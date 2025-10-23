<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
require_once(__DIR__ . '/../../../libs/helper.php');
require_once(__DIR__ . '/../../../libs/lang.php');
?>

<div class="sidebar">
    <div class="sidebar-header text-center p-3">
        <a href="<?= BASE_URL(''); ?>">
            <img src="<?= BASE_URL('assets/img/logo.png'); ?>" alt="Logo" style="max-width: 100%; height: auto;">
        </a>
    </div>

    <div class="sidebar-content">
        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL(''); ?>">
                    <i class="fas fa-home"></i> <?= __('Trang chủ'); ?>
                </a>
            </li>

            <?php
            // --- Lấy danh mục sản phẩm ---
            $categories = [];
            try {
                if ($CMSNT) {
                    $categories = $CMSNT->get_list("SELECT * FROM categories WHERE status = 1 ORDER BY stt ASC");
                }
            } catch (Throwable $e) {
                error_log("Sidebar categories load error: " . $e->getMessage());
            }

            if (!empty($categories)):
                foreach ($categories as $cat): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL('category/' . $cat['slug']); ?>">
                            <i class="fas fa-box"></i> <?= htmlspecialchars($cat['name']); ?>
                        </a>
                    </li>
                <?php endforeach;
            endif;
            ?>

            <li class="nav-item mt-2 border-top pt-2">
                <a class="nav-link" href="<?= BASE_URL('cart'); ?>">
                    <i class="fas fa-shopping-cart"></i> <?= __('Giỏ hàng'); ?>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL('orders'); ?>">
                    <i class="fas fa-history"></i> <?= __('Lịch sử đơn hàng'); ?>
                </a>
            </li>

            <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item border-top mt-2 pt-2">
                    <a class="nav-link" href="<?= BASE_URL('profile'); ?>">
                        <i class="fas fa-user"></i> <?= __('Tài khoản của tôi'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= BASE_URL('logout'); ?>">
                        <i class="fas fa-sign-out-alt"></i> <?= __('Đăng xuất'); ?>
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item border-top mt-2 pt-2">
                    <a class="nav-link" href="<?= BASE_URL('login'); ?>">
                        <i class="fas fa-sign-in-alt"></i> <?= __('Đăng nhập'); ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="sidebar-footer p-3 border-top">
        <form action="<?= BASE_URL('change-language'); ?>" method="POST" class="form-inline">
            <label for="language" class="form-label me-2"><?= __('Select Language:'); ?></label>
            <select name="language" id="language" class="form-select form-select-sm" onchange="this.form.submit()">
                <?php
                $langs = [];
                try {
                    $langs = $CMSNT ? $CMSNT->get_list("SELECT * FROM languages WHERE status = 1") : [];
                } catch (Throwable $e) {
                    error_log("Sidebar languages load error: " . $e->getMessage());
                }

                $currentLang = getLanguage();
                if (empty($langs)) {
                    echo '<option value="en">English</option>';
                } else {
                    foreach ($langs as $lang) {
                        $selected = ($lang['lang'] === $currentLang) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($lang['id']) . '" ' . $selected . '>' . htmlspecialchars($lang['name']) . '</option>';
                    }
                }
                ?>
            </select>
        </form>
        <div class="mt-3 small text-muted text-center">
            <strong><?= site('title') ?: 'CMSNT.CO'; ?></strong><br>
            <?= date('Y'); ?> &copy; All Rights Reserved
        </div>
    </div>
</div>

<style>
.sidebar {
    background: #1e1e2d;
    color: #fff;
    min-height: 100vh;
    width: 250px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.sidebar a {
    color: #ccc;
    text-decoration: none;
}
.sidebar a:hover {
    color: #fff;
}
.sidebar .nav-link i {
    width: 20px;
}
.sidebar-footer select {
    background: #2a2a3c;
    border: 1px solid #444;
    color: #fff;
}
</style>
