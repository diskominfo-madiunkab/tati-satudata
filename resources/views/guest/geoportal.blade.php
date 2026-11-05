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
<div class="page-banner">
    <div class="container">
        <div class="page-banner-content text-center">
            <div class="page-banner-pill">
                <i class="fas fa-map-marked-alt text-info"></i> Satu Data Kabupaten Madiun
            </div>
            <h1 class="page-banner-title">Geoportal Kabupaten Madiun</h1>
            <p class="page-banner-subtitle">Visualisasi Layer Data Spasial dan Pemetaan Geospasial Kabupaten Madiun Terintegrasi.</p>
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
                            <i class="fas fa-hospital text-success me-1"></i> Fasilitas Kesehatan / Puskesmas (26)
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
                        <a href="https://madiunkab.ina-sdi.or.id/" target="_blank" class="btn btn-outline-primary btn-sm">
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
    const map = L.map('geoportal-map').setView([-7.6000, 111.6000], 11);

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

    // 15 Kecamatan di Kabupaten Madiun
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

    // 26 Puskesmas Resmi Kabupaten Madiun (Standar KMK HK.01.07/MENKES/2099/2023)
    const puskesmas = [
        { kode: "35190200001", name: "Puskesmas Gantrung", tipe: "Rawat Inap", kec: "Kebonsari", alamat: "Jl. P Diponegoro No 311, Ds. Mojorejo", lat: -7.7215, lng: 111.5152 },
        { kode: "35190200002", name: "Puskesmas Kebonsari", tipe: "Non Rawat Inap", kec: "Kebonsari", alamat: "Jl. Husni Thamrin, Ds. Balerejo", lat: -7.7280, lng: 111.5200 },
        { kode: "35190200003", name: "Puskesmas Geger", tipe: "Non Rawat Inap", kec: "Geger", alamat: "Jl. Raya Ponorogo No. 48, Ds. Purworejo", lat: -7.6970, lng: 111.5390 },
        { kode: "35190200004", name: "Puskesmas Kaibon", tipe: "Non Rawat Inap", kec: "Geger", alamat: "Jl. Pancotaruno No. 407, Ds. Kaibon", lat: -7.6820, lng: 111.5350 },
        { kode: "35190200005", name: "Puskesmas Mlilir", tipe: "Non Rawat Inap", kec: "Dolopo", alamat: "Jl. Raya Madiun Ponorogo Km 19", lat: -7.7650, lng: 111.5280 },
        { kode: "35190200006", name: "Puskesmas Bangunsari", tipe: "Non Rawat Inap", kec: "Dolopo", alamat: "Jl. Panjang Punjung Kel. Bangunsari", lat: -7.7810, lng: 111.5240 },
        { kode: "35190200007", name: "Puskesmas Dagangan", tipe: "Rawat Inap", kec: "Dagangan", alamat: "Jl. Raya Dagangan Pagotan No. 57", lat: -7.7120, lng: 111.5840 },
        { kode: "35190200008", name: "Puskesmas Jetis", tipe: "Non Rawat Inap", kec: "Dagangan", alamat: "Jl. Jetis, Ds. Jetis", lat: -7.7020, lng: 111.5650 },
        { kode: "35190200009", name: "Puskesmas Wungu", tipe: "Non Rawat Inap", kec: "Wungu", alamat: "Jl. Raya Kare No. 113, Ds. Wungu", lat: -7.6690, lng: 111.5850 },
        { kode: "35190200010", name: "Puskesmas Mojopurno", tipe: "Non Rawat Inap", kec: "Wungu", alamat: "Jl. Raya Dungus, Ds. Mojopurno", lat: -7.6480, lng: 111.5620 },
        { kode: "35190200011", name: "Puskesmas Kare", tipe: "Rawat Inap", kec: "Kare", alamat: "Jl Raya Randualas Kare, Ds. Kare", lat: -7.7330, lng: 111.6880 },
        { kode: "35190200012", name: "Puskesmas Gemarang", tipe: "Rawat Inap", kec: "Gemarang", alamat: "Jl. Tentara Genie Pelajar No 17", lat: -7.6320, lng: 111.7580 },
        { kode: "35190200013", name: "Puskesmas Saradan", tipe: "Rawat Inap", kec: "Saradan", alamat: "Jl. Raya Saradan – Madiun Ds. Sugihwaras", lat: -7.5180, lng: 111.7280 },
        { kode: "35190200014", name: "Puskesmas Sumbersari", tipe: "Rawat Inap", kec: "Saradan", alamat: "Jl. Raya Tulung No. 05, Ds. Sumbersari", lat: -7.5350, lng: 111.7120 },
        { kode: "35190200015", name: "Puskesmas Pilangkenceng", tipe: "Rawat Inap", kec: "Pilangkenceng", alamat: "Jl. Raya Kenongorejo No. 774", lat: -7.4980, lng: 111.6180 },
        { kode: "35190200016", name: "Puskesmas Krebet", tipe: "Rawat Inap", kec: "Pilangkenceng", alamat: "Jl. Gawang Utara No. 55 Ds. Krebet", lat: -7.4820, lng: 111.6350 },
        { kode: "35190200017", name: "Puskesmas Klecorejo", tipe: "Rawat Inap", kec: "Mejayan", alamat: "Jl. Raya Wates, Ds. Klecorejo", lat: -7.5550, lng: 111.6420 },
        { kode: "35190200018", name: "Puskesmas Mejayan", tipe: "Non Rawat Inap", kec: "Mejayan", alamat: "Jl. Panglima Sudirman No. 52", lat: -7.5450, lng: 111.6580 },
        { kode: "35190200019", name: "Puskesmas Wonoasri", tipe: "Non Rawat Inap", kec: "Wonoasri", alamat: "Jl. Raya Wonoasri, Ds. Wonoasri", lat: -7.5810, lng: 111.6370 },
        { kode: "35190200020", name: "Puskesmas Balerejo", tipe: "Rawat Inap", kec: "Balerejo", alamat: "Jl. Raya Madiun Surabaya No. 82", lat: -7.5270, lng: 111.6110 },
        { kode: "35190200021", name: "Puskesmas Simo", tipe: "Non Rawat Inap", kec: "Balerejo", alamat: "Jl. Raya Balerejo-Muneng No.96", lat: -7.5380, lng: 111.6240 },
        { kode: "35190200022", name: "Puskesmas Madiun", tipe: "Non Rawat Inap", kec: "Madiun", alamat: "Jl. Raya Puskesmas No.9, Ds. Tiron", lat: -7.5930, lng: 111.5540 },
        { kode: "35190200023", name: "Puskesmas Dimong", tipe: "Non Rawat Inap", kec: "Madiun", alamat: "Jl. Raya Dimong, Ds. Dimong", lat: -7.6080, lng: 111.5680 },
        { kode: "35190200024", name: "Puskesmas Sawahan", tipe: "Non Rawat Inap", kec: "Sawahan", alamat: "Jl. Raya Kajang No. 31 Ds. Kajang", lat: -7.5760, lng: 111.5360 },
        { kode: "35190200025", name: "Puskesmas Klagenserut", tipe: "Non Rawat Inap", kec: "Jiwan", alamat: "Jl. Raya Klagenserut Rt 08 Rw 03", lat: -7.6250, lng: 111.4780 },
        { kode: "35190200026", name: "Puskesmas Jiwan", tipe: "Non Rawat Inap", kec: "Jiwan", alamat: "Jl. Raya Solo No.85, Ds. Jiwan", lat: -7.6180, lng: 111.4880 }
    ];

    puskesmas.forEach(p => {
        const marker = L.circleMarker([p.lat, p.lng], {
            radius: 7,
            fillColor: p.tipe === 'Rawat Inap' ? "#0077b6" : "#2a9d8f",
            color: "#ffffff",
            weight: 2,
            opacity: 1,
            fillOpacity: 0.95
        }).bindPopup(
            "<div style='min-width: 200px;'>" +
            "<strong style='color:#023e8a;'>" + p.name + "</strong><br>" +
            "<span class='badge' style='background:#e0f2fe; color:#0369a1; font-size:10px; margin: 4px 0;'>" + p.tipe + "</span><br>" +
            "<small><strong>Kode:</strong> " + p.kode + "</small><br>" +
            "<small><strong>Kecamatan:</strong> " + p.kec + "</small><br>" +
            "<small class='text-muted'>" + p.alamat + "</small>" +
            "</div>"
        );
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
