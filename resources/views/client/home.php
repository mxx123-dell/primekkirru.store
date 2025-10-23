<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

$body = [
    'title' => $CMSNT->site('title'),
    'desc'   => $CMSNT->site('description'),
    'keyword' => $CMSNT->site('keywords')
];
$body['header'] = '';
$body['footer'] = '';

if ($CMSNT->site('sign_view_product') == 0) {
    if (isset($_COOKIE["token"])) {
        $getUser = $CMSNT->get_row("SELECT * FROM `users` WHERE `token` = '" . check_string($_COOKIE['token']) . "'");
        if (!$getUser) {
            header("location: " . BASE_URL('client/logout'));
            exit();
        }
        $_SESSION['login'] = $getUser['token'];
    }
    if (isset($_SESSION['login'])) {
        require_once(__DIR__ . '/../../../models/is_user.php');
    }
} else {
    require_once(__DIR__ . '/../../../models/is_user.php');
}

if ($CMSNT->site('status_is_change_password') == 1) {
    if (isset($getUser) && $getUser['change_password'] == 0) {
        redirect(base_url('client/is-change-password'));
    }
}

if ($CMSNT->site('is_update_phone') == 1) {
    if (isset($_SESSION['login']) && $getUser['phone'] == '') {
        redirect(base_url('client/profile'));
    }
}

$categoryINT = 0;
if (isset($_GET['category'])) {
    $categoryINT = check_string($_GET['category']);
}

require_once(__DIR__ . '/header.php');
require_once(__DIR__ . '/sidebar.php');
?>

<div class="content-page">
    <div class="container-fluid">
        <div class="row">

            <?php if ($CMSNT->site('thongbao') != ''): ?>
                <div class="col-lg-12">
                    <div class="alert bg-white alert-primary" role="alert">
                        <div class="iq-alert-icon">
                            <i class="ri-alert-line"></i>
                        </div>
                        <div class="iq-alert-text"><?= $CMSNT->site('thongbao'); ?></div>
                    </div>
                </div>
            <?php endif ?>

            <?= $CMSNT->site('html_top_product'); ?>

            <?php if ($CMSNT->site('status_giao_dich_gan_day') == 1 && $CMSNT->site('position_gd_gan_day') == 1) { ?>
                <div class="col-lg-6">
                    <div class="card card-block card-stretch card-height">
                        <div class="card-header d-flex justify-content-between"
                             style="background: <?= $CMSNT->site('theme_color'); ?>;">
                            <div class="header-title">
                                <h5 class="card-title" style="color:white;"><?= __('ĐƠN HÀNG GẦN ĐÂY'); ?></h5>
                            </div>
                        </div>
                        <div class="card-body p-0" style="height:500px;overflow-x:hidden;overflow-y:auto;">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <tbody>
                                    <?php foreach ($CMSNT->get_list("SELECT * FROM `orders` ORDER BY id DESC limit 20") as $orderLog) { ?>
                                        <tr>
                                            <td style="height:20px;">
                                                <lord-icon src="https://cdn.lordicon.com/cllunfud.json" trigger="hover"
                                                           style="width:30px;height:30px"></lord-icon>
                                                <b style="color: green;">...<?= substr(getRowRealtime("users", $orderLog['buyer'], 'username'), -3, 3); ?></b>
                                                <?= __('mua'); ?>
                                                <b style="color: red;"><?= format_cash($orderLog['amount']); ?></b>
                                                <b><?= $orderLog['product_id'] != 0 ? substr(getRowRealtime('products', $orderLog['product_id'], 'name'), 0, 45) . '...' : getRowRealtime('documents', $orderLog['document_id'], 'name'); ?></b>
                                                - <b style="color:blue;"><?= format_currency($orderLog['pay']); ?></b>
                                            </td>
                                            <td><span class="badge badge-primary"><?= timeAgo($orderLog['create_time']); ?></span></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php require_once(__DIR__ . '/shop-account.php'); ?>

        </div>
    </div>
</div>

<?php
if (isset($_POST['hide_notice_popup'])) {
    $_SESSION['hide_notice_popup'] = 1;
    die('<script type="text/javascript">window.history.back().location.reload();</script>');
}
?>

<?php if ($CMSNT->site('notice_popup') != ''): ?>
    <?php if (!isset($_SESSION['hide_notice_popup'])): ?>
        <div class="onboarding-modal modal fade animated" id="notice_popup" role="dialog" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= __('Thông báo'); ?></h5>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            <?= $CMSNT->site('notice_popup'); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="hide_notice_popup"><?= __('Không hiển thị lại'); ?></button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><?= __('Đóng'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                setTimeout(e => {
                    $('#notice_popup').modal({keyboard: true, show: true});
                }, 0);
            });
        </script>
    <?php endif ?>
<?php endif ?>

<?php require_once(__DIR__ . '/footer.php'); ?>

<!-- ✅ FIX: Thêm auto load shop -->
<script>
$(document).ready(function() {
    if (typeof showProduct === "function") {
        console.log("✅ Tự động tải sản phẩm...");
        showProduct();
    } else {
        console.error("⚠️ Lỗi: Hàm showProduct() chưa được load!");
    }
});
</script>
