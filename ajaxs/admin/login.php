<?php

define("IN_SITE", true);
require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . "/../../libs/db.php");
require_once(__DIR__ . "/../../libs/lang.php");
require_once(__DIR__ . "/../../libs/helper.php");
require_once(__DIR__ . "/../../libs/sendEmail.php");
require_once(__DIR__ . "/../../libs/database/users.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $CMSNT = new DB();
    $Mobile_Detect = new Mobile_Detect();

    // ✅ Kiểm tra dữ liệu đầu vào
    if (empty($_POST['email'])) {
        die(json_encode(['status' => 'error', 'msg' => 'Email không được để trống']));
    }
    if (empty($_POST['password'])) {
        die(json_encode(['status' => 'error', 'msg' => 'Mật khẩu không được để trống']));
    }

    $email = check_string($_POST['email']);
    $password = check_string($_POST['password']);

    // ✅ Kiểm tra reCAPTCHA nếu bật
    if ($CMSNT->site('reCAPTCHA_status') == 1) {
        if (empty($_POST['recaptcha'])) {
            die(json_encode(['status' => 'error', 'msg' => __('Vui lòng xác minh Captcha')]));
        }
        $recaptcha = check_string($_POST['recaptcha']);
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $CMSNT->site('reCAPTCHA_secret_key') . "&response=$recaptcha";
        $verify = file_get_contents($url);
        $captcha_success = json_decode($verify);
        if ($captcha_success->success == false) {
            die(json_encode(['status' => 'error', 'msg' => __('Vui lòng xác minh Captcha')]));
        }
    }

    // ✅ Chặn IP nước ngoài (nếu cần)
    if (getLocation(myip())['country'] != 'VN') {
        die(json_encode([
            'status' => 'error',
            'msg' => __('Vui lòng dùng địa chỉ IP thật để truy cập quản trị')
        ]));
    }

    // ✅ Lấy thông tin người dùng admin
    $getUser = $CMSNT->get_row("SELECT * FROM `users` WHERE `email` = '$email' AND `admin` = 1 ");
    if (!$getUser) {
        die(json_encode(['status' => 'error', 'msg' => __('Thông tin đăng nhập không chính xác')]));
    }

    // ✅ Chặn spam đăng nhập quá nhanh
    if (isset($getUser['time_request']) && (time() - $getUser['time_request']) < $config['max_time_load']) {
        die(json_encode(['status' => 'error', 'msg' => __('Bạn đang thao tác quá nhanh, vui lòng chờ')]));
    }

    // ✅ Kiểm tra tài khoản bị khóa
    if ($getUser['banned'] == 1) {
        die(json_encode(['status' => 'error', 'msg' => __('Tài khoản của bạn đã bị cấm truy cập')]));
    }

    // ✅ Kiểm tra mật khẩu (bcrypt hoặc custom)
    $validPassword = false;
    if ($CMSNT->site('type_password') == 'bcrypt') {
        $validPassword = password_verify($password, $getUser['password']);
    } else {
        $validPassword = ($getUser['password'] == TypePassword($password));
    }

    if (!$validPassword) {
        // Nếu sai mật khẩu
        if ($getUser['login_attempts'] >= $config['limit_block_ip_login_client']) {
            $CMSNT->insert('banned_ips', [
                'ip' => myip(),
                'attempts' => $getUser['login_attempts'],
                'create_gettime' => gettime(),
                'banned' => 1,
                'reason' => __('Đăng nhập thất bại nhiều lần')
            ]);
        }

        if ($getUser['login_attempts'] >= $config['limit_block_login_client']) {
            $User = new users();
            $User->Banned($getUser['id'], __('Đăng nhập thất bại nhiều lần'));
            die(json_encode(['status' => 'error', 'msg' => __('Tài khoản của bạn đã bị tạm khoá do đăng nhập sai nhiều lần')]));
        }

        $CMSNT->cong('users', 'login_attempts', 1, " `id` = '" . $getUser['id'] . "' ");
        die(json_encode(['status' => 'error', 'msg' => __('Thông tin đăng nhập không chính xác')]));
    }

    // ✅ Nếu mật khẩu đúng — reset lại attempts
    $CMSNT->update("users", [
        'login_attempts' => 0
    ], " `id` = '" . $getUser['id'] . "' ");

    // ✅ Gửi email cảnh báo đăng nhập admin
    $chu_de = 'Cảnh báo: đăng nhập quản trị ' . $CMSNT->site('title');
    $noi_dung = '
        Hệ thống phát hiện IP <b style="color:red;">' . myip() . '</b> vừa đăng nhập tài khoản quản trị (<b>' . $getUser['username'] . '</b>).<br>
        Nếu không phải bạn, vui lòng thay đổi mật khẩu hoặc liên hệ <a target="_blank" href="https://www.cmsnt.co/">CMSNT.CO</a> để được hỗ trợ.<br><br>
        <ul>
            <li>Thời gian: ' . gettime() . '</li>
            <li>IP: ' . myip() . '</li>
            <li>Thiết bị: ' . $Mobile_Detect->getUserAgent() . '</li>
        </ul>';
    $bcc = $CMSNT->site('title');
    sendCSM($CMSNT->site('email'), $getUser['username'], $chu_de, $noi_dung, $bcc);

    // ✅ Nếu có bật 2FA thì chuyển qua trang xác minh
    if ($getUser['status_2fa'] == 1) {
        die(json_encode([
            'status' => 'verify',
            'url' => base_url('admin/verify/' . base64_encode($getUser['token'])),
            'msg' => __('Vui lòng xác minh 2FA để hoàn thành đăng nhập')
        ]));
    }

    // ✅ Ghi log đăng nhập
    $CMSNT->insert("logs", [
        'user_id' => $getUser['id'],
        'ip' => myip(),
        'device' => $Mobile_Detect->getUserAgent(),
        'createdate' => gettime(),
        'action' => __('Đăng nhập thành công vào hệ thống Admin')
    ]);

    // ✅ Cập nhật token mới & cookie/session
    $new_token = md5(random('QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789', 6) . time());
    $CMSNT->update("users", [
        'ip' => myip(),
        'time_request' => time(),
        'time_session' => time(),
        'device' => $Mobile_Detect->getUserAgent(),
        'token' => $new_token
    ], " `id` = '" . $getUser['id'] . "' ");

    setcookie("token", $new_token, time() + $CMSNT->site('session_login'), "/");
    $_SESSION['admin_login'] = $new_token;

    die(json_encode(['status' => 'success', 'msg' => 'Đăng nhập thành công!']));
}
