<?php
// ==========================
// Dev By kk... | Primekkirru-Store.onrender.com | MMO Solution
// ==========================
define("IN_SITE", true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================
// ⚙️ AUTO CLEAN RAM & CPU MỖI LẦN CHẠY
// ==========================
if (function_exists('opcache_reset')) {
    @opcache_reset();
}

if (!empty($_SESSION)) {
    foreach ($_SESSION as $key => $val) {
        if (is_null($val)) unset($_SESSION[$key]);
    }
}

// ==========================
// 🕒 AUTO PING MỖI 10 PHÚT - CHỐNG NGỦ HOST (Render / Free Host)
// ==========================
if (!isset($_SESSION['last_ping']) || time() - $_SESSION['last_ping'] > 600) {
    $_SESSION['last_ping'] = time();

    $pingUrl = "https://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/cron/ping.php";
    @file_get_contents($pingUrl);
}

// ==========================
// 🔧 LOAD CÁC FILE CẦN THIẾT
// ⚠️ Tạm thời bỏ các require nếu chưa có thư mục libs/
// ==========================
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
} else {
    die('Thiếu file cấu hình config.php');
}

// ==========================
// 🧭 ĐIỀU HƯỚNG MODULE / ACTION
// ==========================
$module = $_GET['module'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// ==========================
// 📂 LOAD FILE TRANG (tùy chỉnh sau nếu có resources/)
// ==========================
$path = __DIR__ . "/$action.php";
if (file_exists($path)) {
    require_once $path;
} else {
    echo "<h1>Trang $action chưa tồn tại.</h1>";
}
?>
