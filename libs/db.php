<?php
/*
 * CMSNT Optimized DB Class for Low-RAM Hosting (Render, 512MB/0.1CPU)
 * Safe .env loading + auto reconnect + UTF-8 fix
 */

if (!defined('IN_SITE')) {
    define('IN_SITE', true); // fallback nếu chưa define ở index.php
}

/* --------------------------------------------------------
   PHẦN KHỞI TẠO - KHÔNG GỬI HEADER TRƯỚC SESSION_START()
----------------------------------------------------------- */

// Ngăn lỗi "headers already sent"
if (session_status() === PHP_SESSION_NONE) {
    ob_start(); // Bắt đầu output buffer
    session_start();
}

/* --------------------------------------------------------
   LOAD DOTENV (.env)
----------------------------------------------------------- */
include_once(__DIR__ . '/../vendor/autoload.php');

// Load .env an toàn, không crash nếu thiếu
if (file_exists(__DIR__ . '/../.env')) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->safeLoad(); // không lỗi khi thiếu .env
    } catch (Exception $e) {
        error_log('ENV load error: ' . $e->getMessage());
    }
}

/* --------------------------------------------------------
   CLASS DB
----------------------------------------------------------- */

class DB
{
    private $ketnoi = null;

    // ✅ Kết nối MySQL an toàn & nhẹ
    public function connect()
    {
        if ($this->ketnoi && @mysqli_ping($this->ketnoi)) {
            return; // kết nối vẫn ổn, không cần mở lại
        }

        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $user = $_ENV['DB_USERNAME'] ?? 'root';
        $pass = $_ENV['DB_PASSWORD'] ?? '';
        $name = $_ENV['DB_DATABASE'] ?? '';

        $this->ketnoi = @mysqli_connect($host, $user, $pass, $name);

        if (!$this->ketnoi) {
            error_log('⚠️ Database connection failed: ' . mysqli_connect_error());
            die('⚠️ Database connection failed — please check .env or MySQL status');
        }

        mysqli_set_charset($this->ketnoi, 'utf8mb4');
    }

    // ✅ Đóng kết nối khi không cần
    public function dis_connect()
    {
        if ($this->ketnoi) {
            @mysqli_close($this->ketnoi);
            $this->ketnoi = null;
        }
    }

    // ✅ Lấy cấu hình site
    public function site($data)
    {
        $this->connect();
        $stmt = $this->ketnoi->prepare("SELECT `value` FROM `settings` WHERE `name` = ?");
        if (!$stmt) return null;
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['value'] ?? null;
    }

    // ✅ Query cơ bản
    public function query($sql)
    {
        $this->connect();
        return $this->ketnoi->query($sql);
    }

    public function cong($table, $data, $sotien, $where)
    {
        $this->connect();
        return $this->ketnoi->query("UPDATE `$table` SET `$data` = `$data` + '$sotien' WHERE $where");
    }

    public function tru($table, $data, $sotien, $where)
    {
        $this->connect();
        return $this->ketnoi->query("UPDATE `$table` SET `$data` = `$data` - '$sotien' WHERE $where");
    }

    // ✅ INSERT an toàn
    public function insert($table, $data)
    {
        $this->connect();
        $cols = implode(',', array_keys($data));
        $vals = implode("','", array_map([$this->ketnoi, 'real_escape_string'], array_values($data)));
        $sql = "INSERT INTO `$table` ($cols) VALUES ('$vals')";
        return $this->ketnoi->query($sql);
    }

    // ✅ UPDATE
    public function update($table, $data, $where)
    {
        $this->connect();
        $set = '';
        foreach ($data as $k => $v) {
            $set .= "`$k`='" . $this->ketnoi->real_escape_string($v) . "',";
        }
        $sql = "UPDATE `$table` SET " . rtrim($set, ',') . " WHERE $where";
        return $this->ketnoi->query($sql);
    }

    // ✅ DELETE
    public function remove($table, $where)
    {
        $this->connect();
        return $this->ketnoi->query("DELETE FROM `$table` WHERE $where");
    }

    // ✅ Lấy danh sách
    public function get_list($sql)
    {
        $this->connect();
        $result = $this->ketnoi->query($sql);
        if (!$result) return [];
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $rows;
    }

    // ✅ Lấy dòng đầu tiên
    public function get_row($sql)
    {
        $this->connect();
        $result = $this->ketnoi->query($sql);
        if (!$result) return false;
        $row = $result->fetch_assoc();
        $result->free();
        return $row ?: false;
    }

    // ✅ Đếm dòng
    public function num_rows($sql)
    {
        $this->connect();
        $result = $this->ketnoi->query($sql);
        if (!$result) return 0;
        $count = $result->num_rows;
        $result->free();
        return $count;
    }
}
