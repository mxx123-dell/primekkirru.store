<?php
/**
 * auto_download.php
 * 
 * Tự động tải file ZIP từ Google Drive, giải nén ra thư mục hiện tại.
 * Không cần chỉnh gì thêm — chỉ cần thay link Google Drive file ZIP công khai.
 */

// ✅ Link Google Drive file ZIP (đừng dùng link folder!)
$googleDriveFileUrl = "https://drive.google.com/file/d/1WyQqke9P1bMPFIYTdvG3D5w18txHdsn2/view?usp=drive_link";

// ✅ Tên file ZIP tải về
$localZipFile = "source.zip";

// ✅ Thư mục đích để giải nén
$extractTo = __DIR__;

// --- Không cần chỉnh gì bên dưới ---

echo "🔹 Đang tải file từ Google Drive...\n";

// Tải file từ Google Drive
$download = file_get_contents($googleDriveFileUrl);
if ($download === false) {
    exit("❌ Không thể tải file. Kiểm tra lại link hoặc quyền chia sẻ!\n");
}

file_put_contents($localZipFile, $download);
echo "✅ Tải xong: $localZipFile\n";

// Giải nén
$zip = new ZipArchive;
if ($zip->open($localZipFile) === TRUE) {
    $zip->extractTo($extractTo);
    $zip->close();
    echo "✅ Giải nén thành công!\n";

    // Xóa file ZIP sau khi giải nén
    unlink($localZipFile);
    echo "🗑️ Đã xóa file ZIP sau khi giải nén.\n";
} else {
    echo "❌ Không thể mở file ZIP.\n";
}

echo "🎉 Hoàn tất!\n";
?>
