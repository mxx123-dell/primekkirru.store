<?php 
// Dev By CMSNT.CO
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define("IN_SITE", true);

// Load config và helper
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/libs/helper.php');
require_once(__DIR__ . '/libs/db.php');

// Hàm load lib nếu cần
function load_lib($file) {
    $path = __DIR__ . '/libs/' . $file . '.php';
    if (file_exists($path)) {
        require_once($path);
    }
}

// cấu hình PHP
ini_set('memory_limit', '64M');
ini_set('max_execution_time', '10');
ignore_user_abort(true);

// Xử lý module/action/ref
$module = !empty($_GET['module']) ? check_path($_GET['module']) : 'client';
$action = !empty($_GET['action']) ? check_path($_GET['action']) : 'shopacc'; // 🔥 mặc định shopacc
$ref    = isset($_GET['ref']) ? check_string($_GET['ref']) : null;

// Khởi tạo DB
$CMSNT = new DB();
$CMSNT::connect();

// Xử lý ref click
if ($ref) {
    $ref_safe = pg_escape_string($CMSNT::connect(), $ref);
    $domain_row = $CMSNT->fetch("SELECT user_id FROM domains WHERE domain = '$ref_safe' LIMIT 1");
    if ($domain_row && isset($domain_row['user_id'])) {
        $user_id = (int)$domain_row['user_id'];
        $CMSNT->query("UPDATE users SET ref_click = ref_click + 1 WHERE id = '$user_id' LIMIT 1");
        $_SESSION['ref'] = $user_id;
    }
}

// Chặn truy cập trực tiếp các phần nhỏ
if (in_array($action, ['footer', 'header', 'sidebar', 'nav'])) {
    require_once(__DIR__ . '/resources/views/common/404.php');
    exit();
}

// 🧩 Đường dẫn đến view
$view = __DIR__ . "/resources/views/$module/$action.php";

// Nếu file tồn tại → load bình thường
if (file_exists($view)) {
    require_once($view);
} else {
    // Nếu không có, fallback sang shopacc.php
    if (file_exists(__DIR__ . '/resources/views/client/shopacc.php')) {
        require_once(__DIR__ . '/resources/views/client/shopacc.php');
    } else {
        require_once(__DIR__ . '/resources/views/common/404.php');
    }
}

// Auto Ping (chống Render ngủ)
$ping_file = sys_get_temp_dir() . '/last_ping.txt';
$now = time();
$ping_interval = 600;

if (!file_exists($ping_file) || ($now - @filemtime($ping_file)) > $ping_interval) {
    @file_put_contents($ping_file, $now);
    $url = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? '');
    @exec("curl -s -o /dev/null $url >/dev/null 2>&1 &");
}

unset($CMSNT, $module, $action, $ref, $view);
gc_collect_cycles();
ob_end_flush();
?>
