<?php
// db.php - Dev by CMSNT.CO

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class DB {
    private static ?\PgSql\Connection $conn = null;

    public static function connect(): \PgSql\Connection {
        if (!self::$conn) {
            $host = getenv('DB_HOST') ?: 'dpg-d3roc9ruibrs73b64adg-a'; // Render host
            $user = getenv('DB_USERNAME') ?: 'primekkirru_db_user';     // Render username
            $pass = getenv('DB_PASSWORD') ?: 'hqw9ByoG2YNzjJFjIhZe0JMut3dWYcxt';        // Render password
            $db   = getenv('DB_DATABASE') ?: 'primekkirru_db';
            $port = getenv('DB_PORT') ?: '5432';

            $conn_string = "host=$host port=$port dbname=$db user=$user password=$pass";
            $conn = @pg_connect($conn_string);
            if (!$conn) {
                die("❌ Cannot connect to PostgreSQL! 
Host: $host, Port: $port, Database: $db, User: $user, Password: $pass");
            }

            self::$conn = $conn;
        }

        return self::$conn;
    }

    public static function query(string $sql) {
        $conn = self::connect();
        $result = @pg_query($conn, $sql);
        if (!$result) {
            error_log("SQL Error: " . pg_last_error($conn) . " in query: $sql");
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
            while ($row = pg_fetch_assoc($result)) {
                $data[] = $row;
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
            pg_close(self::$conn);
            self::$conn = null;
        }
    }

    public function site(string $key): ?string {
        $conn = self::connect();
        $key_safe = pg_escape_string($conn, $key);
        $result = self::fetch("SELECT value FROM settings WHERE name = '$key_safe' LIMIT 1");
        return $result['value'] ?? null;
    }

    // Thêm phương thức cũ để tương thích helper.php
    public function get_row(string $sql) {
        return $this->fetch($sql);
    }

    public function get_rows(string $sql) {
        return $this->fetchAll($sql);
    }
}
?>
