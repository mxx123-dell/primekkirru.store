<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

// ⚙️ Dùng lại biến $CMSNT được tạo trong index.php
global $CMSNT;

// =======================================================
// 🧩 HÀM LẤY URL HIỆN TẠI
// =======================================================
if (!function_exists('get_url')) {
    function get_url() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443))
            ? "https://" : "http://";
        return $protocol . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '');
    }
}

// =======================================================
// 🧩 HÀM BASE_URL (Tạo đường dẫn tuyệt đối)
// =======================================================
if (!function_exists('BASE_URL')) {
    function BASE_URL($path = '') {
        $base = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http')
              . '://' . ($_SERVER['HTTP_HOST'] ?? '');
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}

// =======================================================
// 🧩 HÀM KIỂM TRA CHUỖI ĐẦU VÀO (chống SQL injection nhẹ)
// =======================================================
if (!function_exists('check_string')) {
    function check_string($data) {
        return trim(htmlspecialchars(addslashes($data), ENT_QUOTES, 'UTF-8'));
    }
}

// =======================================================
// 🧩 HÀM KIỂM TRA TÊN MODULE/ACTION HỢP LỆ
// =======================================================
if (!function_exists('check_path')) {
    function check_path($data) {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '', $data);
    }
}

// =======================================================
// 🧩 HÀM LẤY DÒNG REALTIME USER (theo token)
// =======================================================
if (!function_exists('getRowRealtime')) {
    function getRowRealtime($table, $token, $column = 'token') {
        global $CMSNT;
        return $CMSNT->get_row("SELECT * FROM \"$table\" WHERE \"$column\" = '$token' LIMIT 1");
    }
}

// =======================================================
// 🧩 HÀM GỬI THÔNG BÁO LOG RA FILE (debug dễ hơn)
// =======================================================
if (!function_exists('write_log')) {
    function write_log($msg, $file = 'system.log') {
        $path = __DIR__ . '/../logs';
        if (!is_dir($path)) {
            @mkdir($path, 0755, true);
        }
        $log = "[" . date('Y-m-d H:i:s') . "] " . $msg . PHP_EOL;
        @file_put_contents($path . '/' . $file, $log, FILE_APPEND);
    }
}

// =======================================================
// 🗣️ HÀM HỖ TRỢ DỊCH (Đa ngôn ngữ + tránh lỗi undefined function __())
// =======================================================
if (!function_exists('__')) {
    function __($text) {
        static $lang_data = null;

        // ⚙️ Xác định ngôn ngữ (ưu tiên ?lang= trên URL → session → mặc định 'vi')
        $lang_code = 'vi';
        if (!empty($_GET['lang'])) {
            $lang_code = preg_replace('/[^a-z]/', '', strtolower($_GET['lang']));
            $_SESSION['lang'] = $lang_code;
        } elseif (!empty($_SESSION['lang'])) {
            $lang_code = $_SESSION['lang'];
        }

        // ⚙️ Nạp file ngôn ngữ tương ứng (chỉ load 1 lần để tăng hiệu năng)
        if ($lang_data === null) {
            $lang_file = __DIR__ . '/../lang/' . $lang_code . '.php';
            if (is_file($lang_file)) {
                $lang_data = include $lang_file;
            } else {
                $lang_data = [];
            }
        }

        // ⚙️ Trả về bản dịch nếu có, nếu không có thì giữ nguyên
        return $lang_data[$text] ?? $text;
    }
}
?>
