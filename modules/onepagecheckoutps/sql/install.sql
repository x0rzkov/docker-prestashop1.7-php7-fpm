SET NAMES "utf8";

CREATE TABLE IF NOT EXISTS `PREFIX_opc_field` (
`id_field` int(10) NOT NULL AUTO_INCREMENT,
`object` varchar(20) NOT NULL,
`name` varchar(50) NOT NULL,
`type` varchar(20) NOT NULL,
`size` int(10) NOT NULL,
`type_control` varchar(20) NOT NULL,
`is_custom` tinyint(1) NOT NULL,
`capitalize` tinyint(1) NOT NULL,
PRIMARY KEY (`id_field`)
)
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_opc_field_shop` (
`id_field` int(10) NOT NULL,
`id_shop` int(10) NOT NULL,
`default_value` varchar(255) NOT NULL,
`group` varchar(20) NOT NULL,
`row` int(10) NOT NULL,
`col` int(10) NOT NULL,
`required` tinyint(1) NOT NULL,
`active` tinyint(1) NOT NULL,
PRIMARY KEY (`id_field`, `id_shop`)
)
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_opc_field_lang` (
`id_field` int(10) NOT NULL,
`id_lang` int(10) NOT NULL,
`id_shop` int(10) NOT NULL,
`description` varchar(255) NOT NULL,
`label` varchar(255) NOT NULL,
PRIMARY KEY (`id_field`, `id_lang`, `id_shop`)
)
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_opc_field_option` (
`id_field_option` int NOT NULL AUTO_INCREMENT,
`id_field` int(10) NOT NULL,
`value` varchar(255) NOT NULL,
`position` int(10) NOT NULL,
PRIMARY KEY (`id_field_option`)
)
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_opc_field_option_lang` (
`id_field_option` int(10) NOT NULL,
`id_lang` int(10) NOT NULL,
`description` varchar(255) NOT NULL,
PRIMARY KEY (`id_field_option`, `id_lang`)
)
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_opc_payment` (
`id_payment` int(10) NOT NULL AUTO_INCREMENT,
`id_module` int(10) NOT NULL,
`name` varchar(255) NOT NULL,
`name_image` varchar(100) NOT NULL,
`force_display` TINYINT(1) NOT NULL,
`test_mode` TINYINT(1) NOT NULL,
`test_ip` varchar(300) NOT NULL,
PRIMARY KEY (`id_payment`)
)
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_opc_payment_lang` (
`id_payment` int(10) NOT NULL,
`id_lang` int(10) NOT NULL,
`id_shop` int(10) NOT NULL,
`title` varchar(255) NULL,
`description` varchar(255) NULL,
PRIMARY KEY (`id_payment`, `id_lang`, `id_shop`)
)
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_opc_payment_shop` (
`id_payment` int(10) NOT NULL,
`id_shop` int(10) NOT NULL,
PRIMARY KEY (`id_payment`, `id_shop`)
)
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_opc_social_network` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`id_shop` int(10) NOT NULL,
`id_customer` int(10) NOT NULL,
`code_network` varchar(50) NOT NULL,
`network` varchar(50) NOT NULL,
PRIMARY KEY (`id`)
)
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_opc_customer_address` (
`id_customer` int(10) NOT NULL,
`id_address` int(10) NOT NULL,
`object` varchar(10) NOT NULL,
PRIMARY KEY (`id_customer`, `id_address`, `object`)
)
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_opc_field_customer` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`id_field` int(10) NOT NULL,
`id_customer` int(10) NOT NULL,
`object` varchar(9) NOT NULL,
`id_address` int(10) NULL,
`id_option` int(10) NULL,
`value` varchar(255) NULL,
`date_upd` DATETIME NOT NULL,
PRIMARY KEY (`id`)
)
ENGINE=MYSQL_ENGINE DEFAULT CHARSET=utf8;

INSERT INTO `PREFIX_opc_field` (`id_field`, `object`, `name`, `type`, `size`, `type_control`, `is_custom`, `capitalize`) VALUES
(1, "customer", "id", "number", 0, "textbox", 0, 0),
(2, "customer", "firstname", "isName", 32, "textbox", 0, 1),
(3, "customer", "lastname", "isName", 32, "textbox", 0, 1),
(4, "customer", "email", "isEmail", 128, "textbox", 0, 0),
(5, "customer", "id_gender", "number", 0, "radio", 0, 0),
(6, "customer", "birthday", "isBirthDate", 0, "textbox", 0, 0),
(7, "customer", "newsletter", "isBool", 0, "checkbox", 0, 0),
(8, "customer", "optin", "isBool", 0, "checkbox", 0, 0),
(9, "customer", "passwd", "isPasswd", 32, "textbox", 0, 0),
(10, "customer", "siret", "isGenericName", 14, "textbox", 0, 0),
(11, "customer", "ape", "isGenericName", 5, "textbox", 0, 0),
(12, "customer", "website", "url", 128, "textbox", 0, 0),
(13, "delivery", "id", "number", 0, "textbox", 0, 0),
(14, "delivery", "dni", "isDniLite", 16, "textbox", 0, 0),
(15, "delivery", "company", "isGenericName", 32, "textbox", 0, 1),
(16, "delivery", "firstname", "isName", 32, "textbox", 0, 1),
(17, "delivery", "lastname", "isName", 32, "textbox", 0, 1),
(18, "delivery", "address1", "isAddress", 128, "textbox", 0, 1),
(19, "delivery", "address2", "isAddress", 128, "textbox", 0, 1),
(20, "delivery", "id_country", "number", 0, "select", 0, 0),
(21, "delivery", "id_state", "number", 0, "select", 0, 0),
(22, "delivery", "postcode", "isPostCode", 12, "textbox", 0, 1),
(23, "delivery", "city", "isCityName", 64, "textbox", 0, 1),
(24, "delivery", "phone", "isPhoneNumber", 16, "textbox", 0, 0),
(25, "delivery", "phone_mobile", "isPhoneNumber", 16, "textbox", 0, 0),
(26, "delivery", "other", "isMessage", 300, "textarea", 0, 0),
(27, "delivery", "alias", "isGenericName", 32, "textbox", 0, 0),
(28, "delivery", "vat_number", "isGenericName", 32, "textbox", 0, 0),
(29, "invoice", "id", "number", 0, "textbox", 0, 0),
(30, "invoice", "dni", "isDniLite", 16, "textbox", 0, 0),
(31, "invoice", "company", "isGenericName", 32, "textbox", 0, 1),
(32, "invoice", "firstname", "isName", 32, "textbox", 0, 1),
(33, "invoice", "lastname", "isName", 32, "textbox", 0, 1),
(34, "invoice", "address1", "isAddress", 128, "textbox", 0, 1),
(35, "invoice", "address2", "isAddress", 128, "textbox", 0, 1),
(36, "invoice", "id_country", "number", 0, "select", 0, 0),
(37, "invoice", "id_state", "number", 0, "select", 0, 0),
(38, "invoice", "postcode", "isPostCode", 12, "textbox", 0, 1),
(39, "invoice", "city", "isCityName", 64, "textbox", 0, 1),
(40, "invoice", "phone", "isPhoneNumber", 16, "textbox", 0, 0),
(41, "invoice", "phone_mobile", "isPhoneNumber", 16, "textbox", 0, 0),
(42, "invoice", "other", "isMessage", 300, "textarea", 0, 0),
(43, "invoice", "alias", "isGenericName", 32, "textbox", 0, 0),
(44, "invoice", "vat_number", "isGenericName", 32, "textbox", 0, 0);