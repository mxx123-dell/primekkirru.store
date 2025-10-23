<?php
if (!defined('IN_SITE')) die('The Request Not Found');

if (!class_exists('DB')) require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) @session_start();

try {
    $CMSNT = new DB();
} catch (Throwable $e) {
    error_log('DB Init Error: ' . $e->getMessage());
    $CMSNT = null; // Fallback safe mode
}

// Set timezone
if ($CMSNT && method_exists($CMSNT, 'site')) {
    $tz = $CMSNT->site('timezone') ?: 'Asia/Bangkok';
    @date_default_timezone_set($tz);
} else {
    @date_default_timezone_set('Asia/Bangkok');
}

// Chặn banned IP
try {
    if ($CMSNT && $CMSNT->fetch("SELECT * FROM banned_ips WHERE ip = '".myip()."' AND banned = 1")) {
        die('<div style="text-align:center;margin-top:50px"><h2>404 Not Found</h2><p>Access Denied</p></div>');
    }
} catch (Throwable $e) {
    error_log("Check banned IP failed: " . $e->getMessage());
}

// Hàm __() để tránh lỗi khi thiếu ngôn ngữ
if (!function_exists('__')) {
    function __($string) { return $string; }
}

// Helper cơ bản
function myip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim(end($ips));
    } else $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return $ip;
}

function check_string($data) {
    if (is_array($data)) return '';
    return trim(htmlspecialchars(addslashes($data)));
}

function check_path($data) {
    return str_replace(['../', './', '..\\', '.\\'], '', $data);
}

function site($key) {
    global $CMSNT;
    return $CMSNT ? $CMSNT->site($key) : null;
}

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

function format_date($time) {
    return date("d/m/Y H:i:s", strtotime($time));
}

function random($string, $int) {
    return substr(str_shuffle($string), 0, $int);
}

function gettime() {
    return date("Y-m-d H:i:s");
}

function BASE_URL($url = '') {
    global $CMSNT;
    $domain = $CMSNT ? $CMSNT->site('domain') : '';
    if (empty($domain)) {
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'https';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $domain = "{$scheme}://{$host}";
    }
    return rtrim($domain, '/') . '/' . ltrim($url, '/');
}

function redirect($url) {
    @header('Location: ' . $url);
    exit();
}

function msg_success($text, $url = '', $time = 1000) {
    echo '<script type="text/javascript">swal("Thành công", "' . $text . '","success");';
    if ($url) echo 'setTimeout(function(){ location.href = "' . $url . '"; }, ' . $time . ');';
    echo '</script>';
}

function msg_error($text, $url = '', $time = 1000) {
    echo '<script type="text/javascript">swal("Thất bại", "' . $text . '","error");';
    if ($url) echo 'setTimeout(function(){ location.href = "' . $url . '"; }, ' . $time . ');';
    echo '</script>';
}

// Auto ping Render để tránh sleep
if (!function_exists('auto_ping_render')) {
    function auto_ping_render() {
        $pid_file = sys_get_temp_dir() . '/render_ping.pid';
        $interval = 600; // 10 phút
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'https';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (empty($host)) return false;
        $url = "{$scheme}://{$host}";
        if (!file_exists($pid_file) || (time() - @filemtime($pid_file)) > $interval) {
            @file_put_contents($pid_file, time());
            @file_get_contents($url);
        }
        return true;
    }
    auto_ping_render();
}
?>
