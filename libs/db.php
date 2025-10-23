<?php
// db.php - Dev by CMSNT.CO (Fixed & Optimized for Render / PHP 8.2+)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class DB {
    private static $conn = null; // PostgreSQL connection resource

    public static function connect() {
        if (!self::$conn) {
            $host = getenv('DB_HOST') ?: 'dpg-d3roc9ruibrs73b64adg-a';
            $user = getenv('DB_USERNAME') ?: 'primekkirru_db_user';
            $pass = getenv('DB_PASSWORD') ?: 'hqw9ByoG2YNzjJFjIhZe0JMut3dWYcxt';
            $db   = getenv('DB_DATABASE') ?: 'primekkirru_db';
            $port = getenv('DB_PORT') ?: '5432';

            $conn_string = "host={$host} port={$port} dbname={$db} user={$user} password={$pass}";

            $conn = @pg_connect($conn_string);
            if (!$conn) {
                error_log("❌ Cannot connect to PostgreSQL! Host: $host, DB: $db, User: $user");
                die("Database connection failed. Please check credentials or Render config.");
            }

            // Giảm load CPU/memory khi idle
            register_shutdown_function(function() {
                if (self::$conn) {
                    @pg_close(self::$conn);
                    self::$conn = null;
                }
            });

            self::$conn = $conn;
        }
        return self::$conn;
    }

    // Thực thi truy vấn
    public static function query(string $sql) {
        $conn = self::connect();
        $result = @pg_query($conn, $sql);
        if (!$result) {
            $error = pg_last_error($conn);
            error_log("SQL Error: {$error} | Query: {$sql}");
        }
        return $result;
    }

    // Lấy 1 dòng
    public static function fetch(string $sql) {
        $result = self::query($sql);
        return $result ? pg_fetch_assoc($result) : null;
    }

    // Lấy nhiều dòng (fetchAll)
    public static function fetchAll(string $sql): array {
        $result = self::query($sql);
        $data = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Đếm số dòng
    public static function numRows(string $sql): int {
        $result = self::query($sql);
        return $result ? pg_num_rows($result) : 0;
    }

    // Đóng kết nối
    public static function close(): void {
        if (self::$conn) {
            @pg_close(self::$conn);
            self::$conn = null;
        }
    }

    // Lấy giá trị cấu hình từ bảng settings
    public function site(string $key): ?string {
        $conn = self::connect();
        $key_safe = pg_escape_string($conn, $key);
        $result = self::fetch("SELECT value FROM settings WHERE name = '{$key_safe}' LIMIT 1");
        return $result['value'] ?? null;
    }

    // ====== Các hàm tương thích CMSNT ======
    public function get_row(string $sql) {
        return $this->fetch($sql);
    }

    public function get_rows(string $sql) {
        return $this->fetchAll($sql);
    }

    // 🟢 Thêm hàm get_list() để tương thích code cũ
    public function get_list(string $sql): array {
        return $this->fetchAll($sql);
    }
}
?>
