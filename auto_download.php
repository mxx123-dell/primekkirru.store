<?php
/**
 * auto_download.php (Optimized)
 * 
 * Tự động tải file ZIP từ Google Drive và giải nén — bản tối ưu.
 * Sử dụng cURL để xử lý redirect và tránh lỗi timeout.
 * 
 * Dev: Primekkirru | Render Optimized
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$googleDriveFileUrl = "https://drive.google.com/uc?export=download&id=1WyQqke9P1bMPFIYTdvG3D5w18txHdsn2"; // ✅ Link direct
$localZipFile = __DIR__ . "/source.zip";
$extractTo = __DIR__;

echo "🔹 Bắt đầu tải file ZIP...\n";

/**
 * Download file bằng cURL (ổn định hơn file_get_contents)
 */
function downloadFile($url, $path)
{
    $ch = curl_init($url);
    $fp = fopen($path, 'w+');
    curl_setopt_array($ch, [
        CURLOPT_FILE => $fp,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => "Mozilla/5.0",
        CURLOPT_FAILONERROR => true
    ]);

    $result = curl_exec($ch);
    if ($result === false) {
        $error = curl_error($ch);
        curl_close($ch);
        fclose($fp);
        unlink($path);
        exit("❌ Lỗi tải file: $error\n");
    }

    curl_close($ch);
    fclose($fp);
}

downloadFile($googleDriveFileUrl, $localZipFile);

if (!file_exists($localZipFile)) {
    exit("❌ Không tìm thấy file ZIP sau khi tải.\n");
}

$size = round(filesize($localZipFile) / 1024 / 1024, 2);
echo "✅ Đã tải xong file ZIP ($size MB)\n";

/**
 * Giải nén file ZIP
 */
$zip = new ZipArchive;
if ($zip->open($localZipFile) === TRUE) {
    $zip->extractTo($extractTo);
    $zip->close();
    echo "✅ Giải nén hoàn tất tại: $extractTo\n";
    unlink($localZipFile);
    echo "🗑️ Đã xóa file ZIP tạm.\n";
} else {
    exit("❌ Không thể mở file ZIP để giải nén.\n");
}

echo "🎉 Hoàn tất tải & giải nén!\n";
?>
