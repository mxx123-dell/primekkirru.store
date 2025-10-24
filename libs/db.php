<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class DB {
    private static $conn = null;

    // ===== KẾT NỐI DATABASE =====
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
                error_log("❌ Postgres connect failed: {$host}:{$port}/{$db}");
                die("Database connection failed. Check environment variables.");
            }

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

    // ===== TRUY VẤN =====
    public static function query(string $sql) {
        $conn = self::connect();
        $result = @pg_query($conn, $sql);
        if (!$result) {
            $err = pg_last_error($conn);
            error_log("SQL Error: {$err} | Query: {$sql}");
        }
        return $result;
    }

    public static function fetch(string $sql) {
        $result = self::query($sql);
        return $result ? pg_fetch_assoc($result) : null;
    }

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

    public static function numRows(string $sql): int {
        $result = self::query($sql);
        return $result ? pg_num_rows($result) : 0;
    }

    public static function close(): void {
        if (self::$conn) {
            @pg_close(self::$conn);
            self::$conn = null;
        }
    }

    // ===== LẤY CẤU HÌNH SITE =====
    public function site(string $key): ?string {
        $conn = self::connect();
        $key_safe = pg_escape_string($conn, $key);
        $result = self::fetch("SELECT value FROM settings WHERE name = '{$key_safe}' LIMIT 1");
        return $result['value'] ?? null;
    }

    // ===== HÀM TƯƠNG THÍCH =====
    public function get_row(string $sql) { return $this->fetch($sql); }
    public function get_rows(string $sql) { return $this->fetchAll($sql); }
    public function get_list(string $sql) { return $this->fetchAll($sql); }

    // ===== UPDATE DATA =====
    public function update($table, $data = [], $where = '') {
        $conn = self::connect();
        $sets = [];
        foreach ($data as $k => $v) {
            $key = pg_escape_string($conn, $k);
            $val = pg_escape_string($conn, $v);
            $sets[] = "\"{$key}\" = '{$val}'";
        }
        $sql = "UPDATE \"{$table}\" SET " . implode(", ", $sets) . " {$where}";
        return @pg_query($conn, $sql);
    }

    // ===== INSERT DATA =====
    public function insert($table, $data = []) {
        $conn = self::connect();
        $cols = [];
        $vals = [];
        foreach ($data as $k => $v) {
            $cols[] = '"' . pg_escape_string($conn, $k) . '"';
            $vals[] = "'" . pg_escape_string($conn, $v) . "'";
        }
        $sql = "INSERT INTO \"{$table}\" (" . implode(",", $cols) . ") VALUES (" . implode(",", $vals) . ")";
        return @pg_query($conn, $sql);
    }
}
?>
