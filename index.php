<?php
require_once 'config.php';

// Routing sederhana
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Daftar halaman yang valid
$valid_pages = ['home', 'peta', 'statistik', 'pengetahuan', 'tentang', 'admin'];

// Jika halaman tidak valid, redirect ke home
if (!in_array($page, $valid_pages)) {
    $page = 'home';
}

// Include header
include 'header.php';

// Include navbar
include 'navbar.php';

// Include halaman yang diminta
$page_file = "pages/{$page}.php";
if (file_exists($page_file)) {
    include $page_file;
} else {
    include 'pages/home.php';
}

// Include footer
include 'footer.php';
?>