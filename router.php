<?php
// Router untuk PHP built-in server
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve file statis langsung
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Semua request ke index.php di folder siglon-website
require_once __DIR__ . '/siglon-website/index.php';