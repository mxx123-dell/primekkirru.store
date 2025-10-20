-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th8 09, 2024 lúc 10:43 AM
-- Phiên bản máy phục vụ: 10.3.39-MariaDB-cll-lve
-- Phiên bản PHP: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `mduykhanhdev_shop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `trans_id` varchar(255) DEFAULT NULL,
  `api_trans_id` varchar(255) DEFAULT NULL,
  `seller` varchar(255) DEFAULT NULL,
  `buyer` varchar(255) DEFAULT NULL,
  `account` text DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) DEFAULT 'LIVE',
  `time_live` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `addons`
--

CREATE TABLE `addons` (
  `id` int(11) NOT NULL,
  `name` mediumtext DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `image` mediumtext DEFAULT NULL,
  `createdate` datetime NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `purchase_key` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `addons`
--

INSERT INTO `addons` (`id`, `name`, `description`, `image`, `createdate`, `price`, `purchase_key`) VALUES
(2, 'Tạo giao dịch ảo', 'Tự động tạo giao dịch nạp tiền, mua sản phẩm ảo để tăng uy tín cho shop', 'https://i.imgur.com/6kNRjfN.png', '2022-04-07 20:52:41', 500000, '18865eaec779d151571cca867ca9c9af'),
(4, 'Bảng Xếp Hạng Nạp Tiền', 'Bảng xếp hàng nạp tiền cho thành viên', 'https://i.imgur.com/ZRIRAaB.png', '2022-04-12 02:42:41', 200000, 'f685539599b41eef779f8565e4aff6e6'),
(24, 'Nạp Tiền Server 2', 'Nạp tiền bằng nội dung + id', 'https://i.imgur.com/CydpsWl.png', '2022-04-19 01:40:11', 500000, 'b8d3aca8b4fb19893b6ed5a5b13c03ce'),
(211, 'Số Lượng Đã Bán Ảo', 'Điều chỉnh số lượng đã bán ảo (số lượng ảo + số lượng thật)', 'https://i.imgur.com/3tOOFDC.png', '2022-04-26 01:40:11', 50000, '4e10239bc26c263250ea2449ef7a0a17'),
(11412, 'API 1', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2022-07-26 00:00:00', 1000000, '4475e15d802fe69ca93ac53d13a760a0'),
(11413, 'API 4', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2022-07-26 00:00:00', 1000000, '962e7443d3619f992c61e006bc0a3961'),
(11422, 'API 5', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2022-09-14 00:00:00', 1000000, '8957820e8dc399bb582ed3cc66224caf'),
(11427, 'API 6', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2022-07-26 00:00:00', 1000000, 'dfb36038d385dbdbda808b030b80d83d'),
(11469, 'Email Campaigns', 'Gửi Email đến toàn bộ khách hàng của bạn', 'https://i.imgur.com/iQWAKTY.jpg', '2022-11-02 00:00:00', 300000, 'd26a46c72526b3ac970d115b22193056'),
(11487, 'API 7', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2022-11-03 00:00:00', 1000000, '55a6096fdb7b8a345393fe6f9d03c2b8'),
(11521, 'Bán Like, Follow MXH', 'Tích hợp bán like, follow mạng xã hội vào mã nguồn SHOPCLONE6', 'https://i.imgur.com/gS5RRnm.png', '2022-11-11 00:00:00', 1000000, 'fc206e86e0e3c3eb839d8b6cdb38fed0'),
(11522, 'Bán xu TDS - TTC', 'Chức năng bán xu TDS và TTC tự động', 'https://i.imgur.com/1RHdBdT.png', '2023-09-09 00:00:00', 3000000, '2ab92ebd7193ec9698803dd1a82028ba'),
(11535, 'API 8', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2022-11-11 00:00:00', 1000000, '845c6e249515b4dd92745cedc7c84211'),
(11542, 'API 9', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2022-11-23 00:00:00', 1000000, '915f9c6e82a11d5b6cab14cc7e110793'),
(11621, 'Tích hợp thuê OTP qua API 1', 'Chức năng thuê SIM tích hợp qua API 1', 'https://i.imgur.com/wQlNAcH.png', '2022-12-13 00:00:00', 1000000, '50583e8ad570d89efdd39942849ddff3'),
(11634, 'Tích hợp thuê OTP qua API 2', 'Chức năng thuê SIM tích hợp qua API 2', 'https://i.imgur.com/wQlNAcH.png', '2022-12-28 00:00:00', 2000000, 'e9c09aff42711a13b1b5a72b1fa045f1'),
(11635, 'API 10', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2022-12-19 00:00:00', 1000000, 'b08c345a8786a1b8ed54b28994a9a785'),
(11645, 'API 11', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2023-01-02 00:00:00', 1000000, '8624ce32fbf5719a9a134c7a5e46329f'),
(11656, 'Tích hợp thuê OTP qua API 3', 'Chức năng thuê SIM tích hợp qua API 3', 'https://i.imgur.com/wQlNAcH.png', '2023-01-10 00:00:00', 1000000, '33a38290ba9067d9f4394134f752a3d2'),
(11657, 'API 12', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2023-02-06 00:00:00', 1000000, '6b57852ea835628e78e1d1c00bcd24f0'),
(11678, 'Tích hợp thuê OTP qua API 4', 'Chức năng thuê SIM tích hợp qua API 4', 'https://i.imgur.com/wQlNAcH.png', '2023-02-06 00:00:00', 1000000, '55afdc9090b28e91b43ad1e10e576941'),
(11698, 'Tích hợp thuê OTP qua API 5', 'Chức năng thuê SIM tích hợp qua API 5', 'https://i.imgur.com/wQlNAcH.png', '2023-05-09 00:00:00', 1000000, '66cf0dc47530cd101eebf24c821a697c'),
(11724, 'API 13', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2023-02-08 00:00:00', 1000000, 'f9375d392dba45bdca15a3551b832a58'),
(11735, 'API 14', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2023-05-10 00:00:00', 1000000, 'c478d4a965f76605ad9b240dc79161f5'),
(11872, 'API 15', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2023-06-28 00:00:00', 1000000, 'c9051805c5ca781b52978558a444e958'),
(11898, 'API 16', 'Kết nối API sản phẩm website không dùng API của CMSNT', 'https://i.imgur.com/EFq5tTX.png', '2023-10-01 00:00:00', 1000000, '75144ec6b82612c344098f77b77213b8'),
(14232, 'Bán Fanpage/Group', 'Addon bán Fanpage/Group thủ công', 'https://i.imgur.com/jmIjBfI.png', '2022-05-07 01:59:00', 1000000, '783eeb47f58f19c04fca18cd64dfc521'),
(112246, 'BOT Telegram', 'Addon thông báo về Telegram', 'https://i.imgur.com/9Ci2geb.png', '2022-06-26 16:00:00', 300000, 'a3548218c22256a89503a67e5b0687fb');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `accountName` text DEFAULT NULL,
  `accountNumber` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banned_ips`
--

CREATE TABLE `banned_ips` (
  `id` int(11) NOT NULL,
  `ip` varchar(55) DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL,
  `banned` int(11) NOT NULL DEFAULT 0,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` mediumtext DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `display` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `view` int(11) NOT NULL DEFAULT 0,
  `create_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `trans_id` varchar(255) DEFAULT NULL,
  `telco` varchar(255) DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `price` int(11) NOT NULL DEFAULT 0,
  `serial` text DEFAULT NULL,
  `pin` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `stt` int(1) NOT NULL DEFAULT 0,
  `id_api` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `id_connect_api` int(11) NOT NULL DEFAULT 0,
  `name` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category_service`
--

CREATE TABLE `category_service` (
  `id` int(11) NOT NULL,
  `name` blob DEFAULT NULL,
  `display` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `connect_api`
--

CREATE TABLE `connect_api` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `type` varchar(255) DEFAULT 'CMSNT',
  `domain` varchar(255) DEFAULT NULL,
  `username` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `token` text DEFAULT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `auto_rename_api` int(11) NOT NULL DEFAULT 1,
  `ck_connect_api` float NOT NULL DEFAULT 0,
  `status_update_ck` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `used` int(11) NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `createdate` datetime NOT NULL,
  `updatedate` datetime NOT NULL,
  `min` int(11) NOT NULL DEFAULT 1000,
  `max` int(11) NOT NULL DEFAULT 10000000
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupon_used`
--

CREATE TABLE `coupon_used` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `trans_id` varchar(255) DEFAULT NULL,
  `createdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `crypto_invoice`
--

CREATE TABLE `crypto_invoice` (
  `id` int(11) NOT NULL,
  `trans_id` text DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `request_id` text DEFAULT NULL,
  `amount` decimal(18,6) NOT NULL DEFAULT 0.000000,
  `create_gettime` datetime NOT NULL,
  `update_gettime` datetime NOT NULL,
  `status` varchar(55) DEFAULT NULL,
  `msg` text DEFAULT NULL,
  `url_payment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `rate` float NOT NULL DEFAULT 0,
  `symbol_left` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `symbol_right` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `seperator` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `display` int(11) NOT NULL DEFAULT 1,
  `default_currency` int(11) NOT NULL DEFAULT 0,
  `decimal_currency` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `rate`, `symbol_left`, `symbol_right`, `seperator`, `display`, `default_currency`, `decimal_currency`) VALUES
(1, 'Đồng', 'VND', 1, '', 'đ', 'dot', 1, 1, 0),
(2, 'Dollar', 'USD', 22500, '$', '', 'dot', 1, 0, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dichvugame`
--

CREATE TABLE `dichvugame` (
  `id` int(11) NOT NULL,
  `stt` int(1) NOT NULL DEFAULT 0,
  `name` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `luuy` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dichvu_groups`
--

CREATE TABLE `dichvu_groups` (
  `id` int(11) NOT NULL,
  `stt` int(11) NOT NULL DEFAULT 0,
  `dichvugame_id` int(11) NOT NULL DEFAULT 0,
  `name` text DEFAULT NULL,
  `price` float NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dichvu_history`
--

CREATE TABLE `dichvu_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `receiver` varchar(255) DEFAULT NULL,
  `dichvu` varchar(255) DEFAULT NULL,
  `money` int(11) DEFAULT NULL,
  `taikhoan` varchar(255) DEFAULT NULL,
  `matkhau` varchar(255) DEFAULT NULL,
  `createdate` datetime NOT NULL,
  `updatedate` datetime NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `ghichu` text DEFAULT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL,
  `update_gettime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `stt` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `image` mediumtext DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `price` float NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `document_categories`
--

CREATE TABLE `document_categories` (
  `id` int(11) NOT NULL,
  `stt` int(11) NOT NULL DEFAULT 0,
  `name` mediumtext DEFAULT NULL,
  `image` mediumtext DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `domains`
--

CREATE TABLE `domains` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `domain` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `admin_note` text DEFAULT NULL,
  `create_gettime` datetime NOT NULL,
  `update_gettime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dongtien`
--

CREATE TABLE `dongtien` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `sotientruoc` int(11) NOT NULL DEFAULT 0,
  `sotienthaydoi` int(11) NOT NULL DEFAULT 0,
  `sotiensau` int(11) NOT NULL DEFAULT 0,
  `thoigian` datetime NOT NULL,
  `noidung` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `email_campaigns`
--

CREATE TABLE `email_campaigns` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `cc` text DEFAULT NULL,
  `bcc` text DEFAULT NULL,
  `content` longblob DEFAULT NULL,
  `create_gettime` datetime NOT NULL,
  `update_gettime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `email_sending`
--

CREATE TABLE `email_sending` (
  `id` int(11) NOT NULL,
  `camp_id` int(11) DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL,
  `update_gettime` datetime NOT NULL,
  `response` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giftbox`
--

CREATE TABLE `giftbox` (
  `id` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `trans_id` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `pay` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fake` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ip_white`
--

CREATE TABLE `ip_white` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `lang_default` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `languages`
--

INSERT INTO `languages` (`id`, `lang`, `icon`, `lang_default`, `status`) VALUES
(8, 'Vietnamese', 'assets/storage/flags/flag_Vietnamese.png', 1, 1),
(16, 'English', 'assets/storage/flags/flag_English.png', 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `list_tds_ttc`
--

CREATE TABLE `list_tds_ttc` (
  `id` int(11) NOT NULL,
  `server` varchar(55) DEFAULT NULL COMMENT 'TTC or TDS',
  `username` text DEFAULT NULL COMMENT 'Username nếu có',
  `password` text DEFAULT NULL COMMENT 'Password nếu có',
  `token` text DEFAULT NULL COMMENT 'Token nếu có',
  `cookie` text DEFAULT NULL COMMENT 'Cookie nếu có',
  `coin` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `create_gettime` datetime NOT NULL,
  `update_gettime` datetime NOT NULL,
  `day_limit` int(11) NOT NULL DEFAULT 0,
  `proxy_host` text DEFAULT NULL,
  `proxy_user` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(255) DEFAULT NULL,
  `device` varchar(255) DEFAULT NULL,
  `createdate` datetime NOT NULL,
  `action` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `ip`, `device`, `createdate`, `action`) VALUES
(1, 2, '2001:ee0:4274:b010:b824:cfa7:f8f7:2f68', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', '2024-08-09 16:23:15', 'Thực hiện tạo tài khoản'),
(2, 2, '2001:ee0:4274:b010:b824:cfa7:f8f7:2f68', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', '2024-08-09 17:26:49', 'Đăng nhập thành công vào hệ thống'),
(3, 3, '2001:ee0:4274:b010:b824:cfa7:f8f7:2f68', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', '2024-08-09 17:40:23', 'Thực hiện tạo tài khoản');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `log_ref`
--

CREATE TABLE `log_ref` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `reason` text DEFAULT NULL,
  `sotientruoc` float NOT NULL DEFAULT 0,
  `sotienthaydoi` float NOT NULL DEFAULT 0,
  `sotienhientai` float NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `log_tds_ttc`
--

CREATE TABLE `log_tds_ttc` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `action` text DEFAULT NULL,
  `create_gettime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `href` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `target` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT 3,
  `content` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `title` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `createdate` datetime NOT NULL,
  `timeago` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nowpayments`
--

CREATE TABLE `nowpayments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `payment_id` varchar(50) DEFAULT NULL,
  `invoice_id` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `pay_address` varchar(255) DEFAULT NULL,
  `price_amount` float NOT NULL DEFAULT 0,
  `price` float NOT NULL DEFAULT 0,
  `price_currency` varchar(255) DEFAULT NULL,
  `pay_amount` float NOT NULL DEFAULT 0,
  `actually_paid` float NOT NULL DEFAULT 0,
  `pay_currency` varchar(255) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `order_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchase_id` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `outcome_amount` float NOT NULL DEFAULT 0,
  `outcome_currency` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `name` blob DEFAULT NULL,
  `api_trans_id` varchar(255) DEFAULT NULL,
  `id_connect_api` int(11) NOT NULL DEFAULT 0,
  `seller` int(11) NOT NULL DEFAULT 0,
  `buyer` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `document_id` int(11) NOT NULL DEFAULT 0,
  `store_fanpage_id` int(11) NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL DEFAULT 0,
  `pay` int(11) NOT NULL DEFAULT 0,
  `cost` float NOT NULL DEFAULT 0,
  `create_date` datetime NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0,
  `display` int(11) NOT NULL DEFAULT 1,
  `fake` int(11) NOT NULL DEFAULT 0,
  `refund` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_autofb`
--

CREATE TABLE `order_autofb` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `trans_id` varchar(255) DEFAULT NULL,
  `id_rate_autofb` int(11) NOT NULL DEFAULT 0,
  `insertId` int(11) DEFAULT 0,
  `payment` int(11) NOT NULL DEFAULT 0,
  `payment_api` int(11) NOT NULL DEFAULT 0,
  `uid` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `subscribers` int(11) NOT NULL DEFAULT 0,
  `count_success` int(11) NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `server` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `update_gettime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_service`
--

CREATE TABLE `order_service` (
  `id` int(11) NOT NULL,
  `buyer` int(11) NOT NULL DEFAULT 0,
  `id_api` varchar(50) DEFAULT NULL,
  `server` text DEFAULT NULL,
  `service_id` int(11) NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL DEFAULT 0,
  `remains` int(11) NOT NULL DEFAULT 0,
  `price` float NOT NULL DEFAULT 0,
  `url` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `trans_id` varchar(50) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `task_note` text DEFAULT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `update_gettime` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `refund` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_tds_ttc`
--

CREATE TABLE `order_tds_ttc` (
  `id` int(11) NOT NULL,
  `trans_id` varchar(55) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `server` text DEFAULT NULL,
  `user_nhan` text DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `remaining` int(11) NOT NULL DEFAULT 0,
  `money` float NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL,
  `update_gettime` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 Đang chạy - 1 Hoàn tất - 2 Hủy',
  `note` text DEFAULT NULL COMMENT 'Ghi chú từ Admin'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `otp_history`
--

CREATE TABLE `otp_history` (
  `id` int(11) NOT NULL,
  `transid` text DEFAULT NULL,
  `id_service_otp` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `number` text DEFAULT NULL,
  `id_order_api` text DEFAULT NULL,
  `app` text DEFAULT NULL,
  `price` float NOT NULL DEFAULT 0,
  `cost` float NOT NULL DEFAULT 0,
  `code` text DEFAULT NULL,
  `sms` text DEFAULT NULL,
  `create_gettime` datetime NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_flutterwave`
--

CREATE TABLE `payment_flutterwave` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `tx_ref` varchar(55) DEFAULT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `currency` text DEFAULT NULL,
  `create_gettime` datetime NOT NULL,
  `update_gettime` datetime NOT NULL,
  `status` varchar(55) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_paypal`
--

CREATE TABLE `payment_paypal` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `price` int(11) NOT NULL DEFAULT 0,
  `create_date` datetime NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_pm`
--

CREATE TABLE `payment_pm` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `payment_id` varchar(255) DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `price` int(11) NOT NULL DEFAULT 0,
  `create_date` datetime NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_date` datetime NOT NULL,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_squadco`
--

CREATE TABLE `payment_squadco` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `transaction_ref` varchar(55) DEFAULT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `paypal`
--

CREATE TABLE `paypal` (
  `id` int(11) NOT NULL,
  `item_number` varchar(50) NOT NULL,
  `txn_id` varchar(50) NOT NULL,
  `payment_gross` float(10,2) NOT NULL,
  `currency_code` varchar(5) NOT NULL,
  `payment_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `stt` int(11) NOT NULL DEFAULT 0,
  `user_id` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `name` text DEFAULT NULL,
  `flag` text DEFAULT NULL,
  `price` float NOT NULL DEFAULT 0,
  `cost` float NOT NULL DEFAULT 0,
  `checklive` int(11) NOT NULL DEFAULT 0,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `preview` text DEFAULT NULL,
  `time_delete_account` int(11) NOT NULL DEFAULT 0,
  `minimum` int(11) NOT NULL DEFAULT 1,
  `maximum` int(11) NOT NULL DEFAULT 10000,
  `id_api` varchar(50) NOT NULL DEFAULT '0',
  `id_connect_api` int(11) NOT NULL DEFAULT 0,
  `api_stock` float NOT NULL DEFAULT 0,
  `name_api` blob DEFAULT NULL,
  `update_api` int(11) NOT NULL DEFAULT 0,
  `sold` int(11) NOT NULL DEFAULT 0,
  `filter_time_checklive` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` longtext DEFAULT NULL,
  `answer` longtext DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rate_autofb`
--

CREATE TABLE `rate_autofb` (
  `id` int(11) NOT NULL,
  `type_api` varchar(255) DEFAULT NULL,
  `name_api` varchar(255) DEFAULT NULL,
  `loaiseeding` varchar(255) DEFAULT NULL,
  `price` float NOT NULL DEFAULT 0,
  `name_loaiseeding` varchar(255) DEFAULT NULL,
  `note` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `rate_autofb`
--

INSERT INTO `rate_autofb` (`id`, `type_api`, `name_api`, `loaiseeding`, `price`, `name_loaiseeding`, `note`) VALUES
(1, 'buffsub_sale', 'Facebook buff sub sale (sv1)', '1', 20, 'Tăng Sub sale - SV1 (tốc độ ổn định)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(2, 'buffsub_sale', 'Facebook buff sub sale (sv2)', '2', 30, 'Tăng Sub sale - SV2 (tốc độ ổn định)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(3, 'buffsub_sale', 'Facebook buff sub sale (sv3)', '3', 10, 'Tăng Sub sale - SV3 (done trong ngày)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(4, 'buffsub_sale', 'Facebook buff sub sale (sv4)', '4', 35, 'Tăng Sub sale - SV4 (tốc độ nhanh)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(7, 'buffsub_speed', 'Facebook buff sub speed (sv1)', '1', 50, 'Tăng Sub - SV1 (MAX 50K, bấm tay)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(8, 'buffsub_speed', 'Facebook buff sub speed (sv2)', '2', 50, 'Tăng Sub - SV2 (Lên khá nhanh, Max 1000k, Bảo hành 1 tháng) tốt nhất nên dùng', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(9, 'buffsub_speed', 'Facebook buff sub speed (sv3)', '3', 30, 'Tăng Sub - SV3 (Chạy được cho page pro5, 1k sub / ngày, max 250k sub clone, Không BH)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(10, 'buffsub_speed', 'Facebook buff sub speed (sv4)', '4', 30, 'Tăng Sub - SV4 (Lên ổn định, Max 80k, Bảo hành 1 tháng)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(11, 'buffsub_speed', 'Facebook buff sub speed (sv5)', '5', 40, 'Tăng Sub - SV5 (Lên nhanh, Max 50k Lên cực nhanh)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(12, 'buffsub_speed', 'Facebook buff sub speed (sv6)', '6', 30, 'Tăng Sub - SV6 (Max 15k Hoàn thành trong 1-24H)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(13, 'buffsub_speed', 'Facebook buff sub speed (sv7)', '7', 30, 'Tăng Sub - SV7 (Max 20k Lên cực nhanh)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(14, 'buffsub_speed', 'Facebook buff sub speed (sv8)', '8', 60, 'Tăng Sub - SV8 (Lên rất nhanh, Max 600k, Bảo hành 6 tháng)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(15, 'buffsub_speed', 'Facebook buff sub speed (sv9)', '9', 20, 'Tăng Sub - SV9 (Max 1000k , bảo hành 15 ngày)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(16, 'buffsub_slow', 'Facebook buff sub chậm (basic)', '1', 40, 'Tăng Sub đề xuất - Basic (BH 3 tháng, max 400k) (sub thẳng hoặc kết bạn)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(17, 'buffsub_slow', 'Facebook buff sub chậm (v1)', '3', 30, 'Tăng Sub đề xuất - V1 (BH 1 tháng, max 200k) (sub thẳng hoặc kết bạn)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(18, 'buffsub_slow', 'Facebook buff sub v2 (sv2)', '2', 60, 'Tăng Sub đề xuất - V3 (xịn nhất, nick đang hoạt động 96,69%, max 60k) (sub kết bạn, ẩn kết bạn hoặc qua thẳng)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(19, 'buffsub_slow', 'Facebook buff sub chậm (v2)', '4', 30, 'Tăng Sub đề xuất - V2 (Chất lượng cao, ổn định, nên dùng max 200k) (sub thẳng hoặc kết bạn)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(20, 'bufflikefanpagesale', '', '1', 20, 'Tăng Like Fanpage - SV1 (Lên chậm khoảng 1-2k/ngày Không BH)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(21, 'bufflikefanpagesale', NULL, '2', 28, 'Tăng Like Fanpage - SV2 (Lên ổn định khoảng 5-10k/ngày Không BH)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(22, 'bufflikefanpagesale', NULL, '3', 31, 'Tăng Like Fanpage - SV3 (tốc độ rất nhanh (1 ngày mua max 20k, chạy done mai mua tiếp)) (BH 7 ngày )', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(23, 'bufflikefanpage', NULL, '1', 57, 'Tăng Like Fanpage - BASIC (like bấm tay, ít tụt, có 1 vài page ko thể tăng like) (Nên dùng)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(24, 'bufflikefanpage', NULL, '2', 28, 'Tăng Like Fanpage - PRO (Like Via, max 200k tốc độ chậm Không bảo hành', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(25, 'bufflikefanpage', NULL, '3', 47, 'Tăng Like Fanpage - SV3 (MAX 40k BH 3 tháng) (Like lên chậm)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(26, 'bufflikefanpage', NULL, '4', 46, 'Tăng Like Fanpage - SV4 (MAX 20k BH 1 tháng) (Like lên chậm)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(27, 'bufflikefanpage', NULL, '5', 60, 'Tăng Like Fanpage - SV5 (MAX 50K BH 1 tháng) (Like chất lượng tốt) (Like lên nhanh)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(28, 'bufflikefanpage', NULL, '6', 67, 'Tăng Like Fanpage - SV6 (Like page Global (Like Tây Lên Nhanh ít tụt, nên sử dụng Bảo hành 45 ngày))', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(29, 'bufflikefanpage', NULL, '7', 25, 'Tăng Like Fanpage - SV7 (Like Việt Lên nhanh (max 100k))(BH 60 ngày)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(30, 'bufflikefanpage', NULL, '8', 22, 'Tăng Like Fanpage - SV8 (Like việt, rẻ, nhanh (max 100k)) (BH 30 ngày)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(32, 'buffsubfanpage', NULL, '2', 42, 'Tăng Sub Fanpage - SV2 (page pro5 chạy sau 12-24h)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(33, 'buffsubfanpage', NULL, '1', 27, 'Tăng Sub Fanpage - SV1 (page thường, hoàn thành các đơn < 30k trong 24h)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(34, 'bufflikecommentsharelike', NULL, 'like', 58, 'Tăng Like Bài Viết (Like người việt thật) (max 50k)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(35, 'bufflikecommentsharelike', NULL, 'like_v2', 10, 'Tăng Like Bài Viết V2 (Like việt clone, tốc độ chậm, có tụt like) (max 250k)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(36, 'bufflikecommentsharelike', NULL, 'like_v3', 20, 'Tăng Like Bài Viết V3 (Like việt, rẻ, nhanh !) (max 50k)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(37, 'bufflikecommentsharelike', NULL, 'like_v4', 24, 'Tăng Like Bài Viết V4 (Like Việt Lên nhanh (max 80k) có tụt like)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(38, 'bufflikecommentsharelike', NULL, 'like_v5', 27, 'Tăng Like Bài Viết V5 (Like Việt Lên nhanh(1 ngày chạy 15-25k like) có tụt like)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(39, 'bufflikecommentsharelike', NULL, 'like_v6', 16, 'Tăng Like Bài Viết V6 (Like clone nhanh)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(40, 'bufflikecommentsharelike', NULL, 'like_v7', 34, 'Tăng Like Bài Viết V7 (Like Việt Lên Max Nhanh,luôn oder được)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(41, 'bufflikecommentshareshare', NULL, 'share', 1000, 'Tăng Share Bài Viết SV1 (share người thật,share việt)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(42, 'bufflikecommentshareshare', NULL, 'share_sv2', 250, 'Tăng Share Bài Viết SV2 (share giá rẻ (không chạy đối với bài viết share bài viết khác))', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(43, 'bufflikecommentshareshare', NULL, 'share_sv3', 30, 'Tăng Share Bài Viết SV3 (share ảo (không chạy đối với bài viết share bài viết khác))', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(44, 'bufflikecommentshareshare', NULL, 'share_sv4', 410, 'Tăng Share Bài Viết SV4', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(45, 'bufflikecommentshareshare', NULL, 'share_sv5', 24, 'Tăng Share Bài Viết SV5 (share ảo rẻ)', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.'),
(46, 'buffviewstory', NULL, '1', 18, 'Tăng View Story SV1', '- Nghiêm cấm Buff các ID Seeding có nội dung vi phạm pháp luật, chính trị, đồ trụy...<br>\r\n- Nếu cố tình buff bạn sẽ bị trừ hết tiền và ban khỏi hệ thống vĩnh viễn, và phải chịu hoàn toàn trách nhiệm trước pháp luật.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` varchar(200) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `rating` int(1) NOT NULL,
  `review` text NOT NULL,
  `datetime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `send_email`
--

CREATE TABLE `send_email` (
  `id` int(11) NOT NULL,
  `template` varchar(255) DEFAULT NULL,
  `receiver` varchar(255) DEFAULT NULL,
  `name` mediumtext DEFAULT NULL,
  `title` mediumtext DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `bcc` mediumtext DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `response` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `server2_autobank`
--

CREATE TABLE `server2_autobank` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `tid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `received` float NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `name` blob DEFAULT NULL,
  `price` float NOT NULL DEFAULT 0,
  `cost` float NOT NULL DEFAULT 0,
  `content` longtext DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `id_api` varchar(50) DEFAULT '0',
  `type` varchar(50) DEFAULT NULL,
  `min` int(11) NOT NULL DEFAULT 0,
  `max` int(11) NOT NULL DEFAULT 0,
  `dripfeed` varchar(50) DEFAULT NULL,
  `refill` varchar(50) DEFAULT NULL,
  `cancel` varchar(50) DEFAULT NULL,
  `note` blob DEFAULT NULL,
  `source_api` varchar(255) DEFAULT '5gsmm.com',
  `update_time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `service_order`
--

CREATE TABLE `service_order` (
  `id` int(11) NOT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `seller` int(11) NOT NULL DEFAULT 0,
  `buyer` int(11) NOT NULL DEFAULT 0,
  `service_id` int(11) NOT NULL DEFAULT 0,
  `url` text DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `pay` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 xử lý, 1 hoàn tất, 2 huỷ',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `service_otp`
--

CREATE TABLE `service_otp` (
  `id` int(11) NOT NULL,
  `server` text DEFAULT NULL,
  `id_api` text DEFAULT NULL,
  `name_api` text DEFAULT NULL,
  `name` text DEFAULT NULL,
  `price_api` float NOT NULL DEFAULT 0,
  `price` float NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `update_time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'logo', 'assets/img/logo_light.png'),
(2, 'title', ''),
(3, 'thongbao', ''),
(4, 'logo_light', 'assets/storage/images/logo_light_VEU.png'),
(5, 'logo_dark', 'assets/storage/images/logo_dark_MUI.png'),
(6, 'image', 'assets/storage/images/image_X3P.png'),
(7, 'favicon', 'assets/storage/images/favicon_UKN.png'),
(8, 'description', ''),
(9, 'keywords', ''),
(10, 'author', ''),
(11, 'status', '1'),
(12, 'status_bank', '0'),
(13, 'type_bank', ''),
(14, 'stk_bank', ''),
(15, 'name_bank', ''),
(16, 'mk_bank', ''),
(17, 'status_momo', '0'),
(18, 'token_momo', ''),
(19, 'sdt_momo', ''),
(20, 'name_momo', ''),
(21, 'timeUpdate', ''),
(27, 'token_bank', ''),
(28, 'javascript', ''),
(31, 'email_smtp', ''),
(32, 'pass_email_smtp', ''),
(34, 'bg_login', 'assets/storage/images/bg_loginMXI.png'),
(35, 'bg_register', 'assets/storage/images/bg_registerHJE.png'),
(36, 'time_cron_24h', '0'),
(37, 'status_demo', '0'),
(39, 'license_key', 'LeHaiLong'),
(40, 'email', ''),
(41, 'hotline', ''),
(42, 'qr_momo', ''),
(43, 'recharge_notice', ''),
(44, 'contact_page', ''),
(45, 'gif_loading', ''),
(46, 'check_time_cron', '1691856070'),
(47, 'check_time_cron_bank', '1691856067'),
(48, 'min_rating', '1000'),
(49, 'orders_notice', ''),
(50, 'type_showProduct', 'BOX4'),
(51, 'clientId_paypal', ''),
(52, 'status_paypal', '0'),
(53, 'status_thesieure', '0'),
(54, 'token_thesieure', ''),
(55, 'check_time_cron_thesieure', '1691856068'),
(56, 'api_napthe', ''),
(57, 'status_napthe', '0'),
(58, 'notice_napthe', ''),
(59, 'ck_napthe', '0'),
(60, 'chinh_sach_bao_mat', ''),
(61, 'dieu_khoan_su_dung', ''),
(62, 'status_update', '0'),
(63, 'status_captcha', '1'),
(64, 'session_login', '2592000'),
(65, 'gif_giftbox', ''),
(66, 'display_sold', '1'),
(67, 'status_zalopay', '0'),
(68, 'token_zalopay', ''),
(69, 'check_time_cron_zalopay', '1691856071'),
(70, 'type_password', 'md5'),
(71, 'theme_color', '#C21212'),
(72, 'min_recharge', '10000'),
(73, 'time_check_live', '300'),
(74, 'currency', 'VND'),
(75, 'usd_rate', '22500'),
(76, 'clientSecret_paypal', ''),
(77, 'rate_paypal', '22500'),
(78, 'paypal_notice', ''),
(79, 'gif_loader', 'assets/storage/images/gif_loaderSWL.png'),
(80, 'invoice_expiration', '86400'),
(81, 'mouse_click_effect', '0'),
(82, 'notice_spin', ''),
(83, 'status_spin', '0'),
(84, 'condition_spin', '1000000'),
(85, 'status_perfectmoney', '0'),
(86, 'perfectmoney_notice', ''),
(87, 'PAYEE_ACCOUNT_PM', ''),
(88, 'PAYMENT_UNITS_PM', 'USD'),
(89, 'perfectmoney_pass_pm', ''),
(90, 'rate_pm', '23000'),
(91, 'status_crypto', '0'),
(92, 'notice_crypto', ''),
(93, 'status_giao_dich_gan_day', '1'),
(94, 'check_time_cron_card', '0'),
(95, 'check_time_cron_checklivefb', '1687013096'),
(96, 'partner_id_card', ''),
(97, 'partner_key_card', ''),
(98, 'javascript_header', ''),
(99, 'sign_view_product', '0'),
(100, 'display_box_shop', '1'),
(101, 'type_notice_order', 'Telegram'),
(102, 'font_family', 'font-family: \'Roboto\', sans-serif;'),
(103, 'time_delete_orders', '0'),
(104, 'check_time_cron_cron', '1691856066'),
(105, 'display_show_product', '1'),
(106, 'display_rating', '1'),
(107, 'stt_giaodichao', '0'),
(108, 'theme_color2', '#440523'),
(109, 'stt_topnap', '0'),
(110, 'sv1_autobank', '0'),
(111, 'sv2_autobank', '0'),
(112, 'prefix_autobank', 'NAPTIEN '),
(113, 'status_buff_like_sub', '0'),
(114, 'token_autofb', ''),
(115, 'virtual_sold_quantity', '0'),
(116, 'domain_autofb', 'https://cmslike.com/'),
(117, 'status_store_fanpage', '0'),
(118, 'notice_store_fanpage', ''),
(119, 'status_security', '0'),
(120, 'status_active_member', '0'),
(121, 'type_notification', 'off'),
(122, 'token_telegram', ''),
(123, 'chat_id_telegram', ''),
(124, 'buy_notification', 'THÔNG BÁO MUA HÀNG\r\n• Tên miền: {domain}\r\n• Tên khách hàng: {username}\r\n• Tên sản phẩm: {product_name}\r\n• Phương thức: {method}\r\n• Số lượng: {amount}\r\n• Số tiền thanh toán: {price}\r\n• Mã đơn hàng: {trans_id}\r\n• Thời gian: {time}\r\n'),
(125, 'naptien_notification', 'THÔNG BÁO NẠP THẺ CÀO\r\n• Tên miền: {domain}\r\n• Tên khách hàng: {username}\r\n• Phương thức: {method}\r\n• Số tiền nạp: {amount}\r\n• Thực nhận: {price}\r\n• Thời gian: {time}\r\n'),
(126, 'register_notification', 'THÔNG BÁO TẠO TÀI KHOẢN\r\n• Tên miền: {domain}\r\n• Tên khách hàng: {username}\r\n• Địa chỉ email: {email}\r\n• Địa chỉ IP: {ip}\r\n• Thiết bị: {device}\r\n• Thời gian: {time}\r\n'),
(127, 'max_time_buy', '10'),
(128, 'time_delete_clone_die', '2592000'),
(129, 'check_time_cron1', '1691856072'),
(130, 'check_time_cron_dichvudark', '1675488626'),
(131, 'bg_card', 'assets/storage/images/bg_cardM5A.png'),
(132, 'display_blog', '0'),
(133, 'display_question', '0'),
(134, 'display_contact', '1'),
(135, 'display_api', '0'),
(136, 'display_tool', '0'),
(137, 'status_connect_api', '1'),
(138, 'check_time_cron2', '1691856072'),
(139, 'ck_connect_api', '0'),
(140, 'check_time_cron_mbbank', '1660721103'),
(141, 'status_ref', '1'),
(142, 'ck_ref', '5'),
(143, 'notice_ref', ''),
(144, 'listbank_ref', ''),
(145, 'minrut_ref', ''),
(146, 'display_preview', '0'),
(147, 'display_country', '0'),
(148, 'apikey_nowpayments', ''),
(149, 'status_nowpayments', '0'),
(150, 'status_is_change_password', '0'),
(151, 'auto_rename_api', '1'),
(152, 'ipn_nowpayments', ''),
(153, 'min_crypto', '10'),
(154, 'rate_crypto', '22500'),
(155, 'check_time_cron_crypto', '1660721107'),
(156, 'check_time_cron3', '1686547097'),
(157, 'menu_title', ''),
(158, 'check_time_cron_momo', '1659104220'),
(159, 'default_api_product_status', '1'),
(161, 'mk_momo', '260607'),
(162, 'phash_momo', ''),
(163, 'key_momo', ''),
(164, 'min_gd_ao', '1'),
(165, 'max_gd_ao', '5'),
(166, 'speed_buy_gd_ao', '5000'),
(167, 'amount_nap_ao', '10000\r\n20000\r\n30000\r\n40000\r\n50000\r\n60000\r\n70000\r\n80000\r\n90000\r\n100000'),
(168, 'speed_nap_gd_ao', '5000'),
(169, 'position_gd_gan_day', '2'),
(170, 'is_account_buy_fake', '1'),
(171, 'hide_product_empty', '1'),
(172, 'email_nowpayments', ''),
(173, 'password_nowpayments', ''),
(174, 'check_time_cron4', '1687174861'),
(175, 'home_page', 'home'),
(176, 'notice_popup', '<p style=\"text-align:center\">&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n'),
(177, 'check_time_cron_dongvanfb', '0'),
(178, 'timezone', 'Asia/Ho_Chi_Minh'),
(179, 'status_addfun_seller', '0'),
(180, 'status_store_document', '0'),
(181, 'noti_import_telegram', 'THÔNG BÁO IMPORT\r\n• Tên miền: {domain}\r\n• Số lượng: {amount}\r\n• Tên đơn: {name}\r\n• Thời gian: {time}'),
(182, 'group_id_import_telegram', ''),
(183, 'max_register_ip', '5'),
(184, 'check_time_cron6', '0'),
(185, 'pin_cron', ''),
(186, 'status_toyyibpay', '0'),
(187, 'notice_toyyibpay', ''),
(188, 'userSecretKey_toyyibpay', ''),
(189, 'min_toyyibpay', '1'),
(190, 'categoryCode_toyyibpay', ''),
(191, 'check_time_cron_toyyibpay', '0'),
(192, 'rate_toyyibpay', '5258'),
(193, 'billChargeToCustomer', ''),
(194, 'check_time_cron_sending_email', '1691856067'),
(195, 'check_time_cron7', '0'),
(196, 'host_smtp', 'smtp.gmail.com'),
(197, 'encryption_smtp', 'tls'),
(198, 'port_smtp', '587'),
(199, 'token_5gsmm', ''),
(200, 'ck_rate_service', '0'),
(201, 'status_updatec_rate_service', '0'),
(202, 'rate_vnd_5gsmm', '22500'),
(203, 'check_time_cron_UpdateRate5gsmm', '1686378453'),
(204, 'check_time_cron_UpdateHistory5gsmm', '0'),
(205, 'check_time_cron8', '0'),
(206, 'taohoadonnaptien_notification', 'THÔNG BÁO NẠP TIỀN\r\n• Tên miền: {domain}\r\n• Tên khách hàng: {username}\r\n• Phương thức: {method}\r\n• Số tiền nạp: {amount}\r\n• Thực nhận: {price}\r\n• Thời gian: {time}\r\n'),
(207, 'copyright_footer', 'Powered By <a target=\"_blank\" href=\"/\"><script>document.write(window.location.hostname.toUpperCase());</script></a>'),
(208, 'check_time_cron9', '0'),
(209, 'status_api_buyproduct', '1'),
(210, 'marquee_notication_shopacc', ''),
(211, 'status_thuesim', '0'),
(212, 'server_thuesim', 'API_1'),
(213, 'token_thuesim', 'cccc'),
(214, 'ck_rate_thuesim', '0'),
(215, 'check_time_cron_service_otp_cron', '1694524014'),
(216, 'notice_thuesim', ''),
(217, 'check_time_cron_service_otp_history', '1695206251'),
(218, 'check_time_cron10', '0'),
(219, 'text_create_website', '<ul>\r\n    <li>Bước 1: Trỏ NameServer (NS) tên miền về <b\r\n            style=\"color: red;\">ignacio.ns.cloudflare.com</b> và <b\r\n            style=\"color: red;\">meadow.ns.cloudflare.com</b>.</li>\r\n    <li>Bước 2: Nhập tên miền muốn đăng ký đại lý và nhấn Thêm Ngay.</li>\r\n    <li>Bước 3: Chờ đợi QTV setup website (thanh trạng thái thay đổi thành <b\r\n            style=\"color: green;\">Hoạt Động</b>).</li>\r\n    <li>Bước 4: Truy cập Website bạn vừa tạo và nhập thông tin token và đăng ký\r\n        1 tài khoản quản trị của\r\n        bạn (tài khoản đầu tiên sẽ là tài khoản admin, lưu ý không để lộ tên\r\n        miền ra khi chưa setup xong website).</li>\r\n</ul>'),
(220, 'status_create_website', '0'),
(221, 'stt_create_website', '0'),
(222, 'domain_thuesim', 'cc'),
(223, 'title_thuesim', 'Dịch vụ Thuê OTP, Thuê SIM tự động uy tín'),
(224, 'description_thuesim', 'Dịch vụ Thuê OTP, Thuê SIM tự động uy tín'),
(225, 'keyword_thuesim', 'thue sim, thue otp, thue sms'),
(226, 'create_website_notification', 'THÔNG BÁO TẠO WEB CON\r\n• Tên miền: {domain}\r\n• Tên khách hàng: {username}\r\n• Tên website con: {url}\r\n• Thời gian: {time}'),
(227, 'check_time_cron11', '0'),
(228, 'check_time_cron12', '0'),
(229, 'crypto_address', ''),
(230, 'crypto_token', ''),
(231, 'crypto_min', '1'),
(232, 'crypto_max', '1000000'),
(233, 'check_time_cron13', '0'),
(234, 'flutterwave_api_key', ''),
(235, 'flutterwave_api_secret', ''),
(236, 'prefix_invoice', 'NT'),
(237, 'logo_login', 'assets/storage/images/logo_light_HG8.png'),
(238, 'domain_smmpanel', 'https://5gsmm.com/'),
(239, 'show_category', 'head'),
(240, 'flutterwave_status', '0'),
(241, 'flutterwave_publicKey', ''),
(242, 'flutterwave_secretKey', ''),
(243, 'flutterwave_rate', '24000'),
(244, 'flutterwave_notice', ''),
(245, 'reCAPTCHA_status', '0'),
(246, 'reCAPTCHA_secret_key', ''),
(247, 'reCAPTCHA_site_key', ''),
(248, 'check_time_cron14', '0'),
(249, 'html_top_product', ''),
(250, 'html_banned', ''),
(251, 'html_block_ip', ''),
(252, 'squadco_status', '0'),
(253, 'squadco_Secret_Key', ''),
(254, 'squadco_Public_Key', ''),
(255, 'squadco_rate', '51'),
(256, 'squadco_currency_code', 'NGN'),
(257, 'squadco_notice', ''),
(258, 'check_time_cron15', '0'),
(259, 'buy_fanpage_notification', 'THÔNG BÁO MUA FANAPGE\r\n• Tên miền: {domain}\r\n• Tên khách hàng: {username}\r\n• Tên sản phẩm: {product_name}\r\n• Phương thức: {product_name}\r\n• Link Facebook: {url}\r\n• Số tiền: {price}\r\n• Thời gian: {time}'),
(260, 'is_update_phone', '0'),
(261, 'status_ban_xu_ttc', '0'),
(262, 'status_ban_xu_tds', '0'),
(263, 'min_ban_xu_ttc', '1000000'),
(264, 'max_ban_xu_ttc', '100000000'),
(265, 'rate_ban_xu_ttc', '15'),
(266, 'notice_ban_xu_ttc', ''),
(267, 'min_ban_xu_tds', '1000000'),
(268, 'max_ban_xu_tds', '100000000'),
(269, 'rate_ban_xu_tds', '15'),
(270, 'notice_ban_xu_tds', ''),
(271, 'check_time_cron_mua_xu_cron', '0'),
(272, 'check_time_cron_mua_xu_ttc', '0'),
(273, 'check_time_cron_mua_xu_tds', '0'),
(274, 'check_time_cron_mua_xu_cron_24h', '0'),
(275, 'check_time_cron_mua_xu_cron1', '0'),
(276, 'check_time_cron16', '0'),
(277, 'status_otp_login_admin', '0'),
(278, 'status_dichvugame', '1'),
(279, 'dichvu_notification', ''),
(280, 'discord_notification', 'OFF'),
(281, 'discord_webhook', ''),
(282, 'notice_dichvu', '<p>Thay đổi th&ocirc;ng b&aacute;o hệ thống&nbsp;trong <strong>Dịch Vụ Game -&gt; Cấu H&igrave;nh&nbsp;-&gt;&nbsp;Ghi Ch&uacute; Lịch Sử Dịch Vụ</strong></p>\r\n');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `spin_history`
--

CREATE TABLE `spin_history` (
  `id` int(11) NOT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `spin_option`
--

CREATE TABLE `spin_option` (
  `id` int(11) NOT NULL,
  `name` mediumtext DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `rate` float NOT NULL DEFAULT 0,
  `display` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `spin_option`
--

INSERT INTO `spin_option` (`id`, `name`, `price`, `rate`, `display`) VALUES
(1, '+ 20.000đ', 20000, 10, 1),
(2, '+ 10.000đ', 10000, 20, 1),
(3, '+ 50.000đ', 50000, 5, 1),
(4, '+ 2.000đ', 2000, 30, 1),
(5, '+ 100đ', 100, 30, 1),
(6, '+ 100.000đ', 100000, 2, 1),
(7, '+ 500.000đ', 500000, 0.5, 1),
(8, '+ 30.000đ', 30000, 10, 1),
(9, '+ 99.999đ', 99999, 5, 1),
(10, '+ 11.111đ', 11111, 20, 1),
(11, '+ 1.000.000đ', 1000000, 0.1, 1),
(12, '+ 22.000', 22000, 20, 1),
(13, '+ 222.222', 222222, 2, 1),
(14, '+ 6.666đ', 6666, 30, 1),
(15, '+ 77.777đ', 77777, 10, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `store_fanpage`
--

CREATE TABLE `store_fanpage` (
  `id` int(11) NOT NULL,
  `seller` int(11) NOT NULL DEFAULT 0,
  `buyer` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `url` mediumtext DEFAULT NULL,
  `new_name` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `sl_like` int(11) NOT NULL DEFAULT 0,
  `nam_tao_fanpage` varchar(255) DEFAULT NULL,
  `fb_admin` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `price` float NOT NULL DEFAULT 0,
  `content` longtext DEFAULT NULL,
  `create_gettime` datetime NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_gettime` datetime NOT NULL,
  `update_time` int(11) NOT NULL DEFAULT 0,
  `note` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `toyyibpay_transactions`
--

CREATE TABLE `toyyibpay_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `trans_id` varchar(50) DEFAULT NULL,
  `billName` text DEFAULT NULL,
  `amount` float NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `BillCode` varchar(50) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `translate`
--

CREATE TABLE `translate` (
  `id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL DEFAULT 0,
  `name` longtext DEFAULT NULL,
  `value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `admin` int(11) NOT NULL DEFAULT 0,
  `ctv` int(11) NOT NULL DEFAULT 0,
  `banned` int(11) NOT NULL DEFAULT 0,
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  `otp` varchar(55) DEFAULT NULL,
  `otp_limit` int(11) NOT NULL DEFAULT 0,
  `otp_token` text DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `time_session` int(11) DEFAULT 0,
  `time_request` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `money` float NOT NULL DEFAULT 0,
  `total_money` float NOT NULL DEFAULT 0,
  `rankings` int(11) NOT NULL DEFAULT 0,
  `icon_ranking` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` varchar(255) NOT NULL DEFAULT 'Male',
  `device` text DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `status_2fa` int(11) NOT NULL DEFAULT 0,
  `SecretKey_2fa` varchar(255) DEFAULT NULL,
  `token_2fa` text DEFAULT NULL,
  `limit_2fa` int(11) NOT NULL DEFAULT 0,
  `chietkhau` float NOT NULL DEFAULT 0,
  `spin` int(11) NOT NULL DEFAULT 0,
  `ref_id` int(11) NOT NULL DEFAULT 0,
  `ref_click` int(11) NOT NULL DEFAULT 0,
  `ref_money` float NOT NULL DEFAULT 0,
  `ref_total_money` float NOT NULL DEFAULT 0,
  `ref_amount` float NOT NULL DEFAULT 0,
  `ref_ck` float NOT NULL DEFAULT 0,
  `change_password` int(11) NOT NULL DEFAULT 0,
  `token_forgot_password` varchar(255) DEFAULT NULL,
  `time_forgot_password` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `fullname`, `phone`, `admin`, `ctv`, `banned`, `login_attempts`, `otp`, `otp_limit`, `otp_token`, `active`, `create_date`, `update_date`, `time_session`, `time_request`, `ip`, `token`, `money`, `total_money`, `rankings`, `icon_ranking`, `gender`, `device`, `avatar`, `status_2fa`, `SecretKey_2fa`, `token_2fa`, `limit_2fa`, `chietkhau`, `spin`, `ref_id`, `ref_click`, `ref_money`, `ref_total_money`, `ref_amount`, `ref_ck`, `change_password`, `token_forgot_password`, `time_forgot_password`) VALUES
(2, 'khanhmuzatv2', '7b0f75e6a6b2590c8cb77d62276ee177', 'khanhbts5@gmail.com', NULL, NULL, 1, 0, 0, 0, NULL, 0, NULL, 0, '2024-08-09 16:23:15', '2024-08-09 17:42:56', 1723199243, 1723199209, '2001:ee0:4274:b010:b824:cfa7:f8f7:2f68', '3e18006b80f91e651ecec6dc7927f90e', 0, 0, 0, NULL, 'Male', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', NULL, 0, 'LCM2TY4VAPGSIMBX', NULL, 0, 0, 0, 0, 9, 0, 0, 0, 0, 1, NULL, 0),
(3, 'Duykhanh2007', '1190cc959317da95980c7cf4f413cbd0', 'cskh.dichvuright@gmail.com', NULL, NULL, 0, 0, 0, 0, NULL, 0, NULL, 0, '2024-08-09 17:40:23', '2024-08-09 17:43:01', 1723200023, 0, '2001:ee0:4274:b010:b824:cfa7:f8f7:2f68', '8bc56953566d1fe15652caf918692d81', 0, 0, 0, NULL, 'Male', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', NULL, 0, 'XVFNEUTO735K3LMY', NULL, 0, 0, 0, 2, 0, 0, 0, 0, 0, 1, NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `withdraw_ref`
--

CREATE TABLE `withdraw_ref` (
  `id` int(11) NOT NULL,
  `trans_id` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `bank` varchar(255) DEFAULT NULL,
  `stk` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL,
  `update_gettime` datetime NOT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `banned_ips`
--
ALTER TABLE `banned_ips`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trans_id` (`trans_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `category_service`
--
ALTER TABLE `category_service`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `connect_api`
--
ALTER TABLE `connect_api`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `coupon_used`
--
ALTER TABLE `coupon_used`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `crypto_invoice`
--
ALTER TABLE `crypto_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `dichvugame`
--
ALTER TABLE `dichvugame`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `dichvu_groups`
--
ALTER TABLE `dichvu_groups`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `dichvu_history`
--
ALTER TABLE `dichvu_history`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `document_categories`
--
ALTER TABLE `document_categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domain` (`domain`);

--
-- Chỉ mục cho bảng `dongtien`
--
ALTER TABLE `dongtien`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `email_campaigns`
--
ALTER TABLE `email_campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `email_sending`
--
ALTER TABLE `email_sending`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `giftbox`
--
ALTER TABLE `giftbox`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trans_id` (`trans_id`),
  ADD UNIQUE KEY `tid` (`tid`);

--
-- Chỉ mục cho bảng `ip_white`
--
ALTER TABLE `ip_white`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `list_tds_ttc`
--
ALTER TABLE `list_tds_ttc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `log_ref`
--
ALTER TABLE `log_ref`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `log_tds_ttc`
--
ALTER TABLE `log_tds_ttc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `nowpayments`
--
ALTER TABLE `nowpayments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trans_id` (`trans_id`);

--
-- Chỉ mục cho bảng `order_autofb`
--
ALTER TABLE `order_autofb`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_service`
--
ALTER TABLE `order_service`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_tds_ttc`
--
ALTER TABLE `order_tds_ttc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `otp_history`
--
ALTER TABLE `otp_history`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `payment_flutterwave`
--
ALTER TABLE `payment_flutterwave`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `payment_paypal`
--
ALTER TABLE `payment_paypal`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `payment_pm`
--
ALTER TABLE `payment_pm`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `payment_squadco`
--
ALTER TABLE `payment_squadco`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `paypal`
--
ALTER TABLE `paypal`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `rate_autofb`
--
ALTER TABLE `rate_autofb`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `send_email`
--
ALTER TABLE `send_email`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `server2_autobank`
--
ALTER TABLE `server2_autobank`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tid` (`tid`);

--
-- Chỉ mục cho bảng `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `service_order`
--
ALTER TABLE `service_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trans_id` (`trans_id`);

--
-- Chỉ mục cho bảng `service_otp`
--
ALTER TABLE `service_otp`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `spin_history`
--
ALTER TABLE `spin_history`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `spin_option`
--
ALTER TABLE `spin_option`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `store_fanpage`
--
ALTER TABLE `store_fanpage`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `toyyibpay_transactions`
--
ALTER TABLE `toyyibpay_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trans_id` (`trans_id`),
  ADD UNIQUE KEY `BillCode` (`BillCode`);

--
-- Chỉ mục cho bảng `translate`
--
ALTER TABLE `translate`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `withdraw_ref`
--
ALTER TABLE `withdraw_ref`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trans_id` (`trans_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `addons`
--
ALTER TABLE `addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112247;

--
-- AUTO_INCREMENT cho bảng `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `banned_ips`
--
ALTER TABLE `banned_ips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `category_service`
--
ALTER TABLE `category_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `connect_api`
--
ALTER TABLE `connect_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `coupon_used`
--
ALTER TABLE `coupon_used`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `crypto_invoice`
--
ALTER TABLE `crypto_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `dichvugame`
--
ALTER TABLE `dichvugame`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `dichvu_groups`
--
ALTER TABLE `dichvu_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `dichvu_history`
--
ALTER TABLE `dichvu_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `document_categories`
--
ALTER TABLE `document_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `domains`
--
ALTER TABLE `domains`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `dongtien`
--
ALTER TABLE `dongtien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `email_campaigns`
--
ALTER TABLE `email_campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `email_sending`
--
ALTER TABLE `email_sending`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `giftbox`
--
ALTER TABLE `giftbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ip_white`
--
ALTER TABLE `ip_white`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `list_tds_ttc`
--
ALTER TABLE `list_tds_ttc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `log_ref`
--
ALTER TABLE `log_ref`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `log_tds_ttc`
--
ALTER TABLE `log_tds_ttc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nowpayments`
--
ALTER TABLE `nowpayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_autofb`
--
ALTER TABLE `order_autofb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_service`
--
ALTER TABLE `order_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_tds_ttc`
--
ALTER TABLE `order_tds_ttc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `otp_history`
--
ALTER TABLE `otp_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment_flutterwave`
--
ALTER TABLE `payment_flutterwave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment_paypal`
--
ALTER TABLE `payment_paypal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment_pm`
--
ALTER TABLE `payment_pm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment_squadco`
--
ALTER TABLE `payment_squadco`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `paypal`
--
ALTER TABLE `paypal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `rate_autofb`
--
ALTER TABLE `rate_autofb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `send_email`
--
ALTER TABLE `send_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `server2_autobank`
--
ALTER TABLE `server2_autobank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `service_order`
--
ALTER TABLE `service_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `service_otp`
--
ALTER TABLE `service_otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT cho bảng `spin_history`
--
ALTER TABLE `spin_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `spin_option`
--
ALTER TABLE `spin_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `store_fanpage`
--
ALTER TABLE `store_fanpage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `toyyibpay_transactions`
--
ALTER TABLE `toyyibpay_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `translate`
--
ALTER TABLE `translate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `withdraw_ref`
--
ALTER TABLE `withdraw_ref`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
