<?php

if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

// Load DB class if not loaded
if (!class_exists('DB')) {
    require_once __DIR__ . '/db.php';
}

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

$CMSNT = new DB;
if (isset($CMSNT) && method_exists($CMSNT, 'site')) {
    $tz = $CMSNT->site('timezone') ?: 'Asia/Bangkok';
    date_default_timezone_set($tz);
}

// --- FIX get_row() LỖI ---
if($CMSNT->fetch("SELECT * FROM banned_ips WHERE ip = '".myip()."' AND banned = 1")){
    die('<div style="text-align:center;margin-top:50px"><h2>404 Not Found</h2><p>Access Denied</p></div>');
}

/* Giữ nguyên logic cũ của helper.php còn lại */

if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && !empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
}

function load_mobile() {
    $aMobileUA = array('iphone','android','ipad','ipod','iemobile','mobile','kindle','silk','palm','symbian','blackberry','opera mini','opera mobi','bb10');
    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
    foreach ($aMobileUA as $mobileOS) {
        if (strpos($userAgent, $mobileOS) !== false) return true;
    }
    return false;
}

function myip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim(end($ips));
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    return $ip;
}

function is_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function curl_get($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function curl_post($url, $data = array(), $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) return false;
    return $result;
}

function format_date($time) {
    return date("d/m/Y H:i:s", strtotime($time));
}

function check_string($data) {
    if (is_array($data)) return '';
    return trim(htmlspecialchars(addslashes($data)));
}

function check_path($data) {
    return str_replace(array('../', './', '..\\', '.\\'), '', $data);
}

function site($key) {
    global $CMSNT;
    return $CMSNT->site($key);
}

function random($string, $int) {
    return substr(str_shuffle($string), 0, $int);
}

function gettime() {
    return date("Y-m-d H:i:s");
}

function BASE_URL($url) {
    global $CMSNT;
    return $CMSNT->site('domain') . $url;
}

function redirect($url) {
    header('Location: ' . $url);
    exit();
}

function msg_success($text, $url = '', $time = 1000) {
    echo '<script type="text/javascript">swal("Thành công", "' . $text . '","success");';
    if ($url) {
        echo 'setTimeout(function(){ location.href = "' . $url . '"; }, ' . $time . ');';
    }
    echo '</script>';
}

function msg_error($text, $url = '', $time = 1000) {
    echo '<script type="text/javascript">swal("Thất bại", "' . $text . '","error");';
    if ($url) {
        echo 'setTimeout(function(){ location.href = "' . $url . '"; }, ' . $time . ');';
    }
    echo '</script>';
}

if (!function_exists('auto_ping_render')) {
    function auto_ping_render() {
        $pid_file = sys_get_temp_dir() . '/render_ping.pid';
        $interval = 600; // 10 phút
        $url = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? '');
        if (empty($url) || $url === 'https://') return false;
        if (!file_exists($pid_file) || (time() - @filemtime($pid_file)) > $interval) {
            @file_put_contents($pid_file, time());
            @file_get_contents($url);
        }
        return true;
    }
    auto_ping_render();
}

?>
