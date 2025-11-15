<?php
require_once __DIR__ . '/config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test Koneksi Supabase</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; }
        h2 { color: #007BFF; }
        h3 { color: #333; margin-top: 30px; }
    </style>
</head>
<body>
    <h1>🔌 Test Koneksi Supabase</h1>
    <p><strong>BASE_URL:</strong> " . BASE_URL . "</p>
    <p><strong>ROOT_PATH:</strong> " . ROOT_PATH . "</p>
    <p><strong>APP_PATH:</strong> " . APP_PATH . "</p>
    <hr>
";

// Test mengambil data landslide_events
echo "<h3>Test 1: Ambil Data Landslide Events</h3>";
$events = $supabase->select('landslide_events');

if (isset($events['error'])) {
    echo "<p class='error'>❌ Error: " . htmlspecialchars($events['error']) . "</p>";
    if (isset($events['code'])) {
        echo "<p class='error'>HTTP Code: " . $events['code'] . "</p>";
    }
} else {
    echo "<p class='success'>✅ Berhasil! Total kejadian: " . count($events) . "</p>";
    if (!empty($events)) {
        echo "<p>Sample data (first record):</p>";
        echo "<pre>" . print_r($events[0], true) . "</pre>";
    }
}

// Test mengambil data articles
echo "<h3>Test 2: Ambil Data Articles</h3>";
$articles = $supabase->select('articles');

if (isset($articles['error'])) {
    echo "<p class='error'>❌ Error: " . htmlspecialchars($articles['error']) . "</p>";
    if (isset($articles['code'])) {
        echo "<p class='error'>HTTP Code: " . $articles['code'] . "</p>";
    }
} else {
    echo "<p class='success'>✅ Berhasil! Total artikel: " . count($articles) . "</p>";
    if (!empty($articles)) {
        echo "<p>Sample data (first record):</p>";
        echo "<pre>" . print_r($articles[0], true) . "</pre>";
    }
}

// Test query dengan filter
echo "<h3>Test 3: Query dengan Filter (Latest 5 events)</h3>";
$latest_events = $supabase->query('landslide_events', [
    'select' => '*',
    'order' => 'tanggal.desc',
    'limit' => 5
]);

if (isset($latest_events['error'])) {
    echo "<p class='error'>❌ Error: " . htmlspecialchars($latest_events['error']) . "</p>";
} else {
    echo "<p class='success'>✅ Berhasil! Total kejadian: " . count($latest_events) . "</p>";
    if (!empty($latest_events)) {
        echo "<p>Sample data:</p>";
        echo "<pre>" . print_r($latest_events, true) . "</pre>";
    }
}

echo "
    <hr>
    <h3>✅ Test Selesai</h3>
    <p><a href='?'>← Kembali ke Home</a></p>
</body>
</html>";
?>