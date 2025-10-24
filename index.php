<?php
// ==================== Dev By CMSNT.CO (Đã tối ưu lại + Bảo mật nâng cao) ====================

// ⚙️ Hiển thị lỗi (chỉ bật khi debug)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
define("IN_SITE", true);

// ==================== Load cấu hình và thư viện ====================
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/libs/db.php';       // ⚡ load DB trước helper
require_once __DIR__ . '/libs/helper.php';

// ⚙️ Cấu hình PHP cơ bản
ini_set('memory_limit', '256M');
ini_set('max_execution_time', '60');
ignore_user_abort(true);

// ==================== Khởi tạo DB ====================
$CMSNT = new DB();
$conn = $CMSNT->connect();
if (!$conn) {
    die('<b>❌ Không thể kết nối Database.</b><br>Kiểm tra lại cấu hình trong <code>libs/db.php</code>.');
}

// ==================== Xác định module/action ====================
$module = !empty($_GET['module']) ? check_path($_GET['module']) : 'client';
$action = !empty($_GET['action']) ? check_path($_GET['action']) : 'home';
$ref    = isset($_GET['ref']) ? check_string($_GET['ref']) : null;

// ==================== Danh sách module và action hợp lệ ====================
// 👉 Chỉ cho phép các module này được load (thêm nếu cần)
$allowed_modules = ['client', 'admin'];
// 👉 Các action cấm tuyệt đối
$blocked_actions = ['footer', 'header', 'sidebar', 'nav', '.', '..', 'index'];

// Kiểm tra module hợp lệ
if (!in_array($module, $allowed_modules)) {
    require_once __DIR__ . '/resources/views/common/404.php';
    exit();
}

// Kiểm tra action hợp lệ
if (in_array($action, $blocked_actions) || preg_match('/[^a-zA-Z0-9_\-]/', $action)) {
    require_once __DIR__ . '/resources/views/common/404.php';
    exit();
}

// ==================== Xử lý ref click ====================
if ($ref) {
    try {
        $ref_safe = $CMSNT->escape($ref);
        $domain_row = $CMSNT->get_row("SELECT user_id FROM domains WHERE domain = '$ref_safe' LIMIT 1");
        if (!empty($domain_row['user_id'])) {
            $user_id = (int)$domain_row['user_id'];
            $CMSNT->query("UPDATE users SET ref_click = ref_click + 1 WHERE id = '$user_id' LIMIT 1");
            $_SESSION['ref'] = $user_id;
        }
    } catch (Throwable $e) {
        error_log("[REF_ERROR] " . $e->getMessage());
    }
}

// ==================== Xác định view cần load ====================
$view = __DIR__ . "/resources/views/$module/$action.php";

// ==================== Load trang ====================
if (is_file($view)) {
    require_once $view;
} else {
    // fallback mặc định: shopacc.php nếu client, còn lại 404
    if ($module === 'client') {
        $fallback = __DIR__ . '/resources/views/client/shopacc.php';
        require_once is_file($fallback)
            ? $fallback
            : __DIR__ . '/resources/views/common/404.php';
    } else {
        require_once __DIR__ . '/resources/views/common/404.php';
    }
}

// ==================== AUTO PING (giữ host online) ====================
$ping_file = sys_get_temp_dir() . '/last_ping.txt';
$ping_interval = 600; // 10 phút
$now = time();

if (!file_exists($ping_file) || ($now - @filemtime($ping_file)) > $ping_interval) {
    @file_put_contents($ping_file, $now);
    $scheme = ($_SERVER['REQUEST_SCHEME'] ?? 'https');
    $host = ($_SERVER['HTTP_HOST'] ?? '');
    if (!empty($host)) {
        $url = $scheme . '://' . $host;
        if (function_exists('exec')) {
            @exec("curl -s -o /dev/null $url >/dev/null 2>&1 &");
        }
    }
}

// ==================== Dọn dẹp bộ nhớ ====================
unset($CMSNT, $conn, $module, $action, $ref, $view);
gc_collect_cycles();
ob_end_flush();
