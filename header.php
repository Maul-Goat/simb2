<?php
// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%23007BFF' d='M10 90 L50 10 L90 90 Z'/%3E%3C/svg%3E" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo defined('APP_NAME') ? APP_NAME : 'SIGLON'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'primary-light': '#F8F9FA',
              'secondary-light': '#FFFFFF',
              'gray-soft': '#E9ECEF',
              'text-dark': '#212529',
              'text-muted': '#6C757D',
              'accent-blue': '#007BFF',
              'accent-blue-hover': '#0056B3',
              'orange-warning': '#FD7E14',
              'green-info': '#28A745',
            },
            fontFamily: {
              'poppins': ['Poppins', 'sans-serif'],
              'lato': ['Lato', 'sans-serif'],
            }
          }
        }
      }
    </script>
</head>
<body class="bg-primary-light text-text-dark font-lato">
    <div id="app">