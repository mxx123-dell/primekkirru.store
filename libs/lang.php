<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

// chọn ngôn ngữ
function setLanguage($id)
{
    global $CMSNT;
    if (!$CMSNT) return false;

    try {
        $row = $CMSNT->get_row("SELECT * FROM languages WHERE id = '".check_string($id)."' AND status = 1");
        if ($row) {
            $isSet = setcookie('language', $row['lang'], time() + 31536000, "/"); // 1 năm
            return $isSet;
        }
    } catch (Throwable $e) {
        error_log('setLanguage error: ' . $e->getMessage());
    }
    return false;
}

// lấy ngôn ngữ mặc định
function getLanguage()
{
    global $CMSNT;
    if (!$CMSNT) return 'en';

    try {
        if (isset($_COOKIE['language'])) {
            $language = check_string($_COOKIE['language']);
            $rowLang = $CMSNT->get_row("SELECT * FROM languages WHERE lang = '$language' AND status = 1");
            if ($rowLang) {
                return $rowLang['lang'];
            }
        }

        $rowLang = $CMSNT->get_row("SELECT * FROM languages WHERE lang_default = 1");
        if ($rowLang) {
            return $rowLang['lang'];
        }
    } catch (Throwable $e) {
        error_log('getLanguage error: ' . $e->getMessage());
    }

    return 'en';
}

// hiển thị ngôn ngữ
if (!function_exists('__')) {
    function __($name)
    {
        global $CMSNT;
        if (!$CMSNT) return $name;

        try {
            if (isset($_COOKIE['language'])) {
                $language = check_string($_COOKIE['language']);
                $rowLang = $CMSNT->get_row("SELECT * FROM languages WHERE lang = '$language' AND status = 1");
                if ($rowLang) {
                    $rowTran = $CMSNT->get_row("SELECT * FROM translate WHERE lang_id = '".$rowLang['id']."' AND name = '".check_string($name)."'");
                    if ($rowTran && !empty($rowTran['value'])) {
                        return $rowTran['value'];
                    }
                }
            }

            $rowLang = $CMSNT->get_row("SELECT * FROM languages WHERE lang_default = 1");
            if ($rowLang) {
                $rowTran = $CMSNT->get_row("SELECT * FROM translate WHERE lang_id = '".$rowLang['id']."' AND name = '".check_string($name)."'");
                if ($rowTran && !empty($rowTran['value'])) {
                    return $rowTran['value'];
                }
            }
        } catch (Throwable $e) {
            error_log('__() error: ' . $e->getMessage());
        }

        return $name;
    }
}
?>
