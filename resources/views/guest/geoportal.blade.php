@extends('guest.layout')

@push('style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #geoportal-map {
        height: 650px;
        width: 100%;
        border-radius: 12px;
        z-index: 1;
    }
    .map-sidebar-card {
        max-height: 650px;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="page-banner pt-40 pb-40" style="background: linear-gradient(135deg, #1b4965 0%, #2b2d42 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Satu Peta / Geoportal Kabupaten Madiun</h1>
            <p class="text-white-50 mb-0">Visualisasi Layer Data Spasial dan Pemetaan Geospasial Kabupaten Madiun Terintegrasi.</p>
        </div>
    </div>
</div>

<section class="geoportal-area pt-40 pb-70 bg-light">
    <div class="container-fluid px-lg-5">
        <div class="row g-4">
            <!-- Map Container -->
            <div class="col-lg-9">
                <div class="card shadow-sm border-0 rounded-3 p-2 bg-white">
                    <div id="geoportal-map"></div>
                </div>
            </div>

            <!-- Layer & Info Sidebar -->
            <div class="col-lg-3">
                <div class="card shadow-sm border-0 rounded-3 map-sidebar-card p-3 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-layer-group text-primary me-2"></i>Layer Tematik</h5>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="layerKecamatan" checked>
                        <label class="form-check-label fw-semibold" for="layerKecamatan">
                            <i class="fas fa-map-pin text-danger me-1"></i> Titik Pusat Kecamatan (15)
                        </label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="layerPuskesmas" checked>
                        <label class="form-check-label fw-semibold" for="layerPuskesmas">
                            <i class="fas fa-hospital text-success me-1"></i> Fasilitas Kesehatan / Puskesmas (21)
                        </label>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="layerPemerintahan" checked>
                        <label class="form-check-label fw-semibold" for="layerPemerintahan">
                            <i class="fas fa-landmark text-primary me-1"></i> Pusat Pemerintahan Kab. Madiun
                        </label>
                    </div>

                    <hr>

                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-info-circle text-info me-1"></i> Informasi Geospasial</h6>
                    <p class="small text-muted mb-2">
                        Penyelenggaraan Satu Peta Kabupaten Madiun mengacu pada Kebijakan Satu Peta (One Map Policy) dan Jaringan Informasi Geospasial Nasional (JIGN).
                    </p>

                    <div class="d-grid gap-2 mt-3">
                        <a href="http://madiunkab.ina-sdi.or.id/" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i> Buka Ina-SDI Geoportal
                        </a>
                        <a href="{{ route('guest.kode-referensi') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-table me-1"></i> Lihat Kode Referensi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('js')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Pusat Kabupaten Madiun (Caruban / Mejayan)
    const map = L.map('geoportal-map').setView([-7.54118, 111.65157], 11);

    // OpenStreetMap Tile Layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | Satu Data Kab. Madiun',
        maxZoom: 18
    }).addTo(map);

    // Layer Group
    const kecamatanLayer = L.layerGroup();
    const puskesmasLayer = L.layerGroup();
    const pemdaLayer = L.layerGroup();

    // Pusat Pemerintahan
    const pemdaMarker = L.marker([-7.54118, 111.65157])
        .bindPopup("<strong>Pusat Pemerintahan Kabupaten Madiun</strong><br>Caruban, Mejayan - Jawa Timur");
    pemdaLayer.addLayer(pemdaMarker);

    // 15 Kecamatan
    const kecamatans = [
        { name: "Kec. Mejayan (Caruban)", lat: -7.5450, lng: 111.6580 },
        { name: "Kec. Balerejo", lat: -7.5270, lng: 111.6110 },
        { name: "Kec. Wonoasri", lat: -7.5810, lng: 111.6370 },
        { name: "Kec. Sawahan", lat: -7.5760, lng: 111.5360 },
        { name: "Kec. Madiun", lat: -7.5930, lng: 111.5540 },
        { name: "Kec. Jiwan", lat: -7.6180, lng: 111.4880 },
        { name: "Kec. Geger", lat: -7.6970, lng: 111.5390 },
        { name: "Kec. Dagangan", lat: -7.7120, lng: 111.5840 },
        { name: "Kec. Kebonsari", lat: -7.7280, lng: 111.5200 },
        { name: "Kec. Dolopo", lat: -7.7780, lng: 111.5250 },
        { name: "Kec. Wungu", lat: -7.6690, lng: 111.5850 },
        { name: "Kec. Kare", lat: -7.7330, lng: 111.6880 },
        { name: "Kec. Gemarang", lat: -7.6320, lng: 111.7580 },
        { name: "Kec. Saradan", lat: -7.5180, lng: 111.7280 },
        { name: "Kec. Pilangkenceng", lat: -7.4980, lng: 111.6180 }
    ];

    kecamatans.forEach(k => {
        const marker = L.circleMarker([k.lat, k.lng], {
            radius: 8,
            fillColor: "#e63946",
            color: "#ffffff",
            weight: 2,
            opacity: 1,
            fillOpacity: 0.9
        }).bindPopup("<strong>" + k.name + "</strong><br>Kabupaten Madiun");
        kecamatanLayer.addLayer(marker);
    });

    // Puskesmas Sample Points
    const puskesmas = [
        { name: "Puskesmas Mejayan", lat: -7.5480, lng: 111.6620 },
        { name: "Puskesmas Balerejo", lat: -7.5290, lng: 111.6150 },
        { name: "Puskesmas Dolopo", lat: -7.7750, lng: 111.5280 },
        { name: "Puskesmas Geger", lat: -7.6990, lng: 111.5420 },
        { name: "Puskesmas Jiwan", lat: -7.6150, lng: 111.4920 },
        { name: "Puskesmas Saradan", lat: -7.5210, lng: 111.7320 }
    ];

    puskesmas.forEach(p => {
        const marker = L.circleMarker([p.lat, p.lng], {
            radius: 7,
            fillColor: "#2a9d8f",
            color: "#ffffff",
            weight: 2,
            opacity: 1,
            fillOpacity: 0.9
        }).bindPopup("<strong>" + p.name + "</strong><br>Fasilitas Pelayanan Kesehatan");
        puskesmasLayer.addLayer(marker);
    });

    // Default aktifkan layer
    kecamatanLayer.addTo(map);
    puskesmasLayer.addTo(map);
    pemdaLayer.addTo(map);

    // Toggle event listeners
    document.getElementById('layerKecamatan').addEventListener('change', function (e) {
        if (e.target.checked) {
            map.addLayer(kecamatanLayer);
        } else {
            map.removeLayer(kecamatanLayer);
        }
    });

    document.getElementById('layerPuskesmas').addEventListener('change', function (e) {
        if (e.target.checked) {
            map.addLayer(puskesmasLayer);
        } else {
            map.removeLayer(puskesmasLayer);
        }
    });

    document.getElementById('layerPemerintahan').addEventListener('change', function (e) {
        if (e.target.checked) {
            map.addLayer(pemdaLayer);
        } else {
            map.removeLayer(pemdaLayer);
        }
    });
});
</script>
@endpush
@endsection
