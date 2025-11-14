<?php
// Ambil data kejadian tanah longsor untuk peta
$events = [];
try {
    $events = $supabase->query('landslide_events', [
        'select' => '*',
        'order' => 'tanggal.desc'
    ]);
    
    if (isset($events['error'])) {
        $events = [];
    }
} catch (Exception $e) {
    $events = [];
}
?>

<main class="flex-grow">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-poppins font-bold mb-6">Peta Tanah Longsor</h1>
        
        <div class="bg-secondary-light rounded-xl shadow-md overflow-hidden mb-6">
            <div class="p-6">
                <h2 class="text-xl font-poppins font-bold mb-4">Peta Interaktif Kejadian Tanah Longsor</h2>
                <p class="text-text-muted mb-4">
                    Pantau lokasi kejadian tanah longsor di seluruh Indonesia. Data diperbarui secara berkala.
                </p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden h-96 mb-6">
            <div id="map" class="w-full h-full rounded-xl"></div>
        </div>
        
        <div class="bg-secondary-light rounded-xl shadow-md p-6">
            <h2 class="text-xl font-poppins font-bold mb-4">Legenda Peta</h2>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                    <span class="text-sm text-text-muted">Korban Meninggal < 2</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-orange-500 rounded-full border-2 border-white"></div>
                    <span class="text-sm text-text-muted">Korban Meninggal 2-4</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-red-500 rounded-full border-2 border-white"></div>
                    <span class="text-sm text-text-muted">Korban Meninggal ≥ 5</span>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;
    
    try {
        // Initialize map centered on Indonesia
        const map = L.map('map').setView([-2.5489, 118.0149], 5);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(map);
        
        // Add events data
        const events = <?php echo json_encode($events); ?>;
        
        if (events && events.length > 0) {
            events.forEach(event => {
                if (event.latitude && event.longitude) {
                    let markerColor = 'green';
                    let popupContent = `
                        <div class="p-2">
                            <h3 class="font-bold text-lg mb-2">${event.lokasi}</h3>
                            <p class="text-sm mb-1"><strong>Provinsi:</strong> ${event.provinsi}</p>
                            <p class="text-sm mb-1"><strong>Tanggal:</strong> ${event.tanggal}</p>
                            <p class="text-sm mb-1"><strong>Korban Meninggal:</strong> ${event.korban_meninggal || 0}</p>
                            <p class="text-sm mb-1"><strong>Korban Luka:</strong> ${event.korban_luka || 0}</p>
                            ${event.deskripsi ? `<p class="text-sm mt-2">${event.deskripsi}</p>` : ''}
                        </div>
                    `;
                    
                    // Determine marker color based on casualties
                    if (event.korban_meninggal >= 5) {
                        markerColor = 'red';
                    } else if (event.korban_meninggal >= 2) {
                        markerColor = 'orange';
                    }
                    
                    L.marker([parseFloat(event.latitude), parseFloat(event.longitude)], {
                        icon: L.divIcon({
                            className: 'custom-marker',
                            html: `<div style="background-color: ${markerColor}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                            iconSize: [16, 16],
                            iconAnchor: [8, 8]
                        })
                    })
                    .addTo(map)
                    .bindPopup(popupContent);
                }
            });
        } else {
            // Add default marker if no data
            L.marker([-6.2088, 106.8456])
                .addTo(map)
                .bindPopup('<b>Jakarta</b><br>Ibu Kota Indonesia')
                .openPopup();
        }
        
        // Add scale control
        L.control.scale().addTo(map);
        
    } catch (error) {
        console.error('Error initializing map:', error);
        mapElement.innerHTML = '<div class="flex items-center justify-center h-full text-red-500">Error loading map. Please refresh the page.</div>';
    }
});
</script>
