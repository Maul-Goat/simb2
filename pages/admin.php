<?php
require_once 'config.php';

// Handle login
if ($_POST['action'] ?? '' === 'login') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    
    // Simple authentication (in production, use proper password hashing)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = $username;
        set_flash('success', 'Login berhasil!');
        redirect('admin');
    } else {
        set_flash('error', 'Username atau password salah!');
    }
}

// Handle logout
if ($_GET['action'] ?? '' === 'logout') {
    session_destroy();
    redirect('home');
}

// Jika belum login, tampilkan form login
if (!is_logged_in()): ?>
<main class="flex-grow">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-secondary-light rounded-xl shadow-md p-8">
            <h1 class="text-2xl font-poppins font-bold mb-6 text-center">Admin Login</h1>
            
            <?php if ($error = get_flash('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="?page=admin">
                <input type="hidden" name="action" value="login">
                
                <div class="mb-4">
                    <label class="block text-text-muted text-sm font-poppins font-medium mb-2">
                        Username
                    </label>
                    <input type="text" name="username" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                </div>
                
                <div class="mb-6">
                    <label class="block text-text-muted text-sm font-poppins font-medium mb-2">
                        Password
                    </label>
                    <input type="password" name="password" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent-blue focus:border-transparent">
                </div>
                
                <button type="submit" 
                        class="w-full bg-accent-blue text-white py-2 px-4 rounded-lg font-poppins font-medium hover:bg-accent-blue-hover transition-colors duration-300">
                    Login
                </button>
            </form>
            
            <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                <p class="text-sm text-text-muted text-center">
                    <strong>Default Login:</strong><br>
                    Username: admin<br>
                    Password: admin123
                </p>
            </div>
        </div>
    </div>
</main>
<?php else: ?>
<main class="flex-grow">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-poppins font-bold">Panel Admin</h1>
            <a href="?page=admin&action=logout" 
               class="bg-red-500 text-white px-4 py-2 rounded-lg font-poppins font-medium hover:bg-red-600 transition-colors duration-300">
                Logout
            </a>
        </div>
        
        <?php if ($success = get_flash('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <?php
            // Ambil statistik
            $total_events = 0;
            $verified_events = 0;
            $pending_events = 0;
            
            try {
                $events = $supabase->select('landslide_events');
                if (!isset($events['error'])) {
                    $total_events = count($events);
                    $verified_events = $total_events; // Simplified
                    $pending_events = 0; // Simplified
                }
            } catch (Exception $e) {
                // Error handling
            }
            ?>
            
            <div class="bg-secondary-light rounded-xl shadow-md p-6 text-center">
                <div class="text-3xl font-poppins font-bold text-accent-blue mb-2"><?php echo $total_events; ?></div>
                <div class="text-text-muted">Total Laporan</div>
            </div>
            <div class="bg-secondary-light rounded-xl shadow-md p-6 text-center">
                <div class="text-3xl font-poppins font-bold text-green-info mb-2"><?php echo $verified_events; ?></div>
                <div class="text-text-muted">Terverifikasi</div>
            </div>
            <div class="bg-secondary-light rounded-xl shadow-md p-6 text-center">
                <div class="text-3xl font-poppins font-bold text-orange-warning mb-2"><?php echo $pending_events; ?></div>
                <div class="text-text-muted">Menunggu</div>
            </div>
        </div>
        
        <div class="bg-secondary-light rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6">
                <h2 class="text-xl font-poppins font-bold mb-4">Kelola Data Kejadian</h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-primary-light">
                                <th class="px-4 py-3 text-left text-sm font-poppins font-medium text-text-muted">Lokasi</th>
                                <th class="px-4 py-3 text-left text-sm font-poppins font-medium text-text-muted">Tanggal</th>
                                <th class="px-4 py-3 text-left text-sm font-poppins font-medium text-text-muted">Status</th>
                                <th class="px-4 py-3 text-left text-sm font-poppins font-medium text-text-muted">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php
                            try {
                                $events = $supabase->query('landslide_events', [
                                    'select' => '*',
                                    'order' => 'tanggal.desc',
                                    'limit' => 10
                                ]);
                                
                                if (!isset($events['error']) && !empty($events)) {
                                    foreach ($events as $event) {
                                        echo "<tr>
                                            <td class='px-4 py-3 text-sm font-poppins'>{$event['lokasi']}</td>
                                            <td class='px-4 py-3 text-sm text-text-muted'>" . format_tanggal_indonesia($event['tanggal']) . "</td>
                                            <td class='px-4 py-3'>
                                                <span class='bg-green-info/10 text-green-info text-xs font-poppins font-medium px-3 py-1 rounded-full'>
                                                    Terverifikasi
                                                </span>
                                            </td>
                                            <td class='px-4 py-3'>
                                                <button class='text-accent-blue hover:text-accent-blue-hover font-poppins font-medium text-sm'>
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='px-4 py-3 text-center text-text-muted'>Tidak ada data</td></tr>";
                                }
                            } catch (Exception $e) {
                                echo "<tr><td colspan='4' class='px-4 py-3 text-center text-red-500'>Error loading data</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php endif; ?>