<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

require_once(__DIR__ . '/../libs/db.php');
require_once(__DIR__ . '/../libs/helper.php');
require_once(__DIR__ . '/../libs/database/users.php');

$CMSNT = new DB();

/* ==========================
   ✅ KIỂM TRA BẢN QUYỀN CMSNT
========================== */
$SECRET_KEY = getenv('SECRET_KEY') ?: null;
$EXPECTED_KEY = 'DichVuRight'; // key nội bộ dùng xác minh bản quyền

if (empty($SECRET_KEY) || $SECRET_KEY !== $EXPECTED_KEY) {
    error_log("❌ CMSNT License check failed: SECRET_KEY mismatch or empty");
    die('Vui lòng kích hoạt bản quyền hợp lệ tại <a href="https://www.cmsnt.co/">CMSNT.CO</a>');
}

/* ==========================
   ✅ KIỂM TRA PHIÊN ADMIN
========================== */
try {
    // Nếu có token cookie
    if (isset($_COOKIE['token'])) {
        $token = check_string($_COOKIE['token']);
        $getUser = $CMSNT->get_row("SELECT * FROM `users` WHERE `token` = '$token' AND `admin` = 1");

        if ($getUser) {
            $_SESSION['admin_login'] = $getUser['token'];
        } else {
            // Sai token hoặc không phải admin
            header('Location: ' . base_url('client/logout'));
            exit();
        }
    }

    // Nếu chưa có session admin thì quay lại trang đăng nhập
    if (!isset($_SESSION['admin_login'])) {
        header('Location: ' . base_url('client/login'));
        exit();
    }

    // Lấy lại thông tin người dùng admin từ session
    $getUser = $CMSNT->get_row("SELECT * FROM `users` WHERE `admin` = 1 AND `token` = '" . check_string($_SESSION['admin_login']) . "'");
    if (!$getUser) {
        header('Location: ' . base_url('client/login'));
        exit();
    }

    // Bị khoá
    if ((int)$getUser['banned'] === 1) {
        header('Location: ' . base_url('common/banned'));
        exit();
    }

    // Âm tiền (bảo vệ bug nạp âm)
    if ($getUser['money'] < 0) {
        if (class_exists('users')) {
            $User = new users();
            $User->Banned($getUser['id'], 'Tài khoản âm tiền, nghi vấn bug');
        }
        header('Location: ' . base_url('common/banned'));
        exit();
    }

    // ✅ Kiểm tra whitelist IP nếu bật
    if ($CMSNT->site('status_security') == 1) {
        $ip = myip();
        if (!$CMSNT->get_row("SELECT * FROM `ip_white` WHERE `ip` = '$ip'")) {
            header('Location: ' . base_url('common/block'));
            exit();
        }
    }

    // ✅ Cập nhật thời gian session
    $CMSNT->update('users', [
        'time_session' => time()
    ], " `id` = '" . $getUser['id'] . "' ");

    // ✅ Kiểm tra danh sách domain bị cấm (nếu có)
    if (isset($domain_black) && is_array($domain_black)) {
        if (in_array($_SERVER['HTTP_HOST'], $domain_black)) {
            error_log("⚠️ Domain blacklisted: " . $_SERVER['HTTP_HOST']);
            die('Tên miền này đã bị chặn. Vui lòng kích hoạt bản quyền tại <a href="https://www.cmsnt.co/">CMSNT.CO</a>');
        }
    }
} catch (Throwable $e) {
    error_log("is_admin.php error: " . $e->getMessage());
    header('Location: ' . base_url('client/login'));
    exit();
}
?>
