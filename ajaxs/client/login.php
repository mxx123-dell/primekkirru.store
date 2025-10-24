<?php
define("IN_SITE", true);
require_once __DIR__ . "/../../config.php";
require_once __DIR__ . "/../../libs/db.php";
require_once __DIR__ . "/../../libs/helper.php";
require_once __DIR__ . "/../../libs/lang.php";
require_once __DIR__ . "/../../libs/sendEmail.php";
require_once __DIR__ . "/../../libs/database/users.php";

$CMSNT = new DB();
$User  = new users();

// ✅ Kiểm tra request hợp lệ
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['status' => 'error', 'msg' => 'Phương thức không hợp lệ']));
}

// ✅ Kiểm tra input
if (empty($_POST['username'])) {
    die(json_encode(['status' => 'error', 'msg' => __('Username không được để trống')]));
}
if (empty($_POST['password'])) {
    die(json_encode(['status' => 'error', 'msg' => __('Mật khẩu không được để trống')]));
}

// ✅ Kiểm tra reCAPTCHA nếu bật
if ($CMSNT->site('reCAPTCHA_status') == 1) {
    if (empty($_POST['recaptcha'])) {
        die(json_encode(['status' => 'error', 'msg' => __('Vui lòng xác minh Captcha')]));
    }
    $recaptcha = check_string($_POST['recaptcha']);
    $secretKey = $CMSNT->site('reCAPTCHA_secret_key');
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha");
    $captcha_success = json_decode($verify, true);
    if (empty($captcha_success['success'])) {
        die(json_encode(['status' => 'error', 'msg' => __('Captcha không chính xác')]));
    }
}

// ==================== Xử lý đăng nhập ====================
$username = check_string($_POST['username']);
$password = check_string($_POST['password']);

// ✅ Lấy thông tin user
$getUser = $CMSNT->get_row("SELECT * FROM `users` WHERE `username` = '$username' LIMIT 1");
if (!$getUser) {
    die(json_encode(['status' => 'error', 'msg' => __('Thông tin đăng nhập không chính xác')]));
}

// ✅ Kiểm tra bị khóa
if (!empty($getUser['banned'])) {
    die(json_encode(['status' => 'error', 'msg' => __('Tài khoản của bạn đã bị cấm truy cập')]));
}

// ✅ Kiểm tra mật khẩu
$validPassword = false;
if ($CMSNT->site('type_password') == 'bcrypt') {
    $validPassword = password_verify($password, $getUser['password']);
} else {
    $validPassword = ($getUser['password'] == TypePassword($password));
}

if (!$validPassword) {
    // Giới hạn cấu hình
    $limit_ip   = $config['limit_block_ip_login_client'] ?? 5;
    $limit_user = $config['limit_block_login_client'] ?? 10;

    if ($getUser['login_attempts'] >= $limit_ip) {
        $CMSNT->insert('banned_ips', [
            'ip' => myip(),
            'attempts' => $getUser['login_attempts'],
            'create_gettime' => gettime(),
            'banned' => 1,
            'reason' => __('Đăng nhập thất bại nhiều lần')
        ]);
    }
    if ($getUser['login_attempts'] >= $limit_user) {
        $User->Banned($getUser['id'], __('Đăng nhập thất bại nhiều lần'));
        die(json_encode(['status' => 'error', 'msg' => __('Tài khoản của bạn đã bị tạm khoá do đăng nhập sai nhiều lần')]));
    }

    $CMSNT->cong('users', 'login_attempts', 1, "`id` = '{$getUser['id']}'");
    die(json_encode(['status' => 'error', 'msg' => __('Thông tin đăng nhập không chính xác')]));
}

// ✅ Chống spam login nhanh
$max_time_load = $config['max_time_load'] ?? 3;
if (!empty($getUser['time_request']) && (time() - (int)$getUser['time_request']) < $max_time_load) {
    die(json_encode(['status' => 'error', 'msg' => __('Bạn thao tác quá nhanh, vui lòng thử lại sau')]));
}

// ✅ Xác minh OTP admin nếu bật
if ($getUser['admin'] == 1 && $CMSNT->site('status_otp_login_admin') == 1) {
    $otp_email_token = md5(uniqid() . $getUser['username'] . 'otpmail');
    $otp_email = random('QWERTYUOPASDFGHJKZXCVBNM0123456789', 6);
    $CMSNT->update('users', [
        'otp_token' => $otp_email_token,
        'otp' => $otp_email
    ], "`id` = '{$getUser['id']}'");

    $Mobile_Detect = new Mobile_Detect();
    $chu_de = __('OTP đăng nhập vào hệ thống') . ' ' . $CMSNT->site('title');
    $noi_dung = "
        OTP xác minh đăng nhập tài khoản <b>{$getUser['username']}</b> là 
        <h3 style='color:red;'>$otp_email</h3><br>
        Nếu không phải bạn, vui lòng đổi mật khẩu hoặc liên hệ admin.
        <ul>
            <li>Thời gian: " . gettime() . "</li>
            <li>IP: " . myip() . "</li>
            <li>Thiết bị: " . $Mobile_Detect->getUserAgent() . "</li>
        </ul>
    ";
    sendCSM($getUser['email'], $getUser['username'], $chu_de, $noi_dung, $CMSNT->site('title'));

    die(json_encode([
        'status' => 'verify',
        'url' => base_url('client/verify-otp-mail/' . $otp_email_token),
        'msg' => __('OTP đã được gửi về Email của bạn')
    ]));
}

// ✅ Kiểm tra 2FA
if (!empty($getUser['status_2fa'])) {
    $token_2fa = md5(uniqid() . $getUser['username'] . '2fa');
    $CMSNT->update('users', ['token_2fa' => $token_2fa], "`id` = '{$getUser['id']}'");
    die(json_encode([
        'status' => 'verify',
        'url' => base_url('client/verify/' . $token_2fa),
        'msg' => __('Vui lòng xác minh 2FA để hoàn thành đăng nhập')
    ]));
}

// ✅ Ghi log đăng nhập
$Mobile_Detect = new Mobile_Detect();
$CMSNT->insert('logs', [
    'user_id' => $getUser['id'],
    'ip' => myip(),
    'device' => $Mobile_Detect->getUserAgent(),
    'createdate' => gettime(),
    'action' => __('Đăng nhập thành công vào hệ thống')
]);

// ✅ Cập nhật token đăng nhập mới
$new_token = md5(random('0123456789qwertyuiopasdgjklzxcvbnm', 8) . time());
$CMSNT->update('users', [
    'login_attempts' => 0,
    'ip' => myip(),
    'time_request' => time(),
    'token' => $new_token,
    'time_session' => time(),
    'device' => $Mobile_Detect->getUserAgent()
], "`id` = '{$getUser['id']}'");

// ✅ Gán session và cookie
setcookie("token", $new_token, time() + (int)$CMSNT->site('session_login'), "/");
$_SESSION['login'] = $new_token;

die(json_encode(['status' => 'success', 'msg' => __('Đăng nhập thành công')]));
