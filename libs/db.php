<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Đọc file .env
function loadEnv($path = __DIR__ . '/../.env') {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$name, $value] = array_map('trim', explode('=', $line, 2));
        $_ENV[$name] = $value;
    }
}
loadEnv();

// Lấy biến môi trường
$db_host = $_ENV['DB_HOST'] ?? 'localhost';
$db_name = $_ENV['DB_DATABASE'] ?? '';
$db_user = $_ENV['DB_USERNAME'] ?? '';
$db_pass = $_ENV['DB_PASSWORD'] ?? '';
$db_port = $_ENV['DB_PORT'] ?? '3306';

// Class DB
class DB {
    private static $conn;

    // Kết nối MySQL
    public static function connect() {
        if (self::$conn) return self::$conn;

        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $user = $_ENV['DB_USERNAME'] ?? '';
        $pass = $_ENV['DB_PASSWORD'] ?? '';
        $name = $_ENV['DB_DATABASE'] ?? '';
        $port = $_ENV['DB_PORT'] ?? '3306';

        // Kiểm tra extension mysqli
        if (!extension_loaded('mysqli')) {
            die('❌ Lỗi: PHP chưa cài extension mysqli. Hãy thêm dòng sau vào Dockerfile:
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli');
        }

        // Kết nối
        $conn = @mysqli_connect($host, $user, $pass, $name, $port);

        if (!$conn) {
            $error = mysqli_connect_error();
            die("❌ Không thể kết nối MySQL: $error<br>Host: $host | Port: $port | DB: $name");
        }

        // Cấu hình UTF-8
        mysqli_set_charset($conn, 'utf8mb4');

        self::$conn = $conn;
        return $conn;
    }

    // Lấy dữ liệu site
    public function site($name) {
        $conn = self::connect();
        $stmt = mysqli_prepare($conn, "SELECT value FROM site_setting WHERE name = ? LIMIT 1");
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, 's', $name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $value);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $value;
    }

    // Chạy query
    public function query($sql) {
        $conn = self::connect();
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo "<b>Lỗi SQL:</b> " . mysqli_error($conn) . "<br><code>$sql</code><br>";
        }
        return $result;
    }

    // Hàm tiện ích
    public function fetch_assoc($sql) {
        $result = $this->query($sql);
        return mysqli_fetch_assoc($result);
    }

    public function fetch_array($sql) {
        $result = $this->query($sql);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function num_rows($sql) {
        $result = $this->query($sql);
        return mysqli_num_rows($result);
    }

    public function insert($table, $data) {
        $conn = self::connect();
        $cols = implode(",", array_keys($data));
        $vals = "'" . implode("','", array_map([$conn, 'real_escape_string'], array_values($data))) . "'";
        return mysqli_query($conn, "INSERT INTO $table ($cols) VALUES ($vals)");
    }

    public function update($table, $data, $where) {
        $conn = self::connect();
        $set = [];
        foreach ($data as $k => $v) {
            $set[] = "$k='" . mysqli_real_escape_string($conn, $v) . "'";
        }
        return mysqli_query($conn, "UPDATE $table SET " . implode(",", $set) . " WHERE $where");
    }

    public function delete($table, $where) {
        $conn = self::connect();
        return mysqli_query($conn, "DELETE FROM $table WHERE $where");
    }
}

// Khởi tạo đối tượng DB
$CMSNT = new DB();
?>
