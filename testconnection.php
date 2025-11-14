<?php
require_once 'config.php';

echo "<h2>Test Koneksi Supabase</h2>";

// Test mengambil data landslide_events
echo "<h3>Test 1: Ambil Data Landslide Events</h3>";
$events = $supabase->select('landslide_events');

if (isset($events['error'])) {
    echo "<p style='color: red;'>❌ Error: " . $events['error'] . "</p>";
} else {
    echo "<p style='color: green;'>✅ Berhasil! Total kejadian: " . count($events) . "</p>";
    echo "<pre>" . print_r($events[0], true) . "</pre>";
}

// Test mengambil data articles
echo "<h3>Test 2: Ambil Data Articles</h3>";
$articles = $supabase->select('articles');

if (isset($articles['error'])) {
    echo "<p style='color: red;'>❌ Error: " . $articles['error'] . "</p>";
} else {
    echo "<p style='color: green;'>✅ Berhasil! Total artikel: " . count($articles) . "</p>";
    echo "<pre>" . print_r($articles[0], true) . "</pre>";
}
?>