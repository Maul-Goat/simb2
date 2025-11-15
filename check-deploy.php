<?php
// File untuk cek deployment Railway
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html><head><title>Deployment Check</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1a1a1a;color:#0f0;}";
echo ".ok{color:#0f0;}.error{color:#f00;}.warn{color:#ff0;}</style></head><body>";

echo "<h1>🔍 Railway Deployment Check</h1>";

// Check PHP version
echo "<h2>1. PHP Version</h2>";
echo "<p class='ok'>✅ PHP " . phpversion() . "</p>";

// Check extensions
echo "<h2>2. PHP Extensions</h2>";
$required = ['curl', 'json'];
foreach ($required as $ext) {
    if (extension_loaded($ext)) {
        echo "<p class='ok'>✅ $ext</p>";
    } else {
        echo "<p class='error'>❌ $ext NOT LOADED</p>";
    }
}

// Check environment variables
echo "<h2>3. Environment Variables</h2>";
$env_vars = ['PORT', 'RAILWAY_ENVIRONMENT', 'SUPABASE_URL', 'SUPABASE_KEY'];
foreach ($env_vars as $var) {
    $value = getenv($var);
    if ($value) {
        $display = ($var === 'SUPABASE_KEY') ? substr($value, 0, 20) . '...' : $value;
        echo "<p class='ok'>✅ $var = $display</p>";
    } else {
        echo "<p class='warn'>⚠️  $var not set</p>";
    }
}

// Check file structure
echo "<h2>4. File Structure</h2>";
$files = [
    'router.php',
    'composer.json',
    'siglon-website/index.php',
    'siglon-website/config.php',
    'siglon-website/db_config.php',
    'pages/home.php',
    'css/style.css',
    'js/script.js'
];

foreach ($files as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "<p class='ok'>✅ $file</p>";
    } else {
        echo "<p class='error'>❌ $file NOT FOUND</p>";
    }
}

// Check if config can be loaded
echo "<h2>5. Config Test</h2>";
try {
    if (file_exists(__DIR__ . '/siglon-website/config.php')) {
        require_once __DIR__ . '/siglon-website/config.php';
        echo "<p class='ok'>✅ config.php loaded</p>";
        echo "<p class='ok'>BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'NOT DEFINED') . "</p>";
        echo "<p class='ok'>APP_PATH: " . (defined('APP_PATH') ? APP_PATH : 'NOT DEFINED') . "</p>";
    } else {
        echo "<p class='error'>❌ config.php not found</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Config Error: " . $e->getMessage() . "</p>";
}

// Check Supabase connection
echo "<h2>6. Database Connection</h2>";
try {
    if (isset($supabase)) {
        $test = $supabase->select('landslide_events', '*', ['limit' => 1]);
        if (isset($test['error'])) {
            echo "<p class='error'>❌ Supabase Error: " . $test['error'] . "</p>";
        } else {
            echo "<p class='ok'>✅ Supabase Connected</p>";
        }
    } else {
        echo "<p class='warn'>⚠️  Supabase client not initialized</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Database Error: " . $e->getMessage() . "</p>";
}

echo "<hr><h2>✅ Deployment Check Complete</h2>";
echo "<p>If all checks pass, your app should work at <a href='/' style='color:#0ff;'>Homepage</a></p>";
echo "</body></html>";