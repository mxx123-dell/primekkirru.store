<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

$AVVQ = __DIR__ . '/is_license.php';
$V2F2F2 = 'DichVuRight';
$SECRET_KEY = getenv('SECRET_KEY') ?: '';

if ($V2F2F2 !== $SECRET_KEY) {
    error_log("❌ CMSNT License check failed: SECRET_KEY mismatch");
    exit(base64_decode(base64_decode(base64_decode(
        'VTBkV2MySjVRa1ZoVjA1dlZtNVdVMkZYWkc5a1EwSkVZVTFQWjJKNVFtazBZbkZvWW1sQk5rdFRhM0JMVVQwOQ=='
    ))));
}

$CMSNT = new DB();

try {
    if (isset($_COOKIE["token"])) {
        $getUser = $CMSNT->get_row("SELECT * FROM users WHERE token = '".check_string($_COOKIE['token'])."' AND admin = 1");
        if (!$getUser) {
            if (function_exists('BASE_URL')) {
                header("Location: " . BASE_URL('client/logout'));
            }
            exit();
        }
        $_SESSION['admin_login'] = $getUser['token'];
    }

    if (!isset($_SESSION['admin_login'])) {
        if (function_exists('redirect')) redirect(base_url('client/login'));
    } else {
        $getUser = $CMSNT->get_row("SELECT * FROM users WHERE admin = 1 AND token = '".$_SESSION['admin_login']."'");
        if (!$getUser) {
            if (function_exists('redirect')) redirect(base_url('client/login'));
        }

        // Bị khoá
        if ($getUser['banned'] != 0) {
            if (function_exists('redirect')) redirect(base_url('common/banned'));
        }

        // Âm tiền
        if ($getUser['money'] < 0) {
            if (class_exists('users')) {
                $User = new users();
                $User->Banned($getUser['id'], 'Tài khoản âm tiền, nghi vấn bug');
            }
            if (function_exists('redirect')) redirect(base_url('common/banned'));
        }

        // Kiểm tra whitelist IP
        if ($CMSNT->site('status_security') == 1) {
            if (!$CMSNT->get_row("SELECT * FROM ip_white WHERE ip = '".myip()."'")) {
                if (function_exists('redirect')) redirect(base_url('common/block'));
            }
        }

        // Cập nhật session time
        try {
            $CMSNT->update('users', ['time_session' => time()], "id = '".$getUser['id']."'");
        } catch (Throwable $e) {
            error_log('Update session time failed: '.$e->getMessage());
        }
    }

    // Bảo vệ bản quyền
    if (isset($domain_black) && is_array($domain_black) && in_array($_SERVER['HTTP_HOST'], $domain_black)) {
        error_log("⚠️ Domain blacklisted: ".$_SERVER['HTTP_HOST']);
        die('Vui lòng kích hoạt bản quyền tại <a href="https://www.cmsnt.co/">CMSNT.CO</a>');
    }
} catch (Throwable $e) {
    error_log("is_admin.php error: " . $e->getMessage());
    if (function_exists('redirect')) redirect(base_url('client/login'));
    exit;
}
?>
