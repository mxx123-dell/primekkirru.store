<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
} ?>
    <!-- ================= FOOTER ================= -->
    <footer class="iq-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p class="mb-0">
                        © <?=date('Y');?> <?=$CMSNT->site('title');?> - <?=$CMSNT->site('author');?>
                    </p>
                </div>
            </div>
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

    <!-- ================= SCRIPT CẢNH BÁO TỰ ĐỘNG ================= -->
    <script>
    $(document).ready(function() {
        // Thông báo từ hệ thống (nếu có)
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

    <!-- ================= SCRIPT AUTO LOAD SẢN PHẨM ================= -->
    <script>
    $(document).ready(function () {
        console.log("🛒 Bắt đầu tải danh sách sản phẩm...");

        // Hiển thị hiệu ứng loading
        $("#showProduct").html(`
            <div class="col-12 text-center py-5">
                <img src="<?=BASE_URL('public/datum/assets/images/loader.gif');?>" width="80">
                <p class="text-muted mt-3">Đang tải danh sách sản phẩm...</p>
            </div>
        `);

        // Gọi AJAX lấy danh sách sản phẩm
        $.ajax({
            url: "<?= BASE_URL('ajaxs/client/showProduct.php'); ?>",
            type: "GET",
            dataType: "html",
            timeout: 10000,
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
    });
    </script>

    <!-- ================= SCRIPT XỬ LÝ GIAO DIỆN KHÁC ================= -->
    <script>
    // Hiệu ứng nút scroll lên đầu trang
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('#scrollTopBtn').fadeIn();
        } else {
            $('#scrollTopBtn').fadeOut();
        }
    });
    $('#scrollTopBtn').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 400);
        return false;
    });

    // Tự động cập nhật giá trị realtime
    setInterval(function() {
        $(".auto-update").each(function() {
            const url = $(this).data("url");
            const target = $(this);
            if (url) {
                $.get(url, function(data) {
                    target.html(data);
                });
            }
        });
    }, 30000); // mỗi 30s
    </script>

    <!-- ================= CUSTOM SCRIPT ================= -->
    <?=$CMSNT->site('javascript_footer');?>

    <!-- Nút cuộn lên đầu -->
    <button id="scrollTopBtn" title="Lên đầu trang"
        style="display:none;position:fixed;bottom:30px;right:30px;background:<?=$CMSNT->site('theme_color');?>;border:none;padding:10px 15px;border-radius:8px;cursor:pointer;color:white;z-index:999;">
        <i class="fa fa-arrow-up"></i>
    </button>

</body>
</html>
