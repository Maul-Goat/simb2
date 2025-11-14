<?php
// Data navigasi
$nav_links = [
    ['name' => 'Home', 'path' => 'home'],
    ['name' => 'Peta', 'path' => 'peta'],
    ['name' => 'Statistik', 'path' => 'statistik'],
    [
        'name' => 'Pengetahuan', 
        'path' => 'pengetahuan',
        'subLinks' => [
            ['name' => 'Info Tanah Longsor', 'path' => 'pengetahuan#longsor'],
            ['name' => 'Pengetahuan Bencana', 'path' => 'pengetahuan#pengetahuan'],
            ['name' => 'Berita', 'path' => 'pengetahuan#berita'],
        ]
    ],
    ['name' => 'Tentang Kami', 'path' => 'tentang'],
    ['name' => 'Admin', 'path' => 'admin'],
];

// Mendapatkan halaman aktif
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
if (empty($current_page)) {
    $current_page = 'home';
}
?>

<header class="bg-primary-light/80 backdrop-blur-sm sticky top-0 z-50 shadow-md">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <a href="?page=home" class="flex-shrink-0 flex items-center gap-2 group">
                <svg class="h-8 w-8 text-accent-blue" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.333 5.333a.667.667 0 10-1.333 0v1.334a.667.667 0 101.333 0V5.333zm-2 4.667a.667.667 0 01.667-.667h2.666a.667.667 0 110 1.333H8a.667.667 0 01-.667-.667zm.667 2.667a.667.667 0 100 1.333h4a.667.667 0 100-1.333H8z" clip-rule="evenodd"/>
                    <path d="M11.96 4.34a1.5 1.5 0 010 2.12l-3.535 3.536a1.5 1.5 0 11-2.121-2.121L9.84 4.34a1.5 1.5 0 012.12 0z"/>
                </svg>
                <span class="text-2xl font-poppins font-bold text-text-dark group-hover:text-accent-blue transition-colors duration-200">SIGLON</span>
            </a>
            
            <div class="hidden md:block">
                <nav class="ml-10 flex items-baseline space-x-1">
                    <?php foreach ($nav_links as $link): ?>
                        <?php if (isset($link['subLinks'])): ?>
                            <div class="relative group">
                                <button class="px-3 py-2 rounded-md text-sm font-poppins font-medium flex items-center gap-1 transition-all duration-300 <?php echo ($current_page === $link['path']) ? 'bg-accent-blue text-white shadow-[0_0_10px_theme(colors.accent-blue)]' : 'text-text-muted hover:bg-gray-200 hover:text-text-dark'; ?>">
                                    <?php echo $link['name']; ?>
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="absolute top-full mt-2 w-56 rounded-xl shadow-lg bg-secondary-light ring-1 ring-black ring-opacity-5 py-2 z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 origin-top-right">
                                    <?php foreach ($link['subLinks'] as $subLink): ?>
                                        <?php 
                                        $subLinkPath = explode('#', $subLink['path'])[0];
                                        ?>
                                        <a href="?page=<?php echo $subLinkPath; ?>" class="block px-4 py-2 text-sm font-poppins text-text-muted hover:bg-gray-100 hover:text-text-dark">
                                            <?php echo $subLink['name']; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="?page=<?php echo $link['path']; ?>" class="px-3 py-2 rounded-md text-sm font-poppins font-medium transition-all duration-300 <?php echo ($current_page === $link['path']) ? 'bg-accent-blue text-white shadow-[0_0_10px_theme(colors.accent-blue)]' : 'text-text-muted hover:bg-gray-200 hover:text-text-dark'; ?>">
                                <?php echo $link['name']; ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </nav>
            </div>
            
            <div class="-mr-2 flex md:hidden">
                <button id="mobile-menu-button" type="button" class="bg-gray-200 inline-flex items-center justify-center p-2 rounded-md text-text-dark hover:bg-accent-blue hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-primary-light focus:ring-accent-blue">
                    <span class="sr-only">Open main menu</span>
                    <svg id="menu-icon" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <?php foreach ($nav_links as $link): ?>
                <?php if (isset($link['subLinks'])): ?>
                    <div class="mobile-dropdown">
                        <button class="mobile-dropdown-toggle w-full text-left flex justify-between items-center px-3 py-2 rounded-md text-base font-poppins font-medium transition-colors duration-200 <?php echo ($current_page === $link['path']) ? 'bg-accent-blue text-white' : 'text-text-muted hover:bg-gray-200 hover:text-text-dark'; ?>">
                            <span><?php echo $link['name']; ?></span>
                            <svg class="h-5 w-5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="mobile-dropdown-content pl-5 mt-1 space-y-1 hidden">
                            <?php foreach ($link['subLinks'] as $subLink): ?>
                                <?php 
                                $subLinkPath = explode('#', $subLink['path'])[0];
                                ?>
                                <a href="?page=<?php echo $subLinkPath; ?>" class="block px-3 py-2 rounded-md text-base font-poppins font-medium transition-colors duration-200 <?php echo ($current_page === $subLinkPath) ? 'bg-accent-blue/80 text-white' : 'text-text-muted hover:bg-gray-200 hover:text-text-dark'; ?>">
                                    <?php echo $subLink['name']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="?page=<?php echo $link['path']; ?>" class="block px-3 py-2 rounded-md text-base font-poppins font-medium transition-colors duration-200 <?php echo ($current_page === $link['path']) ? 'bg-accent-blue text-white' : 'text-text-muted hover:bg-gray-200 hover:text-text-dark'; ?>">
                        <?php echo $link['name']; ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</header>