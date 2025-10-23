<?php
// ==================== Dev By CMSNT.CO (Đã tối ưu lại) ====================

// ⚙️ Hiển thị lỗi khi cần debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define("IN_SITE", true);

// ==================== Load cấu hình và thư viện ====================
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/libs/db.php');       // ⚡ load DB trước helper
require_once(__DIR__ . '/libs/helper.php');

// ==================== Hàm load lib thủ công ====================
function load_lib($file) {
    $path = __DIR__ . '/libs/' . $file . '.php';
    if (file_exists($path)) {
        require_once($path);
    }
}

// ⚙️ Một vài cấu hình PHP cơ bản
ini_set('memory_limit', '256M');
ini_set('max_execution_time', '60');
ignore_user_abort(true);

// ==================== Khởi tạo DB ====================
$CMSNT = new DB();
$conn = $CMSNT->connect(); // ✅ Gọi đúng hàm connect()

if (!$conn) {
    die('❌ Không thể kết nối Database. Kiểm tra lại cấu hình trong libs/db.php');
}

// ==================== Lấy module / action ====================
$module = !empty($_GET['module']) ? check_path($_GET['module']) : 'client';
$action = !empty($_GET['action']) ? check_path($_GET['action']) : 'home';
$ref    = isset($_GET['ref']) ? check_string($_GET['ref']) : null;

// ==================== Xử lý ref click ====================
if ($ref) {
    try {
        $ref_safe = $CMSNT->escape($ref); // ✅ Dùng escape() của DB thay vì pg_escape_string
        $domain_row = $CMSNT->get_row("SELECT user_id FROM domains WHERE domain = '$ref_safe' LIMIT 1");
        if ($domain_row && isset($domain_row['user_id'])) {
            $user_id = (int)$domain_row['user_id'];
            $CMSNT->query("UPDATE users SET ref_click = ref_click + 1 WHERE id = '$user_id' LIMIT 1");
            $_SESSION['ref'] = $user_id;
        }
    } catch (Throwable $e) {
        error_log("Lỗi ref click: " . $e->getMessage());
    }
}

// ==================== Chặn load trực tiếp header/footer ====================
if (in_array($action, ['footer', 'header', 'sidebar', 'nav'])) {
    require_once(__DIR__ . '/resources/views/common/404.php');
    exit();
}

// ==================== Xác định view cần load ====================
$view = __DIR__ . "/resources/views/$module/$action.php";

// ==================== Debug (bật nếu cần test router) ====================
// echo "<!-- VIEW FILE: $view -->";

// ==================== Load trang ====================
if (file_exists($view)) {
    require_once($view);
} else {
    // fallback sang shopacc nếu home không có
    if (file_exists(__DIR__ . '/resources/views/client/shopacc.php')) {
        require_once(__DIR__ . '/resources/views/client/shopacc.php');
    } else {
        require_once(__DIR__ . '/resources/views/common/404.php');
    }
}

// ==================== AUTO PING (chống Render ngủ) ====================
// ⚠️ Một số host không hỗ trợ exec(), nên để tùy chọn bật/tắt ở đây
$ping_file = sys_get_temp_dir() . '/last_ping.txt';
$now = time();
$ping_interval = 600; // 10 phút
if (!file_exists($ping_file) || ($now - @filemtime($ping_file)) > $ping_interval) {
    @file_put_contents($ping_file, $now);
    $url = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? '');
    // ⚙️ Kiểm tra nếu host cho phép exec()
    if (function_exists('exec')) {
        @exec("curl -s -o /dev/null $url >/dev/null 2>&1 &");
    }
}

// ==================== Dọn dẹp bộ nhớ ====================
unset($CMSNT, $module, $action, $ref, $view);
gc_collect_cycles();
ob_end_flush();
