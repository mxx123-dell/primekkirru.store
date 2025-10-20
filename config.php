<?php
/**
 * config.php
 * Mình đã thêm các đoạn tối ưu bộ nhớ (GC), giới hạn session, và cơ chế "auto wake" (đánh thức) không blocking
 * Auto-wake sẽ gửi 1 request nhẹ tới /ping.php (bạn cần tạo file ping.php ở public/ hoặc chỉnh đường dẫn)
 *
 * Lưu ý:
 * - Đặt file ping.php ở public/ hoặc route /ping trả về 200 OK mà không load DB.
 * - Thư mục data/ phải có quyền ghi (writable) để lưu last_wake timestamp.
 */

/*
 * === TỐI ƯU BỘ NHỚ & HIỆU NĂNG PHP ===
 * Bật GC và thu gom rác mỗi request, giảm giữ object không cần thiết.
 * Điều chỉnh session GC để tự dọn session cũ sau một khoảng ngắn.
 */
if (function_exists('gc_enable')) {
    @gc_enable();
    @gc_collect_cycles();
}

// Giới hạn buffer / debug / opcache (nếu có)
@ini_set('display_errors', 0);
@ini_set('log_errors', 0);
@ini_set('output_buffering', 'Off');
@ini_set('zlib.output_compression', 'Off'); // nếu hosting đã bật gzip thì để hosting xử lý
@ini_set('memory_limit', '256M'); // bạn có thể chỉnh xuống 128M nếu cần
@ini_set('max_execution_time', 30); // tránh script chạy quá lâu

// Session GC: xác suất dọn session = gc_probability/gc_divisor
@ini_set('session.gc_probability', 1);
@ini_set('session.gc_divisor', 100);
@ini_set('session.gc_maxlifetime', 300); // 5 phút

// Nếu có OPcache, giữ kích hoạt nhưng giảm bộ đệm nếu cần
if (function_exists('opcache_get_status')) {
    @ini_set('opcache.enable', 1);
    @ini_set('opcache.revalidate_freq', 2);
}

// Hàm tiện ích non-blocking HTTP request (sử dụng fsockopen)
// Gửi request đơn giản và đóng socket ngay, không chờ response => nhẹ
function http_ping_nonblocking($url) {
    // $url ví dụ: https://example.com/ping.php
    $parts = parse_url($url);
    if (!$parts || !isset($parts['host'])) return false;

    $scheme = isset($parts['scheme']) ? $parts['scheme'] : 'http';
    $host = $parts['host'];
    $path = isset($parts['path']) ? $parts['path'] : '/';
    if (isset($parts['query']) && $parts['query'] !== '') $path .= '?' . $parts['query'];

    $port = ($scheme === 'https') ? 443 : 80;
    $transport = ($scheme === 'https') ? 'ssl://' : '';

    // timeout rất ngắn để không block
    $timeout = 1; // 1 second connect timeout
    $errno = 0; $errstr = '';

    // suppress warnings
    $fp = @fsockopen($transport . $host, $port, $errno, $errstr, $timeout);
    if (!$fp) {
        return false;
    }

    // non-blocking mode: không chờ response
    stream_set_blocking($fp, false);
    stream_set_timeout($fp, 1);

    $req  = "GET " . $path . " HTTP/1.1\r\n";
    $req .= "Host: " . $host . "\r\n";
    $req .= "User-Agent: AutoWake/1.0\r\n";
    $req .= "Connection: Close\r\n\r\n";

    @fwrite($fp, $req);

    // Thoát luôn (không đọc trả về)
    @fclose($fp);
    return true;
}

/**
 * Auto wake (đánh thức) mỗi X giây.
 * - Lưu file last_wake trong data/last_wake_wakeup.txt
 * - Nếu quá interval (mặc định 600s = 10 phút) thì gửi 1 ping non-blocking tới $wake_url
 */
function auto_wake_if_needed($wake_url = null, $interval_seconds = 600) {
    // Mặc định wake tới chính host /ping.php nếu tồn tại
    if (empty($wake_url)) {
        // cố lấy domain chính hoặc route ping mặc định
        if (!empty($_SERVER['HTTP_HOST'])) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $wake_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/ping.php';
        } else {
            return false;
        }
    }

    // Ensure data directory exists
    $data_dir = __DIR__ . '/data';
    if (!is_dir($data_dir)) {
        @mkdir($data_dir, 0755, true);
    }
    $stamp_file = $data_dir . '/last_wake_wakeup.txt';

    $now = time();
    $last = 0;
    if (file_exists($stamp_file)) {
        $last = @intval(@file_get_contents($stamp_file));
    }

    // nếu quá interval hoặc file ko tồn tại -> wake
    if (($now - $last) >= $interval_seconds) {
        // Gửi ping non-blocking
        $sent = @http_ping_nonblocking($wake_url);
        // Cập nhật timestamp dù thất bại để tránh spam liên tục
        @file_put_contents($stamp_file, (string)$now, LOCK_EX);
        return $sent;
    }

    return false;
}

// Thực hiện auto-wake: mặc định 10 phút (600s)
// Nếu bạn muốn thay đổi URL ping, đặt biến $WAKE_URL trong .env hoặc config trước khi include config.php
if (isset($WAKE_URL) && !empty($WAKE_URL)) {
    auto_wake_if_needed($WAKE_URL, intval($WAKE_INTERVAL_SECONDS ?? 600));
} else {
    // Nếu trong .env có DOMAIN_HOST, DOMAIN_CHINH thì ưu tiên
    $default_wake = null;
    if (!empty(getenv('DOMAIN_HOST'))) {
        // DOMAIN_HOST có thể là https://vn124.dvd.vn:2083/ nhưng hosting cPanel port 2083 không phù hợp
        // vì đó là cPanel UI. Mình ưu tiên DOMAIN_CHINH hoặc host hiện tại.
        $default_wake = rtrim(getenv('DOMAIN_HOST'), '/') . '/ping.php';
    } elseif (!empty(getenv('DOMAIN_CHINH'))) {
        $default_wake = rtrim(getenv('DOMAIN_CHINH'), '/') . '/ping.php';
    }

    // Nếu không có env, fallback dùng host hiện tại
    auto_wake_if_needed($default_wake, 600);
}

/* ============================================================
   Phần cấu hình gốc của bạn (mình giữ nguyên nội dung, chỉ chèn
   các đoạn tối ưu ở trên). Bạn có thể chỉnh lại tiếp trong file.
   ============================================================ */

$config = [
    'project'       => '𝐏𝐫𝐢𝐦𝐞𝐤𝐤𝐢𝐫𝐫𝐮-𝐒𝐭𝐨𝐫𝐞.𝐨𝐧𝐫𝐞𝐧𝐝𝐞𝐫.𝐜𝐨𝐦',
    'version'       => '6.7.2',
    'max_time_load' => 4,
    'limit_block_login_client'  => 10,
    'limit_block_ip_login_client'  => 5
];

$config_listbank_auto = [
    'Vietcombank'   => 'Ngân hàng TMCP Ngoại Thương Việt Nam Vietcombank',
    'MBBank'        => 'Ngân hàng TMCP Quân đội MBBank',
    'ACB'           => 'Ngân hàng TMCP Á Châu ACB',
    'Techcombank'   => 'Ngân hàng TMCP Kỹ thương Việt Nam Techcombank',
    'TPBank'        => 'Ngân hàng TMCP Tiên Phong TPBank'
];

$config_listbank = [
    'THESIEURE'      => 'Ví THESIEURE.COM',
    'MOMO'      => 'Ví điện tử MOMO',
    'Zalo Pay'      => 'Ví điện tử Zalo Pay',
    'VietinBank' => 'Ngân hàng TMCP Công thương Việt Nam VietinBank',
    'Vietcombank' => 'Ngân hàng TMCP Ngoại Thương Việt Nam Vietcombank',
    'BIDV' => 'Ngân hàng TMCP Đầu tư và Phát triển Việt Nam BIDV',
    'Agribank' => 'Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam Agribank',
    'OCB' => 'Ngân hàng TMCP Phương Đông OCB',
    'MBBank' => 'Ngân hàng TMCP Quân đội MBBank',
    'Techcombank' => 'Ngân hàng TMCP Kỹ thương Việt Nam Techcombank',
    'ACB' => 'Ngân hàng TMCP Á Châu ACB',
    'VPBank' => 'Ngân hàng TMCP Việt Nam Thịnh Vượng VPBank',
    'TPBank' => 'Ngân hàng TMCP Tiên Phong TPBank',
    'Sacombank' => 'Ngân hàng TMCP Sài Gòn Thương Tín Sacombank',
    'HDBank' => 'Ngân hàng TMCP Phát triển Thành phố Hồ Chí Minh HDBank',
    'VietCapitalBank' => 'Ngân hàng TMCP Bản Việt VietCapitalBank',
    'SCB' => 'Ngân hàng TMCP Sài Gòn SCB',
    'VIB' => 'Ngân hàng TMCP Quốc tế Việt Nam VIB',
    'SHB' => 'Ngân hàng TMCP Sài Gòn - Hà Nội SHB',
    'Eximbank' => 'Ngân hàng TMCP Xuất Nhập khẩu Việt Nam Eximbank',
    'MSB' => 'Ngân hàng TMCP Hàng Hải MSB',
    'CAKE' => 'TMCP Việt Nam Thịnh Vượng - Ngân hàng số CAKE by VPBank CAKE',
    'Ubank' => 'TMCP Việt Nam Thịnh Vượng - Ngân hàng số Ubank by VPBank Ubank',
    'SaigonBank' => 'Ngân hàng TMCP Sài Gòn Công Thương SaigonBank',
    'BacABank' => 'Ngân hàng TMCP Bắc Á BacABank',
    'PVcomBank' => 'Ngân hàng TMCP Đại Chúng Việt Nam PVcomBank',
    'Oceanbank' => 'Ngân hàng Thương mại TNHH MTV Đại Dương Oceanbank',
    'NCB' => 'Ngân hàng TMCP Quốc Dân NCB',
    'ShinhanBank' => 'Ngân hàng TNHH MTV Shinhan Việt Nam ShinhanBank',
    'ABBANK' => 'Ngân hàng TMCP An Bình ABBANK',
    'VietABank' => 'Ngân hàng TMCP Việt Á VietABank',
    'NamABank' => 'Ngân hàng TMCP Nam Á NamABank',
    'PGBank' => 'Ngân hàng TMCP Xăng dầu Petrolimex PGBank',
    'VietBank' => 'Ngân hàng TMCP Việt Nam Thương Tín VietBank',
    'BaoVietBank' => 'Ngân hàng TMCP Bảo Việt BaoVietBank',
    'SeABank' => 'Ngân hàng TMCP Đông Nam Á SeABank',
    'COOPBANK' => 'Ngân hàng Hợp tác xã Việt Nam COOPBANK',
    'LienVietPostBank' => 'Ngân hàng TMCP Bưu Điện Liên Việt LienVietPostBank',
    'KienLongBank' => 'Ngân hàng TMCP Kiên Long KienLongBank',
    'KBank' => 'Ngân hàng Đại chúng TNHH Kasikornbank KBank',
    'GPBank' => 'Ngân hàng Thương mại TNHH MTV Dầu Khí Toàn Cầu GPBank',
    'CBBank' => 'Ngân hàng Thương mại TNHH MTV Xây dựng Việt Nam CBBank',
    'CIMB' => 'Ngân hàng TNHH MTV CIMB Việt Nam CIMB',
    'DBSBank' => 'DBS Bank Ltd - Chi nhánh Thành phố Hồ Chí Minh DBSBank',
    'DongABank' => 'Ngân hàng TMCP Đông Á DongABank',
    'KookminHCM' => 'Ngân hàng Kookmin - Chi nhánh Thành phố Hồ Chí Minh KookminHCM',
    'KookminHN' => 'Ngân hàng Kookmin - Chi nhánh Hà Nội KookminHN',
    'Woori' => 'Ngân hàng TNHH MTV Woori Việt Nam Woori',
    'VRB' => 'Ngân hàng Liên doanh Việt - Nga VRB',
    'StandardChartered' => 'Ngân hàng TNHH MTV Standard Chartered Bank Việt Nam StandardChartered',
    'HongLeong' => 'Ngân hàng TNHH MTV Hong Leong Việt Nam HongLeong',
    'HSBC' => 'Ngân hàng TNHH MTV HSBC (Việt Nam) HSBC',
    'IBKHN' => 'Ngân hàng Công nghiệp Hàn Quốc - Chi nhánh Hà Nội IBKHN',
    'IBKHCM' => 'Ngân hàng Công nghiệp Hàn Quốc - Chi nhánh TP. Hồ Chí Minh IBKHCM',
    'IndovinaBank' => 'Ngân hàng TNHH Indovina IndovinaBank',
    'Nonghyup' => 'Ngân hàng Nonghyup - Chi nhánh Hà Nội Nonghyup',
    'UnitedOverseas' => 'Ngân hàng United Overseas - Chi nhánh TP. Hồ Chí Minh UnitedOverseas',
    'PublicBank' => 'Ngân hàng TNHH MTV Public Việt Nam PublicBank',
    'Kasikorn Bank' => 'Kasikorn Bank',
    'Siam Commercial Bank'  => 'Siam Commercial Bank',
    'Bank of Ayudthya'  => 'Bank of Ayudthya',
    'Krungthai Bank'    => 'Krungthai Bank',
    'Bangkok Bank'      => 'Bangkok Bank',
    'ICICI Bank'        => 'ICICI Bank',
    'HDFC Bank'         => 'HDFC Bank',
    'State Bank of India'   => 'State Bank of India',
    'ABA Bank'     => 'ABA Bank Cambodia',
    'Wing Bank' => 'Wing Bank',
    'Maybank'   => 'Maybank',
    'CIMB Clicks Malaysia' => 'CIMB Clicks Malaysia',
    'United Bank for Africa (UBA)'  => 'United Bank for Africa (UBA)',
    'Wise.com'  => 'Wise.com',
    'Binance'   => 'Binance',
    'Bitcoin'   => 'Bitcoin',
    'USDT'      => 'USDT',
    'Payoneer'  => 'Payoneer',
    'Algérie Poste' => 'Algérie Poste',
    'Paysera'       => 'Paysera',
    'Mercado Pago'  => 'Mercado Pago',
    'Banco Inter'   => 'Banco Inter'
    
];
 
$ip_server_black = [
    '88.198.22.18', // myoglog.com
    '103.153.64.233',   // shopfb.io.vn
    '103.200.24.28', // shophuyga.com
    '103.74.123.2' // bmvia.net
];

$domain_black = [
    'trumbanclone.pw',
    'blog.sieuthicode.net',
    'sieuthidark.com',
    'xubymon36.com',
    'viatrau.me',
    'shopmailco.com',
    'clonebysun.net',
    'phongxu.com',
    'minhclone.com',
    'rdsieuvip.com',
    'sellviaxu.com',
    'autordff.com',
    'huyclone.com',
    'clonengoaiviet.com',
    'dichvuthanhtoan.site',
    'trumrandom.com',
    'mailcovip.com',
    'nguyenlieufacebook.com',
    'sieuthixu.pro',
    'khoichange.com',
    'maihuybao.live',
    'shopclonexu.com',
    'randomxin.com',
    'mailgiare.pro',
    'mailngon24h.online',
    'cloudnetwork365.vn',
    'autorandomvip.com',
    'trumsub.in',
    'shopphucvia.com',
    'uidclone.com',
    'shopbfviet.com',
    'rdbloxfruits.site',
    'adsfb.vin',
    'muarobuxvn.com',
    'mailsieungon.site',
    'storeroblox1s.com',
    'shoprobloxvip.com',
    'sieuthimail.online',
    'binhpc.com',
    'sieuthimail.vn',
    'nguyenlieuquangcao.com',
    'cuahangngon.com',
    'sieuthirobux.online',
    'rdtik.me',
    'thunkinrd.com',
    'chipbf.com',
    'clonevn.site',
    'phznszngrb.site',
    'cloudstorevn.site',
    'anhbastore.com',
    'shopaccgame24h.online',
    'nhuanphatshop.site',
    'acc5k.vn',
    'viprandom.site',
    'accbfgiare.site',
    'randombloxdn.com',
    'randomrbl247.com',
    'nqtam.dev',
    'pdshopbloxfruit.store',
    'boxrandom.online',
    'thanhbinhrdbf.shop',
    'chillstores.store',
    'shopphamthai.com',
    'shoproblox256.site',
    'clonemailco.site',
    'shopnnhrandom.com',
    'dichvusieungon.click',
    'trummailco.store',
    'xn--randomgir-71a7715f.vn',
    'xn--randomlinqun-zbb2i.vn',
    'xn--cahngrandom-96a0211h.vn',
    'xucauvang.vip',
    'clonetds.site',
    'bionemarket.shop',
    'randomvip1.com',
    'randomsieure.shop',
    'rdtuanpro.site',
    'sieurandom.com',
    'shopgauezb.site',
    'hailook.store',
    'sellaccvn.com',
    'bmvia.net',
    'myoglog.com',
    'fbclonetut.vn',
    'hienrom.com',
    'shopbanviagiare.com',
    'shopfb.io.vn',
    'shophuyga.com',
    'randomvip.me',
    'rdfb.online',
    'randomppg.com',
    'rdacccblox.com',
    'random.thanhhoi.store',
    'cuongroblox.store',
    'rdaccgiare.store',
    'mailclonevia.shop',
    'chienrd.online',
    'phntq.site',
    'random285.store',
    'tuandat888.xyz',
    'shopxure.com',
    'randomree.com',
    'randomaccgame.shop',
    'rdbuitiendat.com',
    'huprandom.com',
    'rdsieungon.com',
    'viafb247.com',
    'random15k.com',
    'shoprdtb.net',
    'via2h.com',
    'viausvn.com',
    'thegioiroblox.com',
    'shopclone.fun',
    'muavia25.com',
    'rdtheblue.site',
    'viaclonefb.shop',
    'hungrd.shop',
    'viabm.shop'
];

 
// Nếu host nằm trong blacklist thì die (mình giữ nguyên)
if(in_array($_SERVER['HTTP_HOST'], $domain_black)) {
    echo 'Die';
    exit;
}
