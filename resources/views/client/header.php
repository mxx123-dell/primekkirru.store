<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

if (!function_exists('get_url')) {
    function get_url() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || 
                     (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443))
            ? "https://" : "http://";
        return $protocol . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '');
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($body['title'] ?? site('title') ?? 'Shop'); ?></title>

<!-- CSS chính -->
<link rel="stylesheet" href="<?= BASE_URL('public/datum/assets/css/all.min.css'); ?>">
<link rel="stylesheet" href="<?= BASE_URL('public/datum/assets/css/backend.css'); ?>">
<link rel="stylesheet" href="<?= BASE_URL('public/datum/assets/css/customize.css'); ?>">
<link rel="stylesheet" href="<?= BASE_URL('public/datum/assets/css/style.css'); ?>">

<!-- fonts / icons -->
<link rel="stylesheet" href="<?= BASE_URL('public/datum/assets/fonts/fa-solid-900.woff2'); ?>">

</head>
<body>
<!-- header content -->
<header class="site-header">
    <div class="topbar" style="background:#00c5ff;padding:6px 0;">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="<?= BASE_URL(''); ?>"><img src="<?= BASE_URL($CMSNT->site('logo_dark') ?: 'public/datum/assets/images/logo.png'); ?>" alt="logo" style="height:60px;"></a>
            </div>
            <div class="search">
                <form action="<?= BASE_URL('search'); ?>" method="GET">
                    <input class="form-control" name="q" placeholder="Tìm kiếm sản phẩm..." style="width:420px;">
                </form>
            </div>
            <div class="user-actions">
                <!-- cart / user icons -->
                <a href="<?= BASE_URL('client/cart'); ?>" class="btn btn-sm"><i class="fas fa-shopping-cart"></i></a>
                <?php if(isset($_SESSION['login'])): ?>
                    <a href="<?= BASE_URL('client/profile'); ?>" class="btn btn-sm"><?= htmlspecialchars(getRowRealtime('users', $_SESSION['login'], 'token')['username'] ?? 'User'); ?></a>
                <?php else: ?>
                    <a href="<?= BASE_URL('client/login'); ?>" class="btn btn-sm">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL(''); ?>">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL('client/shop-dichvu'); ?>">Dịch vụ</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL('client/shop-document'); ?>">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL('client/recharge'); ?>">Nạp tiền</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL('client/orders'); ?>">Lịch sử</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL('client/blogs'); ?>">Blogs</a></li>
            </ul>
        </div>
    </nav>
</header>
