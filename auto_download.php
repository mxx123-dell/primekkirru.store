<?php
/**
 * AUTO DOWNLOAD GOOGLE DRIVE FILE TO SERVER
 * Dev By Primekkirru | Primekkirru-Store.onrender.com
 * Use: Auto download file zip or script source from Google Drive to host
 */

set_time_limit(0);

// === CONFIG ===
$googleDriveFileUrl = "https://drive.google.com/uc?export=download&id=1ZfcslRyhTy_GDkP-nmocO0wtp3QiVHjx"; // ⚠️ ID thay bằng ID file thực, không phải folder
$localFilePath = __DIR__ . "/Source_200K_Clone_V6_Update_Full.zip"; // nơi lưu file tải về

// === FUNCTIONS ===
function getGoogleDriveDownloadUrl($url) {
    if (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
        return "https://drive.google.com/uc?export=download&id=" . $matches[1];
    }
    return null;
}

function downloadFile($url, $path) {
    $fp = fopen($path, 'w+');
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

// === MAIN ===
echo "<pre>🔁 Đang tải file từ Google Drive...</pre>";

$downloadUrl = getGoogleDriveDownloadUrl($googleDriveFileUrl);
if (!$downloadUrl) {
    exit("❌ Không tìm thấy ID file Google Drive hợp lệ.");
}

downloadFile($downloadUrl, $localFilePath);

if (file_exists($localFilePath)) {
    echo "<pre>✅ Tải file thành công: " . basename($localFilePath) . "</pre>";

    // Nếu là file zip, tự động giải nén
    if (pathinfo($localFilePath, PATHINFO_EXTENSION) === 'zip') {
        echo "<pre>📦 Đang giải nén...</pre>";
        $zip = new ZipArchive;
        if ($zip->open($localFilePath) === TRUE) {
            $zip->extractTo(__DIR__);
            $zip->close();
            echo "<pre>✅ Giải nén thành công!</pre>";
        } else {
            echo "<pre>❌ Giải nén thất bại.</pre>";
        }
    }
} else {
    echo "<pre>❌ Không thể tải file.</pre>";
}

echo "<pre>🎉 Hoàn tất!</pre>";
?>
