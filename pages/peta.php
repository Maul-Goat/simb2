<?php
// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi dasar aplikasi
define('APP_NAME', 'SIGLON - Sistem Informasi Tanah Longsor');
define('APP_DESCRIPTION', 'Pusat informasi dan pemantauan tanah longsor di Indonesia');

// Konfigurasi path
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
define('BASE_URL', rtrim($base_url, '/'));

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
?>