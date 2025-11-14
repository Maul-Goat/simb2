<?php
// Ambil artikel dari database
$articles = [];
try {
    $result = $supabase->query('articles', [
        'select' => '*',
        'order' => 'published_date.desc',
        'limit' => 12
    ]);
    
    if (!isset($result['error'])) {
        $articles = $result;
    }
} catch (Exception $e) {
    // Fallback data
}

// Jika belum ada artikel, gunakan data default
if (empty($articles)) {
    $articles = [
        [
            'id' => 1,
            'title' => 'Apa Itu Tanah Longsor?',
            'content' => 'Tanah longsor adalah perpindahan material pembentuk lereng berupa batuan, bahan rombakan, tanah, atau material campuran tersebut...',
            'category' => 'Pengetahuan Dasar',
            'summary' => 'Memahami definisi dan karakteristik tanah longsor'
        ],
        [
            'id' => 2,
            'title' => 'Jenis-jenis Tanah Longsor',
            'content' => 'Ada beberapa jenis tanah longsor yang perlu diketahui, antara lain longsoran translasi, longsoran rotasi, pergerakan blok...',
            'category' => 'Klasifikasi',
            'summary' => 'Mengenal berbagai jenis dan klasifikasi tanah longsor'
        ],
        [
            'id' => 3,
            'title' => 'Mitigasi Bencana Tanah Longsor',
            'content' => 'Mitigasi bencana tanah longsor dapat dilakukan melalui berbagai cara, baik struktural maupun non-struktural...',
            'category' => 'Mitigasi',
            'summary' => 'Langkah-langkah pencegahan dan mitigasi tanah longsor'
        ]
    ];
}

// Jika ada parameter article_id, tampilkan detail artikel
$selected_article = null;
if (isset($_GET['article'])) {
    $article_id = intval($_GET['article']);
    
    try {
        $result = $supabase->query('articles', [
            'select' => '*',
            'filters' => ["id=eq.{$article_id}"]
        ]);
        
        if (!isset($result['error']) && !empty($result)) {
            $selected_article = $result[0];
        }
    } catch (Exception $e) {
        // Article not found
    }
}
?>

<?php if ($selected_article): ?>
    <!-- Detail Artikel -->
    <main class="flex-grow">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="?page=pengetahuan" class="inline-flex items-center text-accent-blue hover:text-accent-blue-hover mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Artikel
            </a>
            
            <article class="bg-secondary-light rounded-xl shadow-md p-8">
                <div class="mb-6">
                    <span class="bg-accent-blue/10 text-accent-blue text-sm font-poppins font-medium px-4 py-2 rounded-full">
                        <?php echo htmlspecialchars($selected_article['category']); ?>
                    </span>
                </div>
                
                <h1 class="text-4xl font-poppins font-bold mb-4"><?php echo htmlspecialchars($selected_article['title']); ?></h1>
                
                <div class="flex items-center gap-4 text-text-muted mb-8">
                    <?php if (isset($selected_article['author'])): ?>
                    <span>Oleh <?php echo htmlspecialchars($selected_article['author']); ?></span>
                    <?php endif; ?>
                    <span>•</span>
                    <span><?php echo format_tanggal_indonesia($selected_article['published_date']); ?></span>
                </div>
                
                <div class="prose max-w-none text-text-muted leading-relaxed">
                    <?php echo nl2br(htmlspecialchars($selected_article['content'])); ?>
                </div>
            </article>
        </div>
    </main>
<?php else: ?>
    <!-- Daftar Artikel -->
    <main class="flex-grow">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-poppins font-bold mb-6">Pengetahuan & Edukasi</h1>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                <?php foreach ($articles as $article): ?>
                    <div class="bg-secondary-light rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="h-48 bg-gradient-to-r from-accent-blue to-blue-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="p-6">
                            <span class="bg-accent-blue/10 text-accent-blue text-xs font-poppins font-medium px-3 py-1 rounded-full mb-3 inline-block">
                                <?php echo htmlspecialchars($article['category']); ?>
                            </span>
                            <h3 class="font-poppins font-bold text-xl mb-3"><?php echo htmlspecialchars($article['title']); ?></h3>
                            <p class="text-text-muted mb-4 line-clamp-3">
                                <?php echo htmlspecialchars($article['summary'] ?? substr($article['content'], 0, 150) . '...'); ?>
                            </p>
                            <a href="?page=pengetahuan&article=<?php echo $article['id']; ?>" class="text-accent-blue hover:text-accent-blue-hover font-poppins font-medium flex items-center gap-2">
                                Baca Selengkapnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- FAQ Section -->
            <div class="bg-secondary-light rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-poppins font-bold mb-6">Pertanyaan Umum</h2>
                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="font-poppins font-bold text-lg mb-2">Apa penyebab utama tanah longsor?</h3>
                        <p class="text-text-muted">Penyebab utama tanah longsor antara lain hujan deras, erosi, gempa bumi, aktivitas manusia, dan lereng yang curam.</p>
                    </div>
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="font-poppins font-bold text-lg mb-2">Bagaimana cara mengenali daerah rawan longsor?</h3>
                        <p class="text-text-muted">Daerah rawan longsor biasanya memiliki ciri-ciri seperti retakan tanah, kemiringan lereng yang curam, dan riwayat kejadian longsor sebelumnya.</p>
                    </div>
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="font-poppins font-bold text-lg mb-2">Apa yang harus dilakukan saat terjadi tanah longsor?</h3>
                        <p class="text-text-muted">Segera evakuasi ke tempat yang lebih tinggi, hindari daerah lereng, dan ikuti instruksi dari pihak berwenang.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php endif; ?>