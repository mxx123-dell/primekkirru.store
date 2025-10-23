<?php
// showProduct.php - fully featured, fixed for CMSNT-like shop
// Replace the existing file at /ajaxs/client/showProduct.php
// Backup original before replacing!

if (!defined('IN_SITE')) {
    // If called directly (not via index), define minimal constant so helper loads OK
    define('IN_SITE', true);
}

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../libs/db.php';
require_once __DIR__ . '/../../libs/helper.php';
require_once __DIR__ . '/../../libs/lang.php';

// init DB object (safe if already created)
$CMSNT = new DB();
if (method_exists($CMSNT, 'connect')) {
    @$CMSNT::connect();
}

// Ensure output is HTML
header('Content-Type: text/html; charset=utf-8');

// --- Helpers local (avoid fatal if helper missing) ---
if (!function_exists('e')) {
    function e($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
}

// Normalize inputs (support both AJAX POST and direct GET for debugging)
$input = $_POST + $_GET;

$action   = isset($input['action']) ? check_string($input['action']) : ''; // optional actions in original code
$id       = isset($input['id']) ? (int)$input['id'] : 0;
$category = isset($input['category']) ? (int)$input['category'] : 0;
$q        = isset($input['q']) ? trim($input['q']) : ''; // search query
$page     = isset($input['page']) ? max(1, (int)$input['page']) : 1;
$perpage  = isset($input['limit']) ? max(1, (int)$input['limit']) : 12;
$sort     = isset($input['sort']) ? check_string($input['sort']) : 'stt'; // sort field
$store    = isset($input['store']) ? check_string($input['store']) : 'accounts'; // original code sometimes used store

// Validate perpage (cap)
if ($perpage > 48) $perpage = 48;
$offset = ($page - 1) * $perpage;

// If an id provided -> return product detail or small card (used by modal)
if ($id > 0) {
    $product = $CMSNT->get_row("SELECT * FROM products WHERE id = '$id' LIMIT 1");
    if (!$product) {
        echo '<div class="col-12 text-center py-4 text-muted">Sản phẩm không tồn tại.</div>';
        exit;
    }

    // count remaining
    if (!empty($product['id_connect_api']) && $product['id_connect_api'] != 0) {
        $conlai = isset($product['api_stock']) ? (int)$product['api_stock'] : 0;
    } else {
        $r = $CMSNT->get_row("SELECT COUNT(id) AS c FROM accounts WHERE product_id = '". $product['id'] ."' AND buyer IS NULL AND status = 'LIVE'");
        $conlai = isset($r['c']) ? (int)$r['c'] : 0;
    }

    // build html - detailed card
    ?>
    <div class="product-detail">
        <div class="row">
            <div class="col-md-5 text-center">
                <?php if (!empty($product['preview'])): ?>
                    <img src="<?= BASE_URL($product['preview']); ?>" class="img-fluid" alt="<?= e($product['name']); ?>">
                <?php else: ?>
                    <img src="<?= BASE_URL('public/datum/assets/images/no-image.png'); ?>" class="img-fluid" alt="<?= e($product['name']); ?>">
                <?php endif; ?>
            </div>
            <div class="col-md-7">
                <h4><?= e($product['name']); ?></h4>
                <p><?= nl2br(e($product['content'])); ?></p>
                <p><strong>Giá: </strong> <?= function_exists('format_currency') ? format_currency($product['price']) : number_format($product['price'],0,',','.'); ?></p>
                <p><strong>Còn lại: </strong> <?= function_exists('format_cash') ? format_cash($conlai) : $conlai; ?></p>
                <?php if ($conlai <= 0): ?>
                    <button class="btn btn-secondary" disabled>HẾT HÀNG</button>
                <?php else: ?>
                    <button class="btn btn-primary" onclick="modalBuy(<?= $product['id']; ?>, <?= $product['price']; ?>, `<?= e($product['name']); ?>`)">Mua ngay</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    exit;
}

// Build WHERE clause for list
$whereClauses = ["status = 1"];

// Category filter
if ($category > 0) {
    $whereClauses[] = "category_id = '". $category ."'";
}

// Search text
if ($q !== '') {
    $q_esc = str_replace("'", "''", $q);
    $whereClauses[] = "(name ILIKE '%{$q_esc}%' OR content ILIKE '%{$q_esc}%')";
}

// hide empty products setting
$hide_empty = $CMSNT->site('hide_product_empty') == 1 ? true : false;

// Combine where
$where = "WHERE " . implode(' AND ', $whereClauses);

// Count total for pagination (best-effort)
$total_row = 0;
try {
    $count_row = $CMSNT->get_row("SELECT COUNT(id) AS c FROM products {$where}");
    $total_row = isset($count_row['c']) ? (int)$count_row['c'] : 0;
} catch (Exception $ex) {
    $total_row = 0;
}

// Fetch products with order and limit
// Validate sort - allow only a whitelist to avoid SQL injection
$allowed_sort = ['stt','id','price','sold','name'];
if (!in_array($sort, $allowed_sort)) $sort = 'stt';

$listQuery = "SELECT * FROM products {$where} ORDER BY {$sort} ASC LIMIT {$perpage} OFFSET {$offset}";
$listProduct = $CMSNT->get_list($listQuery);

// If nothing, return friendly HTML
if (!$listProduct) {
    echo '<div class="col-12"><div class="text-center py-5 text-muted">Chưa có sản phẩm nào.</div></div>';
    exit;
}

// Render product cards (grid). Keep markup consistent with theme.
foreach ($listProduct as $product) {
    // compute remain
    if (!empty($product['id_connect_api']) && $product['id_connect_api'] != 0) {
        $conlai = isset($product['api_stock']) ? (int)$product['api_stock'] : 0;
    } else {
        $r = $CMSNT->get_row("SELECT COUNT(id) AS c FROM accounts WHERE product_id = '". $product['id'] ."' AND buyer IS NULL AND status = 'LIVE'");
        $conlai = isset($r['c']) ? (int)$r['c'] : 0;
    }

    // hide if empty and configured
    if ($hide_empty && $conlai == 0) continue;

    $preview = !empty($product['preview']) ? BASE_URL($product['preview']) : BASE_URL('public/datum/assets/images/no-image.png');
    $category_image = BASE_URL(getRowRealtime("categories", $product['category_id'], 'image'));
    $name = e($product['name']);
    $content = e($product['content'] ?? '');
    $price = function_exists('format_currency') ? format_currency($product['price']) : number_format($product['price'],0,',','.');
    $sold = isset($product['sold']) ? (int)$product['sold'] : 0;
    $flag_html = function_exists('getFlag') ? getFlag($product['flag']) : '';

    // Output html per card (matches many theme styles)
    ?>
    <div class="col-sm-6 col-md-6 col-lg-4 mt-4 mt-md-3">
        <div class="basic-drop-shadow p-3 shadow-showcase">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <p>
                        <img class="mr-1" src="<?= $category_image; ?>" width="25px">
                        <b><?= $name; ?></b>
                    </p>
                    <p style="font-size:12px;"><i class="fas fa-angle-right mr-1"></i><i><?= $content; ?></i></p>
                </div>
                <div class="col-md-7">
                    <span class="btn mb-1 btn-sm btn-outline-danger">Giá: <b><?= $price; ?></b></span>
                    <span class="btn mb-1 btn-sm btn-outline-info">Còn lại: <b><?= function_exists('format_cash') ? format_cash($conlai) : $conlai; ?></b></span>
                    <?php if ($CMSNT->site('display_sold') == 1): ?>
                        <span class="btn mb-1 btn-sm btn-outline-success">Đã bán: <b><?= $sold; ?></b></span>
                    <?php endif; ?>
                    <?php if ($CMSNT->site('display_country') == 1): ?>
                        <span class="btn mb-1 btn-sm btn-outline-warning">Quốc gia: <?= $flag_html; ?></span>
                    <?php endif; ?>
                    <?php if ($CMSNT->site('display_preview') == 1 && !empty($product['preview'])): ?>
                        <span class="btn mb-1 btn-sm btn-outline-success">
                            <div class="thumbnail-mobile"><img src="<?= BASE_URL($product['preview']); ?>"></div>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="col-md-5">
                    <?php if ($CMSNT->site('display_rating') == 1): 
                        // rating calculation (safe)
                        $total_review = $CMSNT->get_row("SELECT COUNT(id) AS c FROM reviews WHERE product_id = '". $product['id'] ."'");
                        $total_user_rating_row = $CMSNT->get_row("SELECT SUM(rating) AS s FROM reviews WHERE product_id = '". $product['id'] ."'");
                        $total_review_n = isset($total_review['c']) ? (int)$total_review['c'] : 0;
                        $total_user_rating = isset($total_user_rating_row['s']) ? (int)$total_user_rating_row['s'] : 0;
                        $average_rating = $total_review_n == 0 ? 5 : number_format($total_user_rating / $total_review_n, 1);
                    ?>
                        <div class="text-center">
                            <i class="fas fa-star <?= $average_rating >= 1 ? 'text-warning' : 'star-light'; ?> mr-1 main_star"></i>
                            <i class="fas fa-star <?= $average_rating >= 2 ? 'text-warning' : 'star-light'; ?> mr-1 main_star"></i>
                            <i class="fas fa-star <?= $average_rating >= 3 ? 'text-warning' : 'star-light'; ?> mr-1 main_star"></i>
                            <i class="fas fa-star <?= $average_rating >= 4 ? 'text-warning' : 'star-light'; ?> mr-1 main_star"></i>
                            <i class="fas fa-star <?= $average_rating >= 5 ? 'text-warning' : 'star-light'; ?> mr-1 main_star"></i>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4"></div>
                    <?php if ($conlai == 0): ?>
                        <button class="btn btn-block btn-secondary" disabled><i class="fas fa-frown mr-1"></i>HẾT HÀNG</button>
                    <?php else: ?>
                        <button class="btn btn-block btn-primary" onclick="modalBuy(<?= $product['id']; ?>, <?= $product['price']; ?>, `<?= addslashes($name); ?>`)">
                            <i class="fas fa-shopping-cart mr-1"></i>MUA NGAY
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
} // end foreach

// Optionally include simple pagination controls (if multiple pages)
$total_pages = $perpage ? ceil($total_row / $perpage) : 1;
if ($total_pages > 1) {
    echo '<div class="col-12"><nav><ul class="pagination justify-content-center mt-3">';
    for ($p = 1; $p <= $total_pages; $p++) {
        $active = $p == $page ? 'active' : '';
        echo '<li class="page-item '.$active.'"><a href="javascript:void(0)" onclick="loadProductPage('.$p.')" class="page-link">'.$p.'</a></li>';
    }
    echo '</ul></nav></div>';
    // Add small JS helper for pagination (if not present on site)
    ?>
    <script>
    function loadProductPage(p) {
        // this function expects you have jQuery and the original showProduct() or AJAX loader
        // We'll call AJAX to refresh the product list (POST)
        $.post("<?= BASE_URL('ajaxs/client/showProduct.php'); ?>", { page: p, limit: <?= $perpage; ?>, category: <?= $category; ?> }, function (resp) {
            $("#showProduct").html(resp);
            // scroll to products
            $('html, body').animate({ scrollTop: $("#showProduct").offset().top - 80 }, 300);
        }).fail(function () {
            console.error('Không thể tải trang sản phẩm.');
        });
    }
    </script>
    <?php
}

exit;
?>
