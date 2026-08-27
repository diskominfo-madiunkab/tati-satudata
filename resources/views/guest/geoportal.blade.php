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
<div class="page-banner pt-60 pb-60" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%);">
    <div class="container">
        <div class="page-banner-content text-center text-white">
            <h1 class="text-white fw-bold mb-2">Geoportal Kabupaten Madiun</h1>
            <p class="text-white-50 mb-0" style="font-size: 16px;">Visualisasi data spasial tematik, sebaran fasilitas kesehatan/puskesmas, dan pemetaan wilayah Kabupaten Madiun</p>
        </div>
    </div>
</div>

<section class="geoportal-area pt-50 pb-70 bg-light">
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
                <div class="card shadow-sm border-0 rounded-3 map-sidebar-card p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-layer-group text-primary me-2"></i>Layer Tematik</h5>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="layerKecamatan" checked>
                        <label class="form-check-label fw-semibold text-dark" for="layerKecamatan">
                            <i class="fas fa-map-pin text-danger me-1"></i> Titik Pusat Kecamatan (15)
                        </label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="layerPuskesmas" checked>
                        <label class="form-check-label fw-semibold text-dark" for="layerPuskesmas">
                            <i class="fas fa-hospital text-success me-1"></i> Fasilitas Kesehatan / Puskesmas (26)
                        </label>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="layerPemerintahan" checked>
                        <label class="form-check-label fw-semibold text-dark" for="layerPemerintahan">
                            <i class="fas fa-landmark text-primary me-1"></i> Pusat Pemerintahan Kab. Madiun
                        </label>
                    </div>

                    <hr>

                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-info-circle text-info me-1"></i> Informasi Geospasial</h6>
                    <p class="small text-muted mb-3" style="line-height: 1.6;">
                        Penyelenggaraan Satu Peta Kabupaten Madiun mengacu pada Kebijakan Satu Peta (One Map Policy) dan Jaringan Informasi Geospasial Nasional (JIGN).
                    </p>

                    <div class="d-grid gap-2 mt-3">
                        <a href="https://madiunkab.ina-sdi.or.id/" target="_blank" class="btn btn-outline-primary btn-sm fw-semibold py-2">
                            <i class="fas fa-external-link-alt me-1"></i> Buka Geoportal Kab. Madiun
                        </a>
                        <a href="{{ route('guest.kode-referensi', ['tab' => 'puskesmas']) }}" class="btn btn-outline-secondary btn-sm fw-semibold py-2">
                            <i class="fas fa-table me-1"></i> Lihat 26 Puskesmas KMK 2023
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
    const kecamatanLayer = L.layerGroup().addTo(map);
    const puskesmasLayer = L.layerGroup().addTo(map);
    const pemdaLayer = L.layerGroup().addTo(map);

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
        }).bindPopup("<strong>" + k.name + "</strong><br>Pusat Pemerintahan Kecamatan");
        kecamatanLayer.addLayer(marker);
    });

    // 26 Puskesmas Resmi Kabupaten Madiun (KMK HK.01.07-MENKES-2099-2023)
    const puskesmasList = [
        { kode: "35190200001", nama: "Puskesmas Gantrung", kec: "Kebonsari", tipe: "Rawat Inap", lat: -7.7185, lng: 111.5120 },
        { kode: "35190200002", nama: "Puskesmas Kebonsari", kec: "Kebonsari", tipe: "Non Rawat Inap", lat: -7.7340, lng: 111.5240 },
        { kode: "35190200003", nama: "Puskesmas Geger", kec: "Geger", tipe: "Non Rawat Inap", lat: -7.6965, lng: 111.5385 },
        { kode: "35190200004", nama: "Puskesmas Kaibon", kec: "Geger", tipe: "Non Rawat Inap", lat: -7.6740, lng: 111.5450 },
        { kode: "35190200005", nama: "Puskesmas Mlilir", kec: "Dolopo", tipe: "Non Rawat Inap", lat: -7.7420, lng: 111.5310 },
        { kode: "35190200006", nama: "Puskesmas Bangunsari", kec: "Dolopo", tipe: "Non Rawat Inap", lat: -7.7785, lng: 111.5260 },
        { kode: "35190200007", nama: "Puskesmas Dagangan", kec: "Dagangan", tipe: "Rawat Inap", lat: -7.7125, lng: 111.5840 },
        { kode: "35190200008", nama: "Puskesmas Jetis", kec: "Dagangan", tipe: "Non Rawat Inap", lat: -7.6890, lng: 111.5620 },
        { kode: "35190200009", nama: "Puskesmas Wungu", kec: "Wungu", tipe: "Non Rawat Inap", lat: -7.6685, lng: 111.5850 },
        { kode: "35190200010", nama: "Puskesmas Mojopurno", kec: "Wungu", tipe: "Non Rawat Inap", lat: -7.6470, lng: 111.5520 },
        { kode: "35190200011", nama: "Puskesmas Kare", kec: "Kare", tipe: "Rawat Inap", lat: -7.7325, lng: 111.6880 },
        { kode: "35190200012", nama: "Puskesmas Gemarang", kec: "Gemarang", tipe: "Rawat Inap", lat: -7.6320, lng: 111.7580 },
        { kode: "35190200013", nama: "Puskesmas Saradan", kec: "Saradan", tipe: "Rawat Inap", lat: -7.5185, lng: 111.7285 },
        { kode: "35190200014", nama: "Puskesmas Sumbersari", kec: "Saradan", tipe: "Rawat Inap", lat: -7.5540, lng: 111.7120 },
        { kode: "35190200015", nama: "Puskesmas Pilangkenceng", kec: "Pilangkenceng", tipe: "Rawat Inap", lat: -7.4980, lng: 111.6180 },
        { kode: "35190200016", nama: "Puskesmas Krebet", kec: "Pilangkenceng", tipe: "Rawat Inap", lat: -7.5120, lng: 111.6420 },
        { kode: "35190200017", nama: "Puskesmas Klecorejo", kec: "Mejayan", tipe: "Rawat Inap", lat: -7.5250, lng: 111.6720 },
        { kode: "35190200018", nama: "Puskesmas Mejayan", kec: "Mejayan", tipe: "Non Rawat Inap", lat: -7.5480, lng: 111.6620 },
        { kode: "35190200019", nama: "Puskesmas Wonoasri", kec: "Wonoasri", tipe: "Non Rawat Inap", lat: -7.5810, lng: 111.6370 },
        { kode: "35190200020", nama: "Puskesmas Balerejo", kec: "Balerejo", tipe: "Rawat Inap", lat: -7.5285, lng: 111.6120 },
        { kode: "35190200021", nama: "Puskesmas Simo", kec: "Balerejo", tipe: "Non Rawat Inap", lat: -7.5420, lng: 111.5890 },
        { kode: "35190200022", nama: "Puskesmas Madiun", kec: "Madiun", tipe: "Non Rawat Inap", lat: -7.5930, lng: 111.5540 },
        { kode: "35190200023", nama: "Puskesmas Dimong", kec: "Madiun", tipe: "Non Rawat Inap", lat: -7.6120, lng: 111.5680 },
        { kode: "35190200024", nama: "Puskesmas Sawahan", kec: "Sawahan", tipe: "Non Rawat Inap", lat: -7.5760, lng: 111.5360 },
        { kode: "35190200025", nama: "Puskesmas Klagenserut", kec: "Jiwan", tipe: "Non Rawat Inap", lat: -7.5980, lng: 111.5120 },
        { kode: "35190200026", nama: "Puskesmas Jiwan", kec: "Jiwan", tipe: "Non Rawat Inap", lat: -7.6180, lng: 111.4880 }
    ];

    puskesmasList.forEach(p => {
        const pMarker = L.circleMarker([p.lat, p.lng], {
            radius: 7,
            fillColor: "#2a9d8f",
            color: "#ffffff",
            weight: 2,
            opacity: 1,
            fillOpacity: 0.95
        }).bindPopup(
            "<strong>" + p.nama + "</strong><br>" +
            "<span class='badge bg-danger'>Kode: " + p.kode + "</span><br>" +
            "Kecamatan: " + p.kec + "<br>" +
            "Layanan: " + p.tipe
        );
        puskesmasLayer.addLayer(pMarker);
    });

    // Toggle Handlers
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
