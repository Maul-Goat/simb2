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
    private $url;
    private $key;
    
    public function __construct() {
        $this->url = SUPABASE_URL;
        $this->key = SUPABASE_KEY;
    }
    
    /**
     * Melakukan query SELECT ke Supabase
     */
    public function select($table, $columns = '*', $filters = []) {
        $endpoint = "{$this->url}/rest/v1/{$table}?select={$columns}";
        
        // Tambahkan filter jika ada
        foreach ($filters as $key => $value) {
            $endpoint .= "&{$key}=eq.{$value}";
        }
        
        return $this->request('GET', $endpoint);
    }
    
    /**
     * Insert data ke Supabase
     */
    public function insert($table, $data) {
        $endpoint = "{$this->url}/rest/v1/{$table}";
        return $this->request('POST', $endpoint, $data);
    }
    
    /**
     * Update data di Supabase
     */
    public function update($table, $id, $data) {
        $endpoint = "{$this->url}/rest/v1/{$table}?id=eq.{$id}";
        return $this->request('PATCH', $endpoint, $data);
    }
    
    /**
     * Delete data dari Supabase
     */
    public function delete($table, $id) {
        $endpoint = "{$this->url}/rest/v1/{$table}?id=eq.{$id}";
        return $this->request('DELETE', $endpoint);
    }
    
    /**
     * Melakukan HTTP request ke Supabase
     */
    private function request($method, $endpoint, $data = null) {
        $ch = curl_init();
        
        $headers = [
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ];
        
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['error' => $error];
        }
        
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if ($httpCode >= 400) {
            return ['error' => $result['message'] ?? 'Unknown error', 'code' => $httpCode];
        }
        
        return $result;
    }
    
    /**
     * Query dengan filter kompleks
     */
    public function query($table, $params = []) {
        $endpoint = "{$this->url}/rest/v1/{$table}";
        $queryParams = [];
        
        // Select columns
        if (isset($params['select'])) {
            $queryParams[] = "select={$params['select']}";
        } else {
            $queryParams[] = "select=*";
        }
        
        // Filter conditions
        if (isset($params['filters'])) {
            foreach ($params['filters'] as $filter) {
                $queryParams[] = $filter;
            }
        }
        
        // Order by
        if (isset($params['order'])) {
            $queryParams[] = "order={$params['order']}";
        }
        
        // Limit
        if (isset($params['limit'])) {
            $queryParams[] = "limit={$params['limit']}";
        }
        
        // Offset
        if (isset($params['offset'])) {
            $queryParams[] = "offset={$params['offset']}";
        }
        
        if (!empty($queryParams)) {
            $endpoint .= '?' . implode('&', $queryParams);
        }
        
        return $this->request('GET', $endpoint);
    }
}

// Instance global
$supabase = new SupabaseClient();
?>
