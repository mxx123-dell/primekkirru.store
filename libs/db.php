<?php
// Bắt đầu session an toàn
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class DB {
    private static $conn;

    // Kết nối PostgreSQL
    public static function connect() {
        if (!self::$conn) {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $user = getenv('DB_USERNAME') ?: 'postgres';
            $pass = getenv('DB_PASSWORD') ?: '';
            $db   = getenv('DB_DATABASE') ?: 'primekkirru_db';
            $port = getenv('DB_PORT') ?: '5432';

            $conn_string = "host=$host port=$port dbname=$db user=$user password=$pass";
            self::$conn = @pg_connect($conn_string);

            if (!self::$conn) {
                error_log("❌ Database connection failed: " . pg_last_error(self::$conn));
                die("Database connection failed!");
            }
        }
        return self::$conn;
    }

    // Query an toàn
    public static function query($sql) {
        $conn = self::connect();
        $result = @pg_query($conn, $sql);
        if (!$result) {
            error_log("SQL Error: " . pg_last_error($conn) . " in query: " . $sql);
        }
        return $result;
    }

    // Lấy 1 bản ghi
    public static function fetch($sql) {
        $result = self::query($sql);
        return $result ? pg_fetch_assoc($result) : null;
    }

    // Lấy tất cả bản ghi
    public static function fetchAll($sql) {
        $result = self::query($sql);
        $data = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Đếm số hàng
    public static function numRows($sql) {
        $result = self::query($sql);
        return $result ? pg_num_rows($result) : 0;
    }

    // Đóng kết nối
    public static function close() {
        if (self::$conn) {
            pg_close(self::$conn);
            self::$conn = null;
        }
    }

    // Lấy cấu hình site
    public function site($key) {
        $conn = self::connect();
        $key = pg_escape_string($conn, $key);
        $result = self::fetch("SELECT value FROM settings WHERE name = '$key' LIMIT 1");
        return $result['value'] ?? null;
    }
}
?>
