<?php
if (!defined('IN_SITE')) die('The Request Not Found');

if (!class_exists('DB')) require_once __DIR__ . '/db.php';

// --- SESSION AN TOÀN (Render) ---
if (session_status() === PHP_SESSION_NONE) {
    @session_save_path(sys_get_temp_dir() . '/sessions');
    @session_start();
}

// --- KHỞI TẠO DB ---
try {
    $CMSNT = new DB();
} catch (Throwable $e) {
    error_log('DB Init Error: ' . $e->getMessage());
    $CMSNT = null;
}

// --- TIMEZONE ---
if ($CMSNT && method_exists($CMSNT, 'site')) {
    $tz = $CMSNT->site('timezone') ?: 'Asia/Bangkok';
    @date_default_timezone_set($tz);
} else {
    @date_default_timezone_set('Asia/Bangkok');
}

// --- CHẶN BANNED IP ---
try {
    if ($CMSNT && $CMSNT->fetch("SELECT * FROM banned_ips WHERE ip = '".myip()."' AND banned = 1")) {
        die('<div style="text-align:center;margin-top:50px"><h2>404 Not Found</h2><p>Access Denied</p></div>');
    }
} catch (Throwable $e) {
    error_log("Check banned IP failed: " . $e->getMessage());
}

// --- HÀM DỊCH ĐƠN GIẢN (nếu lang thiếu) ---
if (!function_exists('__')) { function __($string){ return $string; } }

// --- HELPER CƠ BẢN ---
function myip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim(end($ips));
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function check_string($data) {
    if (is_array($data)) return '';
    return trim(htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8'));
}

function check_path($data) {
    return str_replace(['../', './', '..\\', '.\\'], '', (string)$data);
}

function site($key) {
    global $CMSNT;
    return $CMSNT ? $CMSNT->site($key) : null;
}

// --- CURL ---
function curl_get($url){
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function curl_post($url,$data=[],$headers=[]){
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => is_array($data) ? http_build_query($data) : $data,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    return $err ? false : $result;
}

// --- TIME / RANDOM ---
function format_date($time) { return date("d/m/Y H:i:s", strtotime($time)); }
function random($string, $int) { return substr(str_shuffle($string), 0, $int); }
function gettime() { return date("Y-m-d H:i:s"); }

// ===== BASE_URL (phát hiện https trên Render via X-Forwarded-Proto) =====
function BASE_URL($url = '') {
    global $CMSNT;
    $domain = $CMSNT ? $CMSNT->site('domain') : '';
    if (empty($domain)) {
        $scheme = 'https';
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'];
        } elseif (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $scheme = 'https';
        } elseif (!empty($_SERVER['REQUEST_SCHEME'])) {
            $scheme = $_SERVER['REQUEST_SCHEME'];
        }
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $domain = "{$scheme}://{$host}";
    }
    return rtrim($domain, '/') . '/' . ltrim($url, '/');
}

// --- REDIRECT ---
function redirect($url) { @header('Location: ' . $url); exit(); }

// --- MESSAGES (swal) ---
function msg_success($text, $url = '', $time = 1000) {
    echo '<script type="text/javascript">swal("Thành công", "' . addslashes($text) . '","success");';
    if ($url) echo 'setTimeout(function(){ location.href = "' . $url . '"; }, ' . (int)$time . ');';
    echo '</script>';
}
function msg_error($text, $url = '', $time = 1000) {
    echo '<script type="text/javascript">swal("Thất bại", "' . addslashes($text) . '","error");';
    if ($url) echo 'setTimeout(function(){ location.href = "' . $url . '"; }, ' . (int)$time . ');';
    echo '</script>';
}

// ===== AUTO CLEAN TEMP (reduce mem) =====
if (!function_exists('auto_clean_render')) {
    function auto_clean_render() {
        foreach (glob(sys_get_temp_dir().'/*') as $f) {
            if (is_file($f) && time() - @filemtime($f) > 3600) @unlink($f);
        }
    }
}

// ===== AUTO PING RENDER (avoid sleep) =====
if (!function_exists('auto_ping_render')) {
    function auto_ping_render() {
        $pid_file = sys_get_temp_dir() . '/render_ping.pid';
        $interval = 600;
        $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ($_SERVER['REQUEST_SCHEME'] ?? 'https');
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (empty($host)) return false;
        $url = "{$scheme}://{$host}";
        if (!file_exists($pid_file) || (time() - @filemtime($pid_file)) > $interval) {
            @file_put_contents($pid_file, time());
            @file_get_contents($url);
        }
        return true;
    }
}

// ===== Compatibility helper: getRowRealtime (used by template) =====
if (!function_exists('getRowRealtime')) {
    function getRowRealtime($table, $value, $column = 'id') {
        global $CMSNT;
        if (!$CMSNT) return null;
        $table = check_string($table);
        $column = check_string($column);
        // If numeric id, cast
        $safeValue = is_numeric($value) ? (int)$value : pg_escape_string(DB::connect(), (string)$value);
        $sql = "SELECT * FROM \"{$table}\" WHERE \"{$column}\" = '{$safeValue}' LIMIT 1";
        try {
            return $CMSNT->get_row($sql);
        } catch (Throwable $e) {
            error_log("getRowRealtime error: " . $e->getMessage());
            return null;
        }
    }
}

// ===== Fallback getLanguage if lang.php not loaded =====
if (!function_exists('getLanguage')) {
    function getLanguage() { return $_COOKIE['language'] ?? 'en'; }
}

// auto run maintenance helpers
@auto_clean_render();
@auto_ping_render();
?>
