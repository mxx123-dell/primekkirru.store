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
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($body['title']) ? $body['title'] : $CMSNT->site('title'); ?></title>

    <!-- SEO -->
    <link rel="canonical" href="<?= htmlspecialchars(get_url(), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta name="description" content="<?= isset($body['desc']) ? $body['desc'] : $CMSNT->site('desc'); ?>" />
    <meta name="keywords" content="<?= isset($body['keyword']) ? $body['keyword'] : $CMSNT->site('keyword'); ?>">
    <meta name="author" content="<?= $CMSNT->site('author'); ?>" />

    <!-- OG -->
    <meta property="og:title" content="<?= $CMSNT->site('title'); ?>">
    <meta property="og:image" content="<?= BASE_URL($CMSNT->site('image')); ?>">
    <meta property="og:description" content="<?= $CMSNT->site('desc'); ?>">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= BASE_URL($CMSNT->site('favicon')); ?>" />

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL('public/datum/assets/css/backend-plugin.min.css'); ?>">
    <link rel="stylesheet" href="<?= BASE_URL('resources/css/backend.css'); ?>">
    <link rel="stylesheet" href="<?= BASE_URL('resources/css/customize.css'); ?>">

    <!-- jQuery -->
    <script src="<?= BASE_URL('resources/js/jquery.js'); ?>"></script>
    <script src="<?= BASE_URL('public/js/jquery-3.6.0.js'); ?>"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" />

    <!-- SweetAlert2 -->
    <link href="<?= BASE_URL('public/sweetalert2/default.css'); ?>" rel="stylesheet">
    <script src="<?= BASE_URL('public/sweetalert2/sweetalert2.js'); ?>"></script>

    <!-- Cute Alert -->
    <link href="<?= BASE_URL('public/cute-alert/style.css'); ?>" rel="stylesheet">
    <script src="<?= BASE_URL('public/cute-alert/cute-alert.js'); ?>"></script>

    <!-- Lordicon & Recaptcha -->
    <script src="https://cdn.lordicon.com/xdjxvujz.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Theme -->
    <style>
    body { <?= $CMSNT->site('font_family'); ?> }
    .card-product {
        color: white;
        background-image: linear-gradient(to right, 
            <?= $CMSNT->site('theme_color'); ?>, 
            <?= $CMSNT->site('theme_color2') ?: $CMSNT->site('theme_color'); ?>);
    }
    #loading-center {
        background: url(<?= $CMSNT->site('gif_loader') != '' 
            ? BASE_URL($CMSNT->site('gif_loader')) 
            : BASE_URL('public/datum/assets/images/loader.gif'); ?>) no-repeat center;
        background-size: 20%;
        width: 100%;
        height: 100%;
        position: relative;
    }
    </style>

    <!-- Hide loader -->
    <script>
    window.addEventListener("load", () => {
        const loader = document.getElementById("loading-center");
        if (loader) loader.style.display = "none";
    });
    </script>

    <?= $body['header'] ?? ''; ?>
    <?= $CMSNT->site('javascript_header'); ?>
</head>
