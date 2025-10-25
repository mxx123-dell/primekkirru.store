<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class DB {
    private static $conn = null;

    /**
     * ===== KẾT NỐI DATABASE =====
     * Dùng biến môi trường nếu có, fallback giá trị mặc định.
     */
    public static function connect() {
        if (!self::$conn) {
            $host = getenv('DB_HOST') ?: 'dpg-d3roc9ruibrs73b64adg-a.frankfurt-postgres.render.com';
            $user = getenv('DB_USERNAME') ?: 'primekkirru_db_user';
            $pass = getenv('DB_PASSWORD') ?: 'hqw9ByoG2YNzjJFjIhZe0JMut3dWYcxt';
            $db   = getenv('DB_DATABASE') ?: 'primekkirru_db';
            $port = getenv('DB_PORT') ?: '5432';

            $conn_string = sprintf(
                "host=%s port=%s dbname=%s user=%s password=%s",
                $host, $port, $db, $user, $pass
            );

            $conn = @pg_connect($conn_string);
            if (!$conn) {
                error_log("❌ Database connection failed: {$host}:{$port}/{$db}");
                die('⚠️ Kết nối cơ sở dữ liệu thất bại. Vui lòng kiểm tra cấu hình!');
            }

            // Tự động đóng kết nối khi script kết thúc
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

    /**
     * ===== TRUY VẤN SQL =====
     */
    public static function query(string $sql) {
        $conn = self::connect();
        $result = @pg_query($conn, $sql);
        if (!$result) {
            $err = pg_last_error($conn);
            error_log("❌ SQL Error: {$err} | Query: {$sql}");
        }
        return $result;
    }

    /**
     * ===== LẤY MỘT DÒNG =====
     */
    public static function fetch(string $sql) {
        $result = self::query($sql);
        return $result ? pg_fetch_assoc($result) : null;
    }

    /**
     * ===== LẤY NHIỀU DÒNG =====
     */
    public static function fetchAll(string $sql): array {
        $result = self::query($sql);
        $data = [];
        if ($result) {
            while ($r = pg_fetch_assoc($result)) {
                $data[] = $r;
            }
        }
        return $data;
    }

    /**
     * ===== ĐẾM DÒNG =====
     */
    public static function numRows(string $sql): int {
        $result = self::query($sql);
        return $result ? pg_num_rows($result) : 0;
    }

    /**
     * ===== ĐÓNG KẾT NỐI =====
     */
    public static function close(): void {
        if (self::$conn) {
            @pg_close(self::$conn);
            self::$conn = null;
        }
    }

    /**
     * ===== LẤY GIÁ TRỊ CẤU HÌNH SITE =====
     */
    public function site(string $key): ?string {
        $conn = self::connect();
        $key_safe = pg_escape_string($conn, $key);
        $result = self::fetch("SELECT value FROM settings WHERE name = '{$key_safe}' LIMIT 1");
        return $result['value'] ?? null;
    }

    /**
     * ===== HÀM TƯƠNG THÍCH (CMSNT CŨ) =====
     */
    public function get_row(string $sql) { return $this->fetch($sql); }
    public function get_rows(string $sql) { return $this->fetchAll($sql); }
    public function get_list(string $sql) { return $this->fetchAll($sql); }

    /**
     * ===== UPDATE DATA =====
     */
    public function update($table, $data = [], $where = '') {
        $conn = self::connect();
        $sets = [];
        foreach ($data as $k => $v) {
            $key = pg_escape_string($conn, $k);
            $val = pg_escape_string($conn, $v);
            $sets[] = "\"{$key}\" = '{$val}'";
        }
        $sql = "UPDATE \"{$table}\" SET " . implode(", ", $sets) . " {$where}";
        $result = @pg_query($conn, $sql);
        if (!$result) {
            error_log("❌ Update failed: " . pg_last_error($conn));
        }
        return $result;
    }

    /**
     * ===== INSERT DATA =====
     */
    public function insert($table, $data = []) {
        $conn = self::connect();
        $cols = [];
        $vals = [];
        foreach ($data as $k => $v) {
            $cols[] = '"' . pg_escape_string($conn, $k) . '"';
            $vals[] = "'" . pg_escape_string($conn, $v) . "'";
        }
        $sql = "INSERT INTO \"{$table}\" (" . implode(",", $cols) . ") VALUES (" . implode(",", $vals) . ")";
        $result = @pg_query($conn, $sql);
        if (!$result) {
            error_log("❌ Insert failed: " . pg_last_error($conn));
        }
        return $result;
    }

    /**
     * ===== ESCAPE STRING =====
     */
    public function escape($string) {
        $conn = self::connect();
        return pg_escape_string($conn, $string);
    }
}
?>
