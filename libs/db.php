<?php
// Không được có khoảng trắng hoặc ký tự nào trước dòng này!!!
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class DB {
    private static $conn;

    // 🧩 Kết nối PostgreSQL
    public static function connect() {
        if (!self::$conn) {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $port = getenv('DB_PORT') ?: '5432';
            $dbname = getenv('DB_DATABASE') ?: '';
            $user = getenv('DB_USERNAME') ?: '';
            $pass = getenv('DB_PASSWORD') ?: '';

            $connStr = "host=$host port=$port dbname=$dbname user=$user password=$pass";
            self::$conn = @pg_connect($connStr);

            if (!self::$conn) {
                error_log("❌ Database connection failed: " . pg_last_error());
                die("Database connection failed. Please check .env settings!");
            }
        }
        return self::$conn;
    }

    // 🧩 Thực thi query
    public static function query($sql, $params = []) {
        $conn = self::connect();

        if (!empty($params)) {
            $result = @pg_query_params($conn, $sql, $params);
        } else {
            $result = @pg_query($conn, $sql);
        }

        if (!$result) {
            error_log("SQL Error: " . pg_last_error($conn));
        }
        return $result;
    }

    // 🧩 Lấy 1 dòng dữ liệu
    public static function fetch($sql, $params = []) {
        $result = self::query($sql, $params);
        return $result ? pg_fetch_assoc($result) : null;
    }

    // 🧩 Lấy tất cả dữ liệu
    public static function fetchAll($sql, $params = []) {
        $result = self::query($sql, $params);
        return $result ? pg_fetch_all($result) : [];
    }

    // 🧩 Hàm site() - dùng cho cấu hình website
    public function site($key) {
        $data = self::fetch("SELECT value FROM settings WHERE name = $1", [$key]);
        return $data['value'] ?? null;
    }

    // 🧩 Đếm dòng
    public static function numRows($sql, $params = []) {
        $result = self::query($sql, $params);
        return $result ? pg_num_rows($result) : 0;
    }

    // 🧩 Đóng kết nối
    public static function close() {
        if (self::$conn) {
            pg_close(self::$conn);
            self::$conn = null;
        }
    }
}
?>
