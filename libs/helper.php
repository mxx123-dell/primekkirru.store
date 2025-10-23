<?php
if (!defined('IN_SITE')) die('The Request Not Found');

if (!class_exists('DB')) require_once __DIR__ . '/db.php';

// Bắt đầu session an toàn (fix Render)
if (session_status() === PHP_SESSION_NONE) {
    @session_save_path(sys_get_temp_dir());
    @session_start();
}

// ===== KẾT NỐI DB AN TOÀN =====
try {
    $CMSNT = new DB();
} catch (Throwable $e) {
    error_log('DB Init Error: ' . $e->getMessage());
    $CMSNT = null; // fallback để tránh lỗi fatal
}

// ===== CÀI ĐẶT TIMEZONE =====
if ($CMSNT && method_exists($CMSNT, 'site')) {
    $tz = $CMSNT->site('timezone') ?: 'Asia/Bangkok';
    @date_default_timezone_set($tz);
} else {
    @date_default_timezone_set('Asia/Bangkok');
}

// ===== CHẶN IP BANNED =====
try {
    if ($CMSNT && $CMSNT->fetch("SELECT * FROM banned_ips WHERE ip = '".myip()."' AND banned = 1")) {
        die('<div style="text-align:center;margin-top:50px"><h2>404 Not Found</h2><p>Access Denied</p></div>');
    }
} catch (Throwable $e) {
    error_log("Check banned IP failed: " . $e->getMessage());
}

// ===== HÀM DỊCH ĐƠN GIẢN (TRÁNH LỖI __) =====
if (!function_exists('__')) {
    function __($string) { return $string; }
}

// ===== HÀM LẤY IP NGƯỜI DÙNG =====
function myip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim(end($ips));
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// ===== HÀM BẢO VỆ CHUỖI =====
function check_string($data) {
    if (is_array($data)) return '';
    return trim(htmlspecialchars(addslashes($data)));
}

function check_path($data) {
    return str_replace(['../', './', '..\\', '.\\'], '', $data);
}

// ===== SITE CONFIG =====
function site($key) {
    global $CMSNT;
    return $CMSNT ? $CMSNT->site($key) : null;
}

// ===== CURL HỖ TRỢ API =====
function curl_get($url){
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function curl_post($url,$data=[],$headers=[]){
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => is_array($data) ? http_build_query($data) : $data,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    return $err ? false : $result;
}

// ===== HÀM ĐỊNH DẠNG THỜI GIAN =====
function format_date($time) {
    return date("d/m/Y H:i:s", strtotime($time));
}

function random($string, $int) {
    return substr(str_shuffle($string), 0, $int);
}

function gettime() {
    return date("Y-m-d H:i:s");
}

// ===== TẠO BASE URL TỰ ĐỘNG =====
function BASE_URL($url = '') {
    global $CMSNT;
    $domain = $CMSNT ? $CMSNT->site('domain') : '';
    if (empty($domain)) {
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'https';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $domain = "{$scheme}://{$host}";
    }
    return rtrim($domain, '/') . '/' . ltrim($url, '/');
}

// ===== REDIRECT AN TOÀN =====
function redirect($url) {
    @header('Location: ' . $url);
    exit();
}

// ===== THÔNG BÁO =====
function msg_success($text, $url = '', $time = 1000) {
    echo '<script type="text/javascript">swal("Thành công", "' . $text . '","success");';
    if ($url) echo 'setTimeout(function(){ location.href = "' . $url . '"; }, ' . $time . ');';
    echo '</script>';
}

function msg_error($text, $url = '', $time = 1000) {
    echo '<script type="text/javascript">swal("Thất bại", "' . $text . '","error");';
    if ($url) echo 'setTimeout(function(){ location.href = "' . $url . '"; }, ' . $time . ');';
    echo '</script>';
}

// ===== AUTO CLEAN TEMP (TỐI ƯU CPU + MEM) =====
if (!function_exists('auto_clean_render')) {
    function auto_clean_render() {
        foreach (glob(sys_get_temp_dir().'/*') as $f) {
            if (is_file($f) && time() - filemtime($f) > 3600) @unlink($f);
        }
    }
}

// ===== AUTO PING RENDER CHỐNG NGỦ =====
if (!function_exists('auto_ping_render')) {
    function auto_ping_render() {
        $pid_file = sys_get_temp_dir() . '/render_ping.pid';
        $interval = 600; // 10 phút
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'https';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (empty($host)) return false;
        $url = "{$scheme}://{$host}";
        if (!file_exists($pid_file) || (time() - @filemtime($pid_file)) > $interval) {
            @file_put_contents($pid_file, time());
            @file_get_contents($url);
        }
        return true;
    }
}

// ===== HÀM GIẢ LẬP getLanguage() (fix lỗi thiếu lang.php) =====
if (!function_exists('getLanguage')) {
    function getLanguage($key = '') {
        return $key;
    }
}

// ===== TỰ ĐỘNG GỌI CLEAN & PING =====
auto_clean_render();
auto_ping_render();
?>
