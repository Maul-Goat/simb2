<?php
// Konfigurasi environment-aware
$is_production = getenv('RAILWAY_ENVIRONMENT') === 'production' || getenv('RAILWAY_STATIC_URL') || false;

if ($is_production) {
    // Production configuration (Railway environment variables)
    $supabase_url = getenv('SUPABASE_URL');
    $supabase_key = getenv('SUPABASE_KEY');
    
    // Fallback jika environment variables tidak ada
    define('SUPABASE_URL', $supabase_url ?: 'https://gzjnusvsbzdsjffmiebu.supabase.co');
    define('SUPABASE_KEY', $supabase_key ?: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imd6am51c3ZzYnpkc2pmZm1pZWJ1Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjMxMjQyNDIsImV4cCI6MjA3ODcwMDI0Mn0.q7-G3Q9pwbCCoiU-F9EFaRwsZkzZuEBOYyxEpQIfaDo');
} else {
    // Development configuration
    define('SUPABASE_URL', 'https://gzjnusvsbzdsjffmiebu.supabase.co');
    define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imd6am51c3ZzYnpkc2pmZm1pZWJ1Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjMxMjQyNDIsImV4cCI6MjA3ODcwMDI0Mn0.q7-G3Q9pwbCCoiU-F9EFaRwsZkzZuEBOYyxEpQIfaDo');
}

class SupabaseClient {
    // ... (kode class tetap sama)
}

// Instance global
$supabase = new SupabaseClient();
?>
