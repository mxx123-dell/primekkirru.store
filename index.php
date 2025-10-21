<!-- Dev By 𝐤𝐤𝐢𝐫𝐫𝐮 | 𝐏𝐫𝐢𝐦𝐞𝐤𝐤𝐢𝐫𝐫𝐮-𝐒𝐭𝐨𝐫𝐞.𝐨𝐧𝐫𝐞𝐧𝐝𝐞𝐫.𝐜𝐨𝐦 | MMO Solution -->
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
    // Không cần log nếu không ghi được file (Render có thể chặn ghi)
    @file_get_contents($pingUrl);
}

// ==========================
// 🔧 LOAD CÁC FILE CẦN THIẾT
// ==========================
require_once __DIR__ . '/libs/db.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/libs/lang.php';
require_once __DIR__ . '/libs/helper.php';
require_once __DIR__ . '/libs/database/users.php';

$CMSNT = new DB();

// ==========================
// 🧭 ĐIỀU HƯỚNG MODULE / ACTION
// ==========================
$module = !empty($_GET['module']) ? check_path($_GET['module']) : 'client';
$home   = $module === 'client' ? $CMSNT->site('home_page') : 'home';
$action = !empty($_GET['action']) ? check_path($_GET['action']) : $home;

// ==========================
// 👥 REF (GIỚI THIỆU)
// ==========================
$ref = $_GET['ref'] ?? null;
if ($ref) {
    $domain_row = $CMSNT->get_row("SELECT * FROM `domains` WHERE `domain` = '" . check_string($ref) . "'");
    if ($domain_row) {
        $user_row = $CMSNT->get_row("SELECT * FROM `users` WHERE `id` = '" . check_string($domain_row['user_id']) . "'");
        if ($user_row) {
            $_SESSION['ref'] = $user_row['id'];
            $CMSNT->cong('users', 'ref_click', 1, " `id` = '{$user_row['id']}' ");
        }
    }
}

// ==========================
// 🔒 BẢO TRÌ
// ==========================
if ($module === 'client' && $CMSNT->site('status') != 1 && !isset($_SESSION['admin_login'])) {
    require_once __DIR__ . '/resources/views/common/maintenance.php';
    exit();
}

// ==========================
// 🚫 CHẶN LOAD FILE HỆ THỐNG TRỰC TIẾP
// ==========================
if (in_array($action, ['footer', 'header', 'sidebar', 'nav'])) {
    require_once __DIR__ . '/resources/views/common/404.php';
    exit();
}

// ==========================
// 📂 LOAD FILE TRANG
// ==========================
$path = __DIR__ . "/resources/views/$module/$action.php";
if (file_exists($path)) {
    require_once $path;
} else {
    require_once __DIR__ . '/resources/views/common/404.php';
}
?>
