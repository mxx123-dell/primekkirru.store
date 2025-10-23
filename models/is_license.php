<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

// Ensure DB wrapper exists
if (!class_exists('DB')) {
    require_once __DIR__ . '/../libs/db.php';
}

try {
    $CMSNT = new DB();
} catch (Throwable $e) {
    error_log('is_license: DB init error: '.$e->getMessage());
    $CMSNT = null; // continue in safe mode
}

/**
 * Simple whitelist check (local development / trusted host)
 */
function checkWhiteDomain($domain){
    $current = $_SERVER['SERVER_NAME'] ?? ($_SERVER['HTTP_HOST'] ?? '');
    $domain_white = [$current];
    foreach($domain_white as $row){
        if($row === $domain){
            return true;
        }
    }
    return false;
}

/**
 * Contact remote license server (with safe timeouts / fallbacks)
 */
function CMSNT_check_license($licensekey, $localkey='') {
    // Use global config if available
    global $config;
    $whmcsurl = 'https://client.cmsnt.co/'; // remote endpoint base
    $licensing_secret_key = $config['project'] ?? getenv('PROJECT') ?? '';
    $localkeydays = 15;
    $allowcheckfaildays = 5;
    $check_token = time() . md5(mt_rand(100000000, mt_getrandmax()) . $licensekey);
    $checkdate = date("Ymd");
    $domain = $_SERVER['SERVER_NAME'] ?? ($_SERVER['HTTP_HOST'] ?? '');
    $usersip = $_SERVER['SERVER_ADDR'] ?? ($_SERVER['LOCAL_ADDR'] ?? '127.0.0.1');
    $dirpath = dirname(__FILE__);
    $verifyfilepath = 'modules/servers/licensing/verify.php';

    $localkeyvalid = false;
    $localkeyresults = [];

    // Validate localkey if provided (existing behavior)
    if ($localkey) {
        $localkey = str_replace(["\r","\n"], '', $localkey);
        $localdata = substr($localkey, 0, max(0, strlen($localkey) - 32));
        $md5hash = substr($localkey, max(0, strlen($localkey) - 32));
        if ($md5hash === md5($localdata . $licensing_secret_key)) {
            $localdata = strrev($localdata);
            $md5hash_inner = substr($localdata, 0, 32);
            $localdata = substr($localdata, 32);
            $localdata = base64_decode($localdata);
            $localkeyresults = json_decode($localdata, true);
            $originalcheckdate = $localkeyresults['checkdate'] ?? '';
            if ($md5hash_inner === md5($originalcheckdate . $licensing_secret_key)) {
                $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y")));
                if ($originalcheckdate > $localexpiry) {
                    $localkeyvalid = true;
                    $results = $localkeyresults;
                    // validate domain/ip/dir if present
                    $validdomains = explode(',', $results['validdomain'] ?? '');
                    if (!in_array($domain, $validdomains)) {
                        $localkeyvalid = false;
                        $results = [];
                    }
                    $validips = explode(',', $results['validip'] ?? '');
                    if (!in_array($usersip, $validips)) {
                        $localkeyvalid = false;
                        $results = [];
                    }
                    $validdirs = explode(',', $results['validdirectory'] ?? '');
                    if (!in_array($dirpath, $validdirs)) {
                        $localkeyvalid = false;
                        $results = [];
                    }
                }
            }
        }
    }

    // If not valid locally, perform remote check (safe)
    if (!$localkeyvalid) {
        $responseCode = 0;
        $postfields = [
            'licensekey' => $licensekey,
            'domain' => $domain,
            'ip' => $usersip,
            'dir' => $dirpath,
        ];
        if ($check_token) $postfields['check_token'] = $check_token;

        $query_string = http_build_query($postfields);

        $data = '';
        // Prefer curl with timeouts
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, rtrim($whmcsurl, '/') . '/' . ltrim($verifyfilepath, '/'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            $data = @curl_exec($ch);
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErr = curl_error($ch);
            curl_close($ch);
            if ($curlErr) {
                error_log('CMSNT_check_license curl error: ' . $curlErr);
            }
        } else {
            // Fallback to fsockopen, non-blocking style with short timeout
            $urlParts = parse_url($whmcsurl);
            $host = $urlParts['host'] ?? $whmcsurl;
            $port = $urlParts['scheme'] === 'https' ? 443 : 80;
            $path = (isset($urlParts['path']) ? rtrim($urlParts['path'],'/') : '') . '/' . ltrim($verifyfilepath, '/');
            $fp = @fsockopen(($urlParts['scheme']==='https'?'ssl://':'') . $host, $port, $errno, $errstr, 3);
            if ($fp) {
                $out = "POST {$path} HTTP/1.1\r\n";
                $out .= "Host: {$host}\r\n";
                $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
                $out .= "Content-Length: " . strlen($query_string) . "\r\n";
                $out .= "Connection: Close\r\n\r\n";
                $out .= $query_string;
                stream_set_timeout($fp, 4);
                fwrite($fp, $out);
                $response = '';
                while (!feof($fp)) {
                    $response .= fgets($fp, 1024);
                }
                fclose($fp);
                // try to extract body
                $parts = preg_split("/\r\n\r\n/", $response, 2);
                if (isset($parts[1])) $data = $parts[1];
                // attempt to parse status line
                if (preg_match('/HTTP\/\d+\.\d+\s+(\d+)/', $response, $m)) {
                    $responseCode = intval($m[1]);
                }
            } else {
                error_log("CMSNT_check_license fsockopen failed: $errno $errstr");
            }
        }

        // handle response
        if ($responseCode != 200 || !$data) {
            // fallback to localkeyresults if still valid recently
            $localexpiry = date("Ymd", mktime(0,0,0, date("m"), date("d") - ($localkeydays + $allowcheckfaildays), date("Y")));
            if (!empty($localkeyresults) && ($localkeyresults['checkdate'] ?? '') > $localexpiry) {
                $results = $localkeyresults;
            } else {
                $results = [];
                $results['status'] = "Invalid";
                $results['description'] = "Remote Check Failed";
                return $results;
            }
        } else {
            // parse simple XML-like <tag>value</tag> pairs into array as original
            preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
            $results = [];
            foreach ($matches[1] as $k => $v) {
                $results[$v] = $matches[2][$k];
            }
        }

        // validate md5 if provided
        if (isset($results['md5hash']) && isset($licensing_secret_key)) {
            if ($results['md5hash'] != md5($licensing_secret_key . $check_token)) {
                $results['status'] = "Invalid";
                $results['description'] = "MD5 Checksum Verification Failed";
                return $results;
            }
        }

        if (($results['status'] ?? '') === "Active") {
            $results['checkdate'] = $checkdate;
            $data_encoded = json_encode($results);
            $data_encoded = base64_encode($data_encoded);
            $data_encoded = md5($checkdate . $licensing_secret_key) . $data_encoded;
            $data_encoded = strrev($data_encoded);
            $data_encoded = $data_encoded . md5($data_encoded . $licensing_secret_key);
            $data_encoded = wordwrap($data_encoded, 80, "\n", true);
            $results['localkey'] = $data_encoded;
        }
        $results['remotecheck'] = true;
    }

    // cleanup
    unset($postfields, $data, $matches, $whmcsurl, $licensing_secret_key, $checkdate, $usersip, $localkeydays, $allowcheckfaildays);
    return $results;
}

/**
 * Convenience wrapper
 */
function checkLicenseKey($licensekey){
    $results = CMSNT_check_license($licensekey, '');
    $out = [
        'status' => false,
        'msg' => 'Không tìm thấy giấy phép này trong hệ thống'
    ];
    $status = $results['status'] ?? '';
    if ($status === "Active") {
        $out['msg'] = "Giấy phép hợp lệ";
        $out['status'] = true;
        $out = array_merge($out, $results);
        return $out;
    }
    if ($status === "Invalid") {
        $out['msg'] = $results['description'] ?? "Giấy phép kích hoạt không hợp lệ";
        return $out;
    }
    if ($status === "Expired") {
        $out['msg'] = "Giấy phép mã nguồn đã hết hạn, vui lòng gia hạn ngay";
        return $out;
    }
    if ($status === "Suspended") {
        $out['msg'] = "Giấy phép của bạn đã bị tạm ngưng";
        return $out;
    }
    return $out;
}


// MAIN: show license form only when domain not white and license not active
$serverName = $_SERVER['SERVER_NAME'] ?? ($_SERVER['HTTP_HOST'] ?? '');
$site_license = null;
try {
    if ($CMSNT && method_exists($CMSNT,'site')) {
        $site_license = $CMSNT->site('license_key');
    }
} catch (Throwable $e) {
    error_log('is_license: cannot read site license: '.$e->getMessage());
    $site_license = '';
}

if (!checkWhiteDomain($serverName)) {
    $check = checkLicenseKey($site_license ?: '');
    if (!($check['status'] ?? false)) {
        // handle POST save (admin submitting license)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSaveLicense'])) {
            // Demo restriction
            try {
                if ($CMSNT && $CMSNT->site('status_demo') != 0) {
                    die('<script type="text/javascript">alert("Không được dùng chức năng này vì đây là trang web demo.");history.back();</script>');
                }
            } catch (Throwable $e) {}

            // Save posted fields to settings
            foreach ($_POST as $key => $value) {
                if ($key === 'btnSaveLicense') continue;
                $k = check_string($key);
                $v = check_string($value);
                try {
                    if ($CMSNT) $CMSNT->update("settings", ['value' => $v], " `name` = '$k' ");
                } catch (Throwable $e) {
                    error_log('is_license: update setting failed: '.$e->getMessage());
                }
            }
            // recheck license
            try {
                $site_license = $CMSNT ? $CMSNT->site('license_key') : '';
                $checkKey = checkLicenseKey($site_license ?: '');
                if (!($checkKey['status'] ?? false)) {
                    die('<script type="text/javascript">alert("'.$checkKey['msg'].'");history.back();</script>');
                }
                die('<script type="text/javascript">alert("Lưu thành công !");history.back();</script>');
            } catch (Throwable $e) {
                error_log('is_license: post-save check error: '.$e->getMessage());
                die('<script type="text/javascript">alert("Lưu thất bại, vui lòng thử lại");history.back();</script>');
            }
        }

        // Show admin license form (safe rendering)
        ?>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Cấu hình thông tin bản quyền</h1>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">THÔNG TIN BẢN QUYỀN CODE</h3>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Mã bản quyền (license key)</label>
                                        <div class="col-sm-9">
                                            <div class="form-line">
                                                <input type="text" name="license_key" placeholder="Nhập mã bản quyền của bạn để sử dụng chức năng này"
                                                       value="<?=htmlspecialchars($site_license ?? '')?>" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" name="btnSaveLicense" class="btn btn-primary btn-block">
                                        <span>LƯU</span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">HƯỚNG DẪN</h3>
                            </div>
                            <div class="card-body">
                                <p>Quý khách có thể lấy License key tại đây: <a target="_blank" href="https://client.cmsnt.co/clientarea.php?action=products&module=licensing">CMSNT Client</a></p>
                                <p>Nếu chưa mua, liên hệ hỗ trợ của CMSNT để cấp key.</p>
                                <img src="https://i.imgur.com/VzDVIx0.png" width="100%">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php
        // include footer if available
        @include_once __DIR__ . "/../resources/views/admin/footer.php";
        // stop further execution so admin must activate license first
        exit;
    }
}
?>
