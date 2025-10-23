<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
} ?>
    <!-- ================= FOOTER CLIENT ================= -->
    <footer class="footer text-center mt-5 py-4 border-top">
        <div class="container">
            <p class="mb-1">
                © <?=date('Y');?> <?=$CMSNT->site('title');?> - <?=$CMSNT->site('author');?>
            </p>
            <small class="text-muted">
                Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi ❤️
            </small>
        </div>
    </footer>

    <!-- ================= SCRIPT CƠ BẢN ================= -->
    <script src="<?=BASE_URL('resources/js/jquery.js');?>"></script>
    <script src="<?=BASE_URL('public/datum/assets/js/backend-bundle.min.js');?>"></script>
    <script src="<?=BASE_URL('public/datum/assets/js/app.js');?>"></script>
    <script src="<?=BASE_URL('public/datum/assets/js/customizer.js');?>"></script>
    <script src="<?=BASE_URL('public/cute-alert/cute-alert.js');?>"></script>
    <script src="<?=BASE_URL('public/sweetalert2/sweetalert2.js');?>"></script>
    <script src="https://cdn.lordicon.com/xdjxvujz.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- ================= SCRIPT HIỂN THỊ THÔNG BÁO ================= -->
    <script>
    $(document).ready(function() {
        <?php if (!empty($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?= $_SESSION['alert']['type']; ?>',
                title: '<?= $_SESSION['alert']['title']; ?>',
                html: '<?= $_SESSION['alert']['text']; ?>',
                showConfirmButton: true
            });
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    });
    </script>

    <!-- ================= SCRIPT AJAX LOAD SẢN PHẨM (NẾU CÓ) ================= -->
    <script>
    $(document).ready(function () {
        if ($("#showProduct").length) {
            console.log("🛒 Bắt đầu tải danh sách sản phẩm...");

            $("#showProduct").html(`
                <div class="col-12 text-center py-5">
                    <img src="<?=BASE_URL('public/datum/assets/images/loader.gif');?>" width="80">
                    <p class="text-muted mt-3">Đang tải danh sách sản phẩm...</p>
                </div>
            `);

            $.ajax({
                url: "<?= BASE_URL('ajaxs/client/showProduct.php'); ?>",
                type: "GET",
                dataType: "html",
                success: function (data) {
                    console.log("✅ Đã tải sản phẩm thành công");
                    $("#showProduct").html(data);
                },
                error: function (xhr, status, error) {
                    console.error("❌ Lỗi tải sản phẩm:", xhr.status, xhr.statusText);
                    $("#showProduct").html(`
                        <div class="col-12 text-center text-danger py-5">
                            <i class="fa fa-exclamation-circle fa-2x mb-3"></i>
                            <p>Không thể tải danh sách sản phẩm.<br>Lỗi: ${xhr.status} - ${xhr.statusText}</p>
                            <button class="btn btn-primary mt-3" onclick="location.reload()">Thử lại</button>
                        </div>
                    `);
                }
            });
        }
    });
    </script>

    <!-- ================= SCRIPT ẨN LOADING ================= -->
    <script>
    window.addEventListener("load", function() {
        const loader = document.getElementById("loading-center");
        if (loader) {
            loader.style.transition = "opacity 0.5s ease";
            loader.style.opacity = "0";
            setTimeout(() => loader.style.display = "none", 500);
            console.log("✅ Loader ẩn thành công");
        } else {
            console.warn("⚠️ Không tìm thấy #loading-center");
        }
    });
    </script>

    <!-- ================= CUSTOM FOOTER SCRIPT ================= -->
    <?=$CMSNT->site('javascript_footer');?>

</body>
</html>
