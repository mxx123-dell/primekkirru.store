<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

/* ================================================
   KIỂM TRA NGƯỜI DÙNG ĐĂNG NHẬP
   ================================================ */

$CMSNT = new DB();

// Nếu tồn tại cookie token
if (isset($_COOKIE['token']) && !empty($_COOKIE['token'])) {
    $token = check_string($_COOKIE['token']);
    $getUser = $CMSNT->get_row("SELECT * FROM `users` WHERE `token` = '$token' ");

    if (!$getUser) {
        // Token không hợp lệ → đăng xuất
        redirect(base_url('client/logout'));
    }

    $_SESSION['login'] = $getUser['token'];
}

// Nếu chưa đăng nhập → chuyển hướng
if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
    redirect(base_url('client/login'));
}

// Lấy thông tin người dùng từ session
$getUser = $CMSNT->get_row("SELECT * FROM `users` WHERE `token` = '".check_string($_SESSION['login'])."' LIMIT 1");

if (!$getUser) {
    redirect(base_url('client/login'));
}

// Nếu bị khóa tài khoản
if ((int)$getUser['banned'] !== 0) {
    redirect(base_url('common/banned'));
}

// Nếu tiền âm → tự động khóa và chuyển hướng
if ($getUser['money'] < 0) {
    if (class_exists('users')) {
        $User = new users();
        $User->Banned($getUser['id'], 'Tài khoản âm tiền, nghi ngờ bug');
    }
    redirect(base_url('common/banned'));
}

// Nếu website bật chế độ yêu cầu kích hoạt thành viên
if ($CMSNT->site('status_active_member') == 1) {
    if ((int)$getUser['active'] !== 1) {
        redirect(base_url('common/not-active'));
    }
}

// Cập nhật thời gian hoạt động
$CMSNT->update('users', [
    'update_date' => gettime()
], " `id` = '".$getUser['id']."' ");

// Kiểm tra domain vi phạm bản quyền
if (isset($domain_black) && is_array($domain_black)) {
    if (in_array($_SERVER['HTTP_HOST'], $domain_black)) {
        die('Bạn đang vi phạm bản quyền của CMSNT.CO, vui lòng kích hoạt bản quyền trước khi dùng.<br><a href="https://www.cmsnt.co/">Mua giấy phép kích hoạt tại đây</a>');
    }
}
?>
