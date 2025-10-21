<?php
// ==========================
// Dev By kk... | primekkirru-store-dy5x.onrender.com | MMO Solution
// ==========================
define("IN_SITE", true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================
// ⚙️ AUTO CLEAN RAM & CPU
// ==========================
if (function_exists('opcache_reset')) {
    @opcache_reset();
}

if (!empty($_SESSION)) {
    foreach ($_SESSION as $key => $val) {
        if (is_null($val)) unset($_SESSION[$key]);
    }
}

// ==========================
// 🚀 KIỂM TRA SOURCE ĐÃ GIẢI NÉN CHƯA
// Nếu chưa có thư mục libs/, thì tải & giải nén từ Google Drive
// ==========================
$sourceDir = __DIR__ . '/libs';
if (!is_dir($sourceDir)) {
    echo "🔹 Đang kiểm tra source...\n";

    $zipFile = __DIR__ . "/source.zip";
    $driveUrl = "https://drive.google.com/uc?export=download&id=1WyQqke9P1bMPFIYTdvG3D5w18txHdsn2";

    // Nếu chưa tải file ZIP
    if (!file_exists($zipFile)) {
        echo "📥 Tải source từ Google Drive...\n";
        $ch = curl_init($driveUrl);
        $fp = fopen($zipFile, 'w+');
        curl_setopt_array($ch, [
            CURLOPT_FILE => $fp,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => "Mozilla/5.0",
        ]);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        echo "✅ Tải xong file ZIP.\n";
    }

    // Giải nén nếu có file ZIP
    if (file_exists($zipFile)) {
        echo "📦 Giải nén source...\n";
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo(__DIR__);
            $zip->close();
            unlink($zipFile);
            echo "🎉 Giải nén thành công! Vui lòng reload lại trang.\n";
        } else {
            die("❌ Không thể giải nén file ZIP.\n");
        }
    }

    exit();
}

// ==========================
// 🔧 LOAD FILE CẦN THIẾT
// ==========================
$requiredFiles = [
    '/libs/db.php',
    '/config.php',
    '/libs/lang.php',
    '/libs/helper.php',
    '/libs/database/users.php'
];

foreach ($requiredFiles as $file) {
    $fullPath = __DIR__ . $file;
    if (file_exists($fullPath)) {
        require_once $fullPath;
    } else {
        echo "⚠️ Thiếu file: $file<br>";
    }
}

// ==========================
// 🧭 ĐIỀU HƯỚNG MODULE / ACTION
// ==========================
$module = $_GET['module'] ?? 'client';
$action = $_GET['action'] ?? 'home';
$path = __DIR__ . "/resources/views/$module/$action.php";

// ==========================
// 📂 LOAD TRANG
// ==========================
if (file_exists($path)) {
    require_once $path;
} else {
    echo "<h1>🚧 Trang <b>$module/$action</b> chưa tồn tại.</h1>";
}
?>
