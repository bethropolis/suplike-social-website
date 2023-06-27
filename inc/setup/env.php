<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

if (!defined('DB_DATABASE')) define('DB_DATABASE', 'suplike');
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USERNAME')) define('DB_USERNAME', 'root');
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', '');
if (!defined('DB_PORT')) define('DB_PORT', 3306);
if (!defined('APP_NAME')) define('APP_NAME', 'Suplike social network');
if (!defined('SETUP')) define('SETUP', false);
if (!defined('BASE_URL')) define('BASE_URL', $protocol . $_SERVER['HTTP_HOST']);
if (!defined('EMAIL_VERIFICATION')) define('EMAIL_VERIFICATION', false);
if (!defined('APP_EMAIL')) define('APP_EMAIL', 'test@localhost');
if (!defined('API_ACCESS')) define('API_ACCESS', true);
if (!defined('DEFAULT_THEME')) define('DEFAULT_THEME', 'dark');
if (!defined('ACCENT_COLOR')) define('ACCENT_COLOR', '');
if (!defined('FILE_EXTENSIONS')) define('FILE_EXTENSIONS', array('jpeg', 'jpg', 'png', 'gif', 'webp'));
if (!defined('FILE_SIZE_LIMIT')) define('FILE_SIZE_LIMIT', 6291456);
if (!defined('RANDOM_BYTES_LENGTH')) define('RANDOM_BYTES_LENGTH', 4);
