<?php
// db.php
// Dev by primekkirru

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class DB {
    private static ?\PgSql\Connection $conn = null;

    public static function connect(): \PgSql\Connection {
        if (!self::$conn) {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $user = getenv('DB_USERNAME') ?: 'postgres';
            $pass = getenv('DB_PASSWORD') ?: '';
            $db   = getenv('DB_DATABASE') ?: 'primekkirru_db';
            $port = getenv('DB_PORT') ?: '5432';

            $conn_string = "host=$host port=$port dbname=$db user=$user password=$pass";
            $conn = @pg_connect($conn_string);

            if (!$conn) {
                error_log("❌ Database connection failed!");
                die("Database connection failed!");
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
        $key = pg_escape_string($conn, $key);
        $result = self::fetch("SELECT value FROM settings WHERE name = '$key' LIMIT 1");
        return $result['value'] ?? null;
    }
}
?>
