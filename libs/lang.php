<?php
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

// ====== Đặt ngôn ngữ ======
if (!function_exists('setLanguage')) {
    function setLanguage($id)
    {
        global $CMSNT;
        if (!$CMSNT) return false;

        try {
            $row = $CMSNT->get_row("SELECT * FROM `languages` WHERE `id` = '" . check_string($id) . "' AND `status` = 1");
            if ($row) {
                return setcookie('language', $row['lang'], time() + 31536000, "/"); // lưu 1 năm
            }
        } catch (Throwable $e) {
            error_log('setLanguage error: ' . $e->getMessage());
        }
        return false;
    }
}

// ====== Lấy ngôn ngữ hiện tại ======
if (!function_exists('getLanguage')) {
    function getLanguage()
    {
        global $CMSNT;
        if (!$CMSNT) return 'en';

        try {
            // Nếu đã chọn cookie
            if (!empty($_COOKIE['language'])) {
                $language = check_string($_COOKIE['language']);
                $rowLang = $CMSNT->get_row("SELECT * FROM `languages` WHERE `lang` = '$language' AND `status` = 1");
                if ($rowLang) {
                    return $rowLang['lang'];
                }
            }

            // Nếu chưa chọn, lấy mặc định
            $rowLang = $CMSNT->get_row("SELECT * FROM `languages` WHERE `lang_default` = 1");
            if ($rowLang) {
                return $rowLang['lang'];
            }
        } catch (Throwable $e) {
            error_log('getLanguage error: ' . $e->getMessage());
        }

        return 'en';
    }
}

// ====== Dịch chuỗi ======
if (!function_exists('__')) {
    function __($name)
    {
        global $CMSNT;
        if (!$CMSNT) return $name;

        try {
            $language = $_COOKIE['language'] ?? getLanguage();

            // Kiểm tra bản dịch trong ngôn ngữ hiện tại
            $rowLang = $CMSNT->get_row("SELECT * FROM `languages` WHERE `lang` = '" . check_string($language) . "' AND `status` = 1");
            if ($rowLang) {
                $rowTran = $CMSNT->get_row("SELECT * FROM `translate` WHERE `lang_id` = '" . $rowLang['id'] . "' AND `name` = '" . check_string($name) . "'");
                if (!empty($rowTran['value'])) {
                    return $rowTran['value'];
                }
            }

            // Nếu chưa có, fallback về ngôn ngữ mặc định
            $rowDefault = $CMSNT->get_row("SELECT * FROM `languages` WHERE `lang_default` = 1");
            if ($rowDefault) {
                $rowTran = $CMSNT->get_row("SELECT * FROM `translate` WHERE `lang_id` = '" . $rowDefault['id'] . "' AND `name` = '" . check_string($name) . "'");
                if (!empty($rowTran['value'])) {
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
