// Inisialisasi peta Jakarta
// ========================

// Batas wilayah Jakarta
var jakartaBounds = L.latLngBounds(
    L.latLng(-6.3950, 106.6890),  // southWest
    L.latLng(-5.9550, 107.0885)   // northEast
);

// Inisialisasi peta dengan konfigurasi Jakarta
var peta = L.map('map', {
    center: [-6.2146, 106.8451], // Pusat Jakarta
    zoom: 11
});

// Tambahkan tile layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(peta);

// Layer Group untuk marker
var indomaretLayer = L.layerGroup().addTo(peta);
var alfamartLayer = L.layerGroup().addTo(peta);

// Fungsi pengecekan lokasi di Jakarta
function isWithinJakarta(lat, lon) {
    return jakartaBounds.contains(L.latLng(lat, lon));
}

// Geolokasi pengguna
document.getElementById('locate-button').addEventListener('click', function() {
    if (!navigator.geolocation) {
        alert("Geolokasi tidak didukung oleh browser Anda.");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            var userLat = position.coords.latitude;
            var userLon = position.coords.longitude;

            if (!isWithinJakarta(userLat, userLon)) {
                alert("Anda berada di luar Jakarta!");
                return;
            }

            L.marker([userLat, userLon])
                .addTo(peta)
                .bindPopup("Lokasi Anda")
                .openPopup();

            findNearbyIndomarets(userLat, userLon);
            findNearbyAlfamarts(userLat, userLon);
        },
        function(error) {
            alert("Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.");
            console.error(error);
        }
    );
});

// Fungsi pencarian supermarket terdekat
function findNearbyIndomarets(userLat, userLon) {
    processSupermarketData(userLat, userLon, indomaretData, indomaretLayer, 'Indomaret');
}

function findNearbyAlfamarts(userLat, userLon) {
    processSupermarketData(userLat, userLon, alfamartData, alfamartLayer, 'Alfamart');
}

function processSupermarketData(userLat, userLon, data, layer, brand) {
    var radius = 2;
    var found = false;

    data.features.forEach(function(feature) {
        var [lon, lat] = feature.geometry.coordinates;

        if (!isWithinJakarta(lat, lon)) return;

        var distance = calculateDistance(userLat, userLon, lat, lon);
        if (distance <= radius) {
            addMarker(feature, layer, distance, brand);
            found = true;
        }
    });

    if (!found) alert(`Tidak ada ${brand} dalam radius 2 km.`);
}

// Fungsi pembantu
function addMarker(feature, layer, distance, brand) {
    var [lon, lat] = feature.geometry.coordinates;
    var name = feature.properties.name || brand;
    var parking = feature.properties.has_parking_attendant ? "Ada tukang parkir" : "Tidak ada tukang parkir";

    L.marker([lat, lon])
        .addTo(layer)
        .bindPopup(`<b>${name}</b><br>${parking}<br>Jarak: ${distance.toFixed(2)} km`);
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    var R = 6371;
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLon = (lon2 - lon1) * Math.PI / 180;
    var a =
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon/2) * Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Kontrol Pencarian
var searchControl = L.Control.extend({
    onAdd: function() {
        var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');

        var input = L.DomUtil.create('input', 'search-input');
        input.placeholder = "Cari Indomaret/Alfamart di Jakarta...";

        var button = L.DomUtil.create('button', 'search-btn');
        button.innerHTML = "🔍";

        L.DomEvent.on(button, 'click', () => {
            var query = input.value.toLowerCase();
            var results = [];

            [indomaretData, alfamartData].forEach(data => {
                data.features.forEach(feature => {
                    var [lon, lat] = feature.geometry.coordinates;
                    var name = feature.properties.name?.toLowerCase();
                    if (name?.includes(query) && isWithinJakarta(lat, lon)) {
                        results.push(feature);
                    }
                });
            });

            if (results.length > 0) {
                indomaretLayer.clearLayers();
                alfamartLayer.clearLayers();
                results.forEach(feature => {
                    var layer = feature.properties.brand === 'Indomaret' ? indomaretLayer : alfamartLayer;
                    addMarker(feature, layer, 0, feature.properties.brand);
                });
            } else {
                alert("Lokasi tidak ditemukan!");
            }
        });

        container.appendChild(input);
        container.appendChild(button);
        return container;
    }
});

// Load GeoJSON Data
var indomaretData, alfamartData;

fetch('Indomaret.geojson')
    .then(res => res.json())
    .then(data => {
        indomaretData = data;
        data.features.forEach(f => {
            var [lon, lat] = f.geometry.coordinates;
            if (isWithinJakarta(lat, lon)) addMarker(f, indomaretLayer, 0, 'Indomaret');
        });
    });

fetch('Alfamart.geojson')
    .then(res => res.json())
    .then(data => {
        alfamartData = data;
        data.features.forEach(f => {
            var [lon, lat] = f.geometry.coordinates;
            if (isWithinJakarta(lat, lon)) addMarker(f, alfamartLayer, 0, 'Alfamart');
        });
    });

// Kontrol Layer
L.control.layers(null, {
    "Indomaret": indomaretLayer,
    "Alfamart": alfamartLayer
}, { position: 'topright' }).addTo(peta);
