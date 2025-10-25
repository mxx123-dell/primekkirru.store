<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

$body = [
    'title' => __('Đăng nhập').' | '.$CMSNT->site('title'),
    'desc'   => $CMSNT->site('description'),
    'keyword' => $CMSNT->site('keywords')
];
$body['header'] = '
    <link href="'.BASE_URL('public/client/').'assets/css/pages/login/classic/login-2.css" rel="stylesheet" type="text/css" />
';
require_once(__DIR__.'/header.php');
?>

<style>
    .bg-image {
        background-position: center;
        background-size: cover;
    }
</style>

<body class="bg-image" style="background-image: url(<?=BASE_URL($CMSNT->site('bg_login'));?>);">
    <div id="loading"><div id="loading-center"></div></div>
    <div class="wrapper">
        <section class="login-content">
            <div class="container h-100">
                <div class="row align-items-center justify-content-center h-100">
                    <div class="col-md-5">
                        <div class="card p-3 shadow">
                            <div class="card-body">
                                <div class="auth-logo text-center mb-3">
                                    <img src="<?=BASE_URL($CMSNT->site('logo_dark'));?>" class="img-fluid" alt="logo" style="max-height: 70px;">
                                </div>
                                <h3 class="mb-3 font-weight-bold text-center"><?=__('Đăng Nhập');?></h3>

                                <form id="loginForm">
                                    <div class="form-group mb-3">
                                        <label class="text-secondary"><?=__('Tên đăng nhập hoặc Email');?></label>
                                        <input type="text" class="form-control" id="username" placeholder="<?=__('Nhập tên đăng nhập hoặc email');?>">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="text-secondary"><?=__('Mật khẩu');?></label>
                                        <input type="password" class="form-control" id="password" placeholder="<?=__('Nhập mật khẩu');?>">
                                    </div>

                                    <?php if($CMSNT->site('reCAPTCHA_status') == 1): ?>
                                    <div class="form-group text-center mb-3">
                                        <div class="g-recaptcha" data-sitekey="<?=$CMSNT->site('reCAPTCHA_site_key');?>"></div>
                                    </div>
                                    <?php endif; ?>

                                    <button type="button" id="btnLogin" class="btn btn-primary w-100 mt-2"><?=__('Đăng Nhập');?></button>

                                    <div class="text-center mt-3">
                                        <p class="mb-0"><?=__('Chưa có tài khoản?');?> 
                                            <a href="<?=BASE_URL('client/register');?>"><?=__('Đăng ký ngay');?></a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Gọi footer -->
    <?php require_once(__DIR__.'/footer.php'); ?>

<script type="text/javascript">
$("#btnLogin").on("click", function() {
    $('#btnLogin').html('<i class="fa fa-spinner fa-spin"></i> <?=__('Đang xử lý...');?>').prop('disabled', true);
    
    $.ajax({
        url: "<?=base_url('ajaxs/client/login.php');?>",
        method: "POST",
        dataType: "JSON",
        data: {
            username: $("#username").val(),
            password: $("#password").val(),
            recaptcha: $("#g-recaptcha-response").val()
        },
        success: function(response) {
            if (response.status === 'success') {
                cuteToast({
                    type: "success",
                    message: response.msg,
                    timer: 4000
                });
                setTimeout(() => {
                    window.location.href = "<?=BASE_URL('client/home');?>";
                }, 500);
            } else {
                Swal.fire('<?=__('Thất bại');?>', response.msg, 'error');
            }
            $('#btnLogin').html('<?=__('Đăng Nhập');?>').prop('disabled', false);
        },
        error: function(xhr, status, error) {
            Swal.fire('Lỗi!', 'Không thể xử lý yêu cầu. Vui lòng thử lại.', 'error');
            $('#btnLogin').html('<?=__('Đăng Nhập');?>').prop('disabled', false);
        }
    });
});
</script>
</body>
</html>
