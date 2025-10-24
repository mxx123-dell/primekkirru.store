<?php
if (!defined('IN_SITE')) die('The Request Not Found');
if (!class_exists('DB')) require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) {
    @session_save_path(sys_get_temp_dir() . '/sessions');
    @session_start();
}
try { $CMSNT = new DB(); } catch (Throwable $e) { error_log('DB init: '.$e->getMessage()); $CMSNT = null; }
if ($CMSNT && method_exists($CMSNT,'site')) {
    $tz = $CMSNT->site('timezone') ?: 'Asia/Bangkok';
    @date_default_timezone_set($tz);
} else { @date_default_timezone_set('Asia/Bangkok'); }
if (!function_exists('__')) { function __($s){ return $s; } }
function myip(){ if(!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP']; if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { $ips=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']); return trim(end($ips)); } return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'; }
function check_string($data){ if (is_array($data)) return ''; return trim(htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8')); }
function check_path($data){ return str_replace(['../','./','..\\','.\\'],'',(string)$data); }
function site($key){ global $CMSNT; return $CMSNT ? $CMSNT->site($key) : null; }
function curl_get($url){ $ch=curl_init(); curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_URL=>$url,CURLOPT_TIMEOUT=>10,CURLOPT_SSL_VERIFYPEER=>false]); $d=curl_exec($ch); curl_close($ch); return $d; }
function BASE_URL($url=''){ global $CMSNT; $domain = $CMSNT ? $CMSNT->site('domain') : ''; if (empty($domain)) { $scheme = 'https'; if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO']; elseif (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') $scheme = 'https'; elseif (!empty($_SERVER['REQUEST_SCHEME'])) $scheme = $_SERVER['REQUEST_SCHEME']; $host = $_SERVER['HTTP_HOST'] ?? 'localhost'; $domain = "{$scheme}://{$host}"; } return rtrim($domain,'/').'/'.ltrim($url,'/'); }
if (!function_exists('getRowRealtime')) {
    function getRowRealtime($table, $value, $column = 'id') {
        global $CMSNT;
        if (!$CMSNT) return null;
        $table = check_string($table);
        $column = check_string($column);
        $conn = DB::connect();
        $safeValue = is_numeric($value) ? (int)$value : pg_escape_string($conn, (string)$value);
        $sql = "SELECT * FROM \"{$table}\" WHERE \"{$column}\" = '{$safeValue}' LIMIT 1";
        return $CMSNT->get_row($sql);
    }
}

if (!function_exists('getLanguage')) {
    function getLanguage() { return $_COOKIE['language'] ?? 'en'; }
}

function msg_success($text, $url = '', $time = 1000) {
    echo '<script>swal("Thành công","' . addslashes($text) . '","success");';
    if ($url) echo 'setTimeout(()=>{location.href="' . $url . '"},' . (int)$time . ');';
    echo '</script>';
}

function auto_clean_render() {
    foreach (glob(sys_get_temp_dir().'/*') as $f) {
        if (is_file($f) && time() - @filemtime($f) > 3600) @unlink($f);
    }
}

function auto_ping_render(){ $pid=sys_get_temp_dir().'/render_ping.pid'; $interval=600; $scheme=$_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ($_SERVER['REQUEST_SCHEME'] ?? 'https'); $host=$_SERVER['HTTP_HOST'] ?? ''; if(empty($host)) return false; $url = "{$scheme}://{$host}"; if(!file_exists($pid) || (time()-@filemtime($pid))>$interval){ @file_put_contents($pid,time()); @file_get_contents($url);} return true; }
@auto_clean_render(); @auto_ping_render();
?>
