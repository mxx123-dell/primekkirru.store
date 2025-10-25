<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

// ✅ Load file hệ thống nếu chưa có $CMSNT
if (!isset($CMSNT)) {
    $paths = [
        __DIR__ . '/../../../libs/db.php',
        __DIR__ . '/../../../libs/helper.php'
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
        }
    }
}

// ✅ Hàm lấy URL hiện tại
if (!function_exists('get_url')) {
    function get_url() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
                    (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443))
            ? "https://" : "http://";
        return $protocol . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '');
    }
}

// ✅ Hàm lấy giá trị site an toàn (tránh lỗi khi $CMSNT chưa tồn tại)
if (!function_exists('safe_site')) {
    function safe_site($key, $default = '') {
        global $CMSNT;
        if (isset($CMSNT) && method_exists($CMSNT, 'site')) {
            return $CMSNT->site($key) ?? $default;
        }
        return $default;
    }
}

// ✅ Lấy thông tin user nếu đã đăng nhập
$user = null;
if (isset($_SESSION['login'])) {
    $user = getRowRealtime('users', $_SESSION['login'], 'token');
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= htmlspecialchars($body['title'] ?? safe_site('title', 'Shop Primekkirru-Store')); ?></title>
<meta name="description" content="<?= htmlspecialchars($body['desc'] ?? safe_site('description')); ?>">
<meta name="keywords" content="<?= htmlspecialchars($body['keyword'] ?? safe_site('keywords')); ?>">

<!-- ✅ CSS chính -->
<link rel="stylesheet" href="<?= BASE_URL('public/client/assets/css/all.min.css'); ?>">
<link rel="stylesheet" href="<?= BASE_URL('public/client/assets/css/backend.css'); ?>">
<link rel="stylesheet" href="<?= BASE_URL('public/client/assets/css/customize.css'); ?>">
<link rel="stylesheet" href="<?= BASE_URL('public/client/assets/css/style.css'); ?>">

<!-- ✅ FontAwesome -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      integrity="sha512-/D+nZQ7z8A1qEUdF8YoEfY9sN2I64ZT9+8l8kCS4tmHuWAh5K+7nQe4+R03q1HkMJ6QvRVKZr8D8bT0M3Q9u9Q=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- ✅ Thêm header custom -->
<?= $body['header'] ?? ''; ?>
</head>

<body>
<header class="site-header">
    <div class="topbar" style="background:#00c5ff;padding:6px 0;">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="<?= BASE_URL(''); ?>">
                    <img src="<?= BASE_URL(safe_site('logo_dark', 'public/client/assets/img/logo.png')); ?>" 
                         alt="logo" style="height:55px;">
                </a>
            </div>

            <div class="search">
                <form action="<?= BASE_URL('client/search'); ?>" method="GET" class="d-flex">
                    <input class="form-control" name="q" placeholder="Tìm kiếm sản phẩm ..." style="width:420px;">
                    <button class="btn btn-light" type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <div class="user-actions">
                <a href="<?= BASE_URL('client/cart'); ?>" class="btn btn-sm"><i class="fas fa-shopping-cart"></i></a>
                <?php if($user): ?>
                    <a href="<?= BASE_URL('client/profile'); ?>" class="btn btn-sm btn-primary">
                        <?= htmlspecialchars($user['username'] ?? 'Tài khoản'); ?>
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL('client/login'); ?>" class="btn btn-sm btn-outline-dark">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ✅ Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL(''); ?>">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL('client/shop-dichvu'); ?>">Dịch vụ</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL('client/shop-document'); ?>">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL('client/recharge'); ?>">Nạp tiền</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL('client/orders'); ?>">Lịch sử</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL('client/blogs'); ?>">Blogs</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL('client/contact'); ?>">Liên hệ</a></li>
            </ul>
        </div>
    </nav>
</header>
