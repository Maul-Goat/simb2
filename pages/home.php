<?php
// Ambil statistik dari database
$stats = [
    ['value' => 0, 'label' => 'Total Kejadian', 'color' => 'text-orange-warning'],
    ['value' => 0, 'label' => 'Korban Meninggal', 'color' => 'text-red-600'],
    ['value' => 0, 'label' => 'Korban Luka', 'color' => 'text-yellow-600'],
    ['value' => 0, 'label' => 'Rumah Rusak', 'color' => 'text-blue-600'],
];

try {
    $events = $supabase->select('landslide_events');
    
    if (!isset($events['error']) && !empty($events)) {
        $stats[0]['value'] = count($events);
        $stats[1]['value'] = array_sum(array_column($events, 'korban_meninggal'));
        $stats[2]['value'] = array_sum(array_column($events, 'korban_luka'));
        $stats[3]['value'] = array_sum(array_column($events, 'kerusakan_rumah'));
    }
} catch (Exception $e) {
    // Keep default values
}

// Ambil berita terbaru dari database
$news = [];
try {
    $articles = $supabase->query('articles', [
        'select' => '*',
        'order' => 'published_date.desc',
        'limit' => 3
    ]);
    
    if (!isset($articles['error'])) {
        foreach ($articles as $article) {
            $news[] = [
                'id' => $article['id'],
                'title' => $article['title'],
                'summary' => $article['summary'],
                'date' => format_tanggal_indonesia($article['published_date']),
                'category' => $article['category']
            ];
        }
    }
} catch (Exception $e) {
    // Fallback to default news
    $news = [
        [
            'id' => 1,
            'title' => 'Peningkatan Kewaspadaan Tanah Longsor di Musim Hujan',
            'summary' => 'BMKG mengingatkan masyarakat untuk meningkatkan kewaspadaan terhadap potensi tanah longsor di musim hujan ini.',
            'date' => '15 November 2023',
            'category' => 'Peringatan'
        ]
    ];
}

// Ambil beberapa kejadian terbaru untuk peta
$recent_events = [];
try {
    $recent_events = $supabase->query('landslide_events', [
        'select' => '*',
        'order' => 'tanggal.desc',
        'limit' => 10
    ]);
    
    if (isset($recent_events['error'])) {
        $recent_events = [];
    }
} catch (Exception $e) {
    $recent_events = [];
}
?>

<main class="flex-grow">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-accent-blue to-blue-600 text-white py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-poppins font-bold mb-4">SIGLON</h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">Sistem Informasi Tanah Longsor Indonesia - Pusat Informasi dan Pemantauan Tanah Longsor</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="?page=peta" class="bg-white text-accent-blue px-6 py-3 rounded-lg font-poppins font-medium hover:bg-gray-100 transition-colors duration-300 shadow-lg">
                    Lihat Peta
                </a>
                <a href="?page=pengetahuan" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-poppins font-medium hover:bg-white/10 transition-colors duration-300">
                    Pelajari Mitigasi
                </a>
            </div>
        </div>
    </section>

    <!-- Statistik Section -->
    <section class="py-16 bg-secondary-light">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-poppins font-bold text-center mb-12">Statistik Tanah Longsor</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php foreach ($stats as $stat): ?>
                    <div class="stat-card bg-primary-light rounded-xl p-6 text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="stat-value text-3xl md:text-4xl font-poppins font-bold <?php echo $stat['color']; ?> mb-2">
                            <?php echo $stat['value']; ?>
                        </div>
                        <div class="text-text-muted font-poppins font-medium">
                            <?php echo $stat['label']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Peta Section -->
    <section class="py-16 bg-gray-soft">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-poppins font-bold mb-4">Pemantauan Real-time</h2>
                    <p class="text-text-muted mb-6 text-lg">
                        Pantau lokasi kejadian tanah longsor di seluruh Indonesia dengan peta interaktif kami. 
                        Data diperbarui secara berkala dari sumber terpercaya.
                    </p>
                    <a href="?page=peta" class="inline-flex items-center gap-2 bg-accent-blue text-white px-6 py-3 rounded-lg font-poppins font-medium hover:bg-accent-blue-hover transition-colors duration-300">
                        Buka Peta Interaktif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden h-80">
                    <div id="home-map" class="w-full h-full rounded-xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Berita Section -->
    <section class="py-16 bg-secondary-light">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-3xl font-poppins font-bold">Berita Terbaru</h2>
                <a href="?page=pengetahuan" class="text-accent-blue hover:text-accent-blue-hover font-poppins font-medium flex items-center gap-2">
                    Lihat Semua Berita
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($news as $item): ?>
                    <div class="bg-primary-light rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <span class="bg-accent-blue/10 text-accent-blue text-xs font-poppins font-medium px-3 py-1 rounded-full">
                                    <?php echo htmlspecialchars($item['category']); ?>
                                </span>
                                <span class="text-text-muted text-sm"><?php echo htmlspecialchars($item['date']); ?></span>
                            </div>
                            <h3 class="font-poppins font-bold text-lg mb-3 line-clamp-2"><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p class="text-text-muted text-sm mb-4 line-clamp-3"><?php echo htmlspecialchars($item['summary']); ?></p>
                            <a href="?page=pengetahuan&article=<?php echo $item['id']; ?>" class="text-accent-blue hover:text-accent-blue-hover font-poppins font-medium text-sm flex items-center gap-1">
                                Baca Selengkapnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapElement = document.getElementById('home-map');
    if (!mapElement) return;
    
    try {
        const map = L.map('home-map').setView([-2.5489, 118.0149], 5);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        
        const recentEvents = <?php echo json_encode($recent_events); ?>;
        
        if (recentEvents && recentEvents.length > 0) {
            recentEvents.forEach(event => {
                let markerColor = 'green';
                if (event.korban_meninggal >= 5) {
                    markerColor = 'red';
                } else if (event.korban_meninggal >= 2) {
                    markerColor = 'orange';
                }
                
                L.marker([parseFloat(event.latitude), parseFloat(event.longitude)], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background-color: ${markerColor}; width: 10px; height: 10px; border-radius: 50%; border: 2px solid white;"></div>`,
                        iconSize: [14, 14],
                        iconAnchor: [7, 7]
                    })
                })
                .addTo(map)
                .bindPopup(`<b>${event.lokasi}</b><br>${event.provinsi}`);
            });
        }
    } catch (error) {
        console.error('Error initializing map:', error);
    }
});
</script>