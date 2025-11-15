<?php
// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi dasar aplikasi
define('APP_NAME', 'SIGLON - Sistem Informasi Tanah Longsor');
define('APP_DESCRIPTION', 'Pusat informasi dan pemantauan tanah longsor di Indonesia');

// Konfigurasi environment
$is_production = getenv('RAILWAY_ENVIRONMENT') === 'production' || getenv('RAILWAY_STATIC_URL') || false;

if ($is_production) {
    // Konfigurasi production (Railway)
    $base_url = getenv('RAILWAY_STATIC_URL') ?: ('https://' . $_SERVER['HTTP_HOST']);
} else {
    // Konfigurasi development
    $base_url = 'http://' . $_SERVER['HTTP_HOST'];
}

define('BASE_URL', rtrim($base_url, '/'));

// Define paths
define('ROOT_PATH', dirname(__DIR__)); // Parent directory (root project)
define('APP_PATH', __DIR__); // siglon-website directory

// Include database configuration
require_once __DIR__ . '/db_config.php';

/**
 * Helper function untuk sanitasi input
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Helper function untuk format tanggal Indonesia
 */
function format_tanggal_indonesia($date) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $timestamp = strtotime($date);
    $tanggal = date('d', $timestamp);
    $bulan_num = date('n', $timestamp);
    $tahun = date('Y', $timestamp);
    
    return $tanggal . ' ' . $bulan[$bulan_num] . ' ' . $tahun;
}

/**
 * Helper function untuk redirect
 */
function redirect($page) {
    header("Location: ?page={$page}");
    exit;
}

/**
 * Helper function untuk set flash message
 */
function set_flash($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

/**
 * Helper function untuk get dan clear flash message
 */
function get_flash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}

/**
 * Helper function untuk check login
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Helper function untuk require login
 */
function require_login() {
    if (!is_logged_in()) {
        redirect('admin');
        exit;
    }
}
?>