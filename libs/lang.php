<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}

/*
|--------------------------------------------------------------------------
| ⚙️ LANG HELPER — Dịch ngôn ngữ (CMSNT)
|---------------------------------------------------------------
| - Tự động lấy ngôn ngữ mặc định hoặc cookie người dùng.
| - An toàn: có kiểm tra $CMSNT, tránh lỗi nếu DB chưa khởi tạo.
| - Không làm chậm site nếu bảng translate trống.
*/

// ====== Đặt ngôn ngữ ======
if (!function_exists('setLanguage')) {
    function setLanguage($id)
    {
        global $CMSNT;
        if (!isset($CMSNT) || !is_object($CMSNT)) {
            return false;
        }

        try {
            $id_safe = check_string($id);
            $row = $CMSNT->get_row("SELECT * FROM `languages` WHERE `id` = '{$id_safe}' AND `status` = 1 LIMIT 1");
            if ($row) {
                setcookie('language', $row['lang'], time() + 31536000, "/", "", false, true); // lưu 1 năm
                $_COOKIE['language'] = $row['lang'];
                return true;
            }
        } catch (Throwable $e) {
            error_log('[setLanguage] ' . $e->getMessage());
        }
        return false;
    }
}

// ====== Lấy ngôn ngữ hiện tại ======
if (!function_exists('getLanguage')) {
    function getLanguage()
    {
        global $CMSNT;
        if (!isset($CMSNT) || !is_object($CMSNT)) {
            return 'vi'; // fallback an toàn
        }

        try {
            // Nếu có cookie đã lưu
            if (!empty($_COOKIE['language'])) {
                $lang = check_string($_COOKIE['language']);
                $rowLang = $CMSNT->get_row("SELECT * FROM `languages` WHERE `lang` = '{$lang}' AND `status` = 1 LIMIT 1");
                if ($rowLang) {
                    return $rowLang['lang'];
                }
            }

            // Nếu chưa chọn, lấy ngôn ngữ mặc định
            $rowLang = $CMSNT->get_row("SELECT * FROM `languages` WHERE `lang_default` = 1 LIMIT 1");
            if ($rowLang) {
                return $rowLang['lang'];
            }
        } catch (Throwable $e) {
            error_log('[getLanguage] ' . $e->getMessage());
        }

        return 'vi';
    }
}

// ====== Dịch chuỗi ======
if (!function_exists('__')) {
    function __($text)
    {
        global $CMSNT;

        // Nếu DB chưa sẵn, trả nguyên văn
        if (!isset($CMSNT) || !is_object($CMSNT)) {
            return $text;
        }

        try {
            $language = $_COOKIE['language'] ?? getLanguage();
            $lang_safe = check_string($language);
            $name_safe = check_string($text);

            // 🔍 Dịch theo ngôn ngữ hiện tại
            $rowLang = $CMSNT->get_row("SELECT * FROM `languages` WHERE `lang` = '{$lang_safe}' AND `status` = 1 LIMIT 1");
            if ($rowLang) {
                $rowTran = $CMSNT->get_row("SELECT * FROM `translate` WHERE `lang_id` = '{$rowLang['id']}' AND `name` = '{$name_safe}' LIMIT 1");
                if (!empty($rowTran['value'])) {
                    return $rowTran['value'];
                }
            }

            // 🔁 Fallback sang ngôn ngữ mặc định
            $rowDefault = $CMSNT->get_row("SELECT * FROM `languages` WHERE `lang_default` = 1 LIMIT 1");
            if ($rowDefault) {
                $rowTran = $CMSNT->get_row("SELECT * FROM `translate` WHERE `lang_id` = '{$rowDefault['id']}' AND `name` = '{$name_safe}' LIMIT 1");
                if (!empty($rowTran['value'])) {
                    return $rowTran['value'];
                }
            }

        } catch (Throwable $e) {
            error_log('[__()] ' . $e->getMessage());
        }

        return $text; // nếu không có bản dịch
    }
}
?>
