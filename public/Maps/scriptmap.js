// Inisialisasi peta
var peta = L.map('map').setView([-6.912148511760036, 107.59537123067042], 13); // Koordinat Bandung

// Menambahkan tile layer (layer peta)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetmap</a> contributors'
}).addTo(peta);

// Menyimpan layer supermarket
var indomaretLayer = L.layerGroup().addTo(peta); // Marker Indomaret
var alfamartLayer = L.layerGroup().addTo(peta); // Marker Alfamart

// Menentukan batas wilayah Bandung
var bandungBounds = {
    southWest: [-6.9914, 107.4889], // Koordinat barat daya
    northEast: [-6.8505, 107.6925], // Koordinat timur laut
};

document.getElementById('locate-button').addEventListener('click', function () {
    // Periksa apakah browser mendukung geolokasi
    if (!navigator.geolocation) {
        alert("Geolokasi tidak didukung oleh browser Anda.");
        return;
    }

    // Dapatkan lokasi pengguna
    navigator.geolocation.getCurrentPosition(function (position) {
        var userLat = position.coords.latitude;
        var userLon = position.coords.longitude;

        // Tambahkan marker lokasi pengguna di peta
        L.marker([userLat, userLon])
            .addTo(peta)
            .bindPopup("Lokasi Anda")
            .openPopup();

        // Cari Indomaret di sekitar lokasi pengguna
        findNearbyIndomarets(userLat, userLon);
        findNearbyAlfamarts(userLat, userLon)
    }, function (error) {
        alert("Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.");
        console.error(error);
    });
});

function findNearbyIndomarets(userLat, userLon) {
    var radius = 2; // Radius dalam kilometer
    var nearbyIndomaret = [];

    indomaretData.features.forEach(function (feature) {
        var indomaretLat = feature.geometry.coordinates[1];
        var indomaretLon = feature.geometry.coordinates[0];

        // Hitung jarak antara pengguna dan Indomaret
        var distance = calculateDistance(userLat, userLon, indomaretLat, indomaretLon);
        if (distance <= radius) {
            nearbyIndomaret.push(feature);

            // Tambahkan marker Indomaret ke peta
            var name = feature.properties.name || "Indomaret";
            var hasParkingAttendant = feature.properties.has_parking_attendant;
            var parkingInfo = hasParkingAttendant
                ? "Ada tukang parkir"
                : "Tidak ada tukang parkir";

            L.marker([indomaretLat, indomaretLon])
                .addTo(indomaretLayer)
                .bindPopup(`<b>${name}</b><br>${parkingInfo}<br>Jarak: ${distance.toFixed(2)} km`);
        }
    });

    if (nearbyIndomaret.length === 0) {
        alert("Tidak ada Indomaret di sekitar Anda dalam radius " + radius + " km.");
    }
}

function findNearbyAlfamarts(userLat, userLon) {
    var radius = 2; // Radius dalam kilometer
    var nearbyAlfamarts = [];

    alfamartData.features.forEach(function (feature) {
        var alfamartLat = feature.geometry.coordinates[1];
        var alfamartLon = feature.geometry.coordinates[0];

        // Hitung jarak antara pengguna dan Indomaret
        var distance = calculateDistance(userLat, userLon, alfamartLat, alfamartLon);
        if (distance <= radius) {
            nearbyAlfamarts.push(feature);

            // Tambahkan marker Indomaret ke peta
            var name = feature.properties.name || "Alfamart";
            var hasParkingAttendant = feature.properties.has_parking_attendant;
            var parkingInfo = hasParkingAttendant
                ? "Ada tukang parkir"
                : "Tidak ada tukang parkir";

            L.marker([alfamartLat, alfamartLon])
                .addTo(alfamartLayer)
                .bindPopup(`<b>${name}</b><br>${parkingInfo}<br>Jarak: ${distance.toFixed(2)} km`);
        }
    });

    if (nearbyAlfamarts.length === 0) {
        alert("Tidak ada Alfamart di sekitar Anda dalam radius " + radius + " km.");
    }
}

// Fungsi untuk menghitung jarak menggunakan haversine formula
function calculateDistance(lat1, lon1, lat2, lon2) {
    var R = 6371; // Radius bumi dalam kilometer
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLon = (lon2 - lon1) * Math.PI / 180;
    var a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c; // Jarak dalam kilometer
}

function findNearestSupermarket(userLat, userLon, supermarketType, requireParkingAttendant) {
    var radius = 2; // Radius dalam kilometer
    var closestSupermarket = null;
    var closestDistance = Infinity;

    // Pilih data yang akan dicari berdasarkan supermarketType (Indomaret/Alfamart)
    var dataToSearch = supermarketType === 'indomaret' ? indomaretData : alfamartData;
    var supermarketLayer = supermarketType === 'indomaret' ? indomaretLayer : alfamartLayer;

    // Loop untuk mencari supermarket terdekat berdasarkan pilihan
    dataToSearch.features.forEach(function (feature) {
        var lat = feature.geometry.coordinates[1];
        var lon = feature.geometry.coordinates[0];
        var hasParkingAttendant = feature.properties.has_parking_attendant;

        // Filter berdasarkan opsi parkir
        if (requireParkingAttendant && !hasParkingAttendant) return;
        if (!requireParkingAttendant && hasParkingAttendant) return;

        // Hitung jarak
        var distance = calculateDistance(userLat, userLon, lat, lon);
        if (distance <= radius && distance < closestDistance) {
            closestDistance = distance;
            closestSupermarket = feature;
        }
    });

    if (!closestSupermarket) {
        alert("Tidak ada supermarket yang memenuhi kriteria dalam radius " + radius + " km.");
        return;
    }

    // Tambahkan marker dan rute ke supermarket terdekat
    var closestLat = closestSupermarket.geometry.coordinates[1];
    var closestLon = closestSupermarket.geometry.coordinates[0];
    var name = closestSupermarket.properties.name || supermarketType;
    var parkingInfo = closestSupermarket.properties.has_parking_attendant
        ? "Ada tukang parkir"
        : "Tidak ada tukang parkir";

    // Menampilkan marker
    L.marker([closestLat, closestLon])
        .addTo(supermarketLayer)
        .bindPopup(`<b>${name}</b><br>${parkingInfo}<br>Jarak: ${closestDistance.toFixed(2)} km`);

    // Tambahkan rute dari pengguna ke supermarket terdekat
    addRoute(userLat, userLon, closestLat, closestLon, closestDistance);
}



// Fungsi untuk menambahkan rute dari lokasi pengguna ke lokasi Indomaret
function addRoute(userLat, userLon, targetLat, targetLon, distance) {
    L.Routing.control({
        waypoints: [
            L.latLng(userLat, userLon), // Lokasi pengguna
            L.latLng(targetLat, targetLon) // Lokasi supermarket terdekat
        ],
        routeWhileDragging: true
    }).addTo(peta);

    alert('Rute ditambahkan ke ' + (supermarketType === 'indomaret' ? 'Indomaret' : 'Alfamart') + ' terdekat. Jarak: ' + distance.toFixed(2) + ' km.');
}

var findSupermarketControl = L.Control.extend({
    onAdd: function () {
        var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');

        // Add a label for choosing a supermarket
        var label = L.DomUtil.create('label', 'find-supermarket-label', container);
        label.innerHTML = 'Choose Supermarket:';

        // Add radio button for Indomaret and Alfamart
        var radioIndomaret = L.DomUtil.create('input', '', container);
        radioIndomaret.type = 'radio';
        radioIndomaret.name = 'supermarket';
        radioIndomaret.value = 'indomaret';
        var labelIndomaret = L.DomUtil.create('label', '', container);
        labelIndomaret.innerHTML = 'Indomaret';  // Text next to radio button
        labelIndomaret.style.marginLeft = '10px';

        var radioAlfamart = L.DomUtil.create('input', '', container);
        radioAlfamart.type = 'radio';
        radioAlfamart.name = 'supermarket';
        radioAlfamart.value = 'alfamart';
        var labelAlfamart = L.DomUtil.create('label', '', container);
        labelAlfamart.innerHTML = 'Alfamart';  // Text next to radio button
        labelAlfamart.style.marginLeft = '10px';

        // Add options to choose whether with or without parking attendants
        var parkingLabel = L.DomUtil.create('label', 'parking-label', container);
        parkingLabel.innerHTML = 'Choose Parking Attendant Option:';
        parkingLabel.style.marginTop = '15px';

        var radioWithParking = L.DomUtil.create('input', '', container);
        radioWithParking.type = 'radio';
        radioWithParking.name = 'parking';
        radioWithParking.value = 'with';
        var labelWithParking = L.DomUtil.create('label', '', container);
        labelWithParking.innerHTML = 'With Parking Attendant';  // Text next to radio button
        labelWithParking.style.marginLeft = '10px';

        var radioWithoutParking = L.DomUtil.create('input', '', container);
        radioWithoutParking.type = 'radio';
        radioWithoutParking.name = 'parking';
        radioWithoutParking.value = 'without';
        var labelWithoutParking = L.DomUtil.create('label', '', container);
        labelWithoutParking.innerHTML = 'Without Parking Attendant';  // Text next to radio button
        labelWithoutParking.style.marginLeft = '10px';

        // Add a button to search for the nearest supermarket
        var button = L.DomUtil.create('button', 'find-supermarket-button', container);
        button.innerHTML = 'Find Nearest Supermarket';

        // Event handler untuk tombol pencarian
        L.DomEvent.on(button, 'click', function () {
            if (!navigator.geolocation) {
                alert("Geolocation tidak didukung oleh browser Anda.");
                return;
            }

            // Dapatkan lokasi pengguna
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    var userLat = position.coords.latitude;
                    var userLon = position.coords.longitude;

                    // Ambil opsi yang dipilih: Indomaret atau Alfamart
                    var supermarketOption = document.querySelector('input[name="supermarket"]:checked');
                    var parkingOption = document.querySelector('input[name="parking"]:checked');

                    if (!supermarketOption || !parkingOption) {
                        alert("Silakan pilih supermarket dan opsi tukang parkir.");
                        return;
                    }

                    var supermarketChoice = supermarketOption.value;
                    var parkingChoice = parkingOption.value === 'with';

                    // Panggil fungsi untuk mencari supermarket berdasarkan pilihan
                    findNearestSupermarket(userLat, userLon, supermarketChoice, parkingChoice);
                },
                function () {
                    alert("Gagal mendapatkan lokasi Anda.");
                }
            );
        });

        // Tambahkan elemen ke dalam container
        container.appendChild(radioIndomaret);
        container.appendChild(labelIndomaret);
        container.appendChild(document.createElement('br'));
        container.appendChild(radioAlfamart);
        container.appendChild(labelAlfamart);
        container.appendChild(document.createElement('br'));

        container.appendChild(parkingLabel);
        container.appendChild(radioWithParking);
        container.appendChild(labelWithParking);
        container.appendChild(document.createElement('br'));
        container.appendChild(radioWithoutParking);
        container.appendChild(labelWithoutParking);

        container.appendChild(button);

        return container;
    }
});

// Tambahkan kontrol ke peta
peta.addControl(new findSupermarketControl({ position: 'topleft' }));




var parkingOptionControl = L.Control.extend({
    onAdd: function () {
        var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');

        // Tombol untuk supermarket dengan tukang parkir
        var buttonWith = L.DomUtil.create('button', 'find-parking-button', container);
        buttonWith.innerHTML = 'Dengan Tukang Parkir';
        buttonWith.style.margin = '5px';

        // Tombol untuk supermarket tanpa tukang parkir
        var buttonWithout = L.DomUtil.create('button', 'find-no-parking-button', container);
        buttonWithout.innerHTML = 'Tanpa Tukang Parkir';
        buttonWithout.style.margin = '5px';

        // Event handler untuk tombol
        L.DomEvent.on(buttonWith, 'click', function () {
            navigator.geolocation.getCurrentPosition(function (position) {
                var userLat = position.coords.latitude;
                var userLon = position.coords.longitude;

                // Cari supermarket dengan tukang parkir
                findNearestSupermarketWithParkingOption(userLat, userLon, true);
            });
        });

        L.DomEvent.on(buttonWithout, 'click', function () {
            navigator.geolocation.getCurrentPosition(function (position) {
                var userLat = position.coords.latitude;
                var userLon = position.coords.longitude;

                // Cari supermarket tanpa tukang parkir
                findNearestSupermarketWithParkingOption(userLat, userLon, false);
            });
        });

        return container;
    }
});

// Muat data GeoJSON Indomaret
var indomaretData = null;
fetch('Indomaret.geojson') // Path ke file GeoJSON Indomaret
    .then(response => response.json())
    .then(data => {
        console.log('Indomaret GeoJSON:', data);
        indomaretData = data; // Simpan data GeoJSON Indomaret
    })
    .catch(error => console.error('Error loading Indomaret GeoJSON data:', error));

// Muat data GeoJSON Alfamart
var alfamartData = null;
fetch('Alfamart.geojson') // Path ke file GeoJSON Alfamart
    .then(response => response.json())
    .then(data => {
        alfamartData = data; // Simpan data GeoJSON Alfamart
    })
    .catch(error => console.error('Error loading Alfamart GeoJSON data:', error));

// Fungsi untuk memeriksa apakah koordinat berada di dalam bounding box
function isWithinBounds(lat, lon, bounds) {
    return (
        lat >= bounds.southWest[0] &&
        lat <= bounds.northEast[0] &&
        lon >= bounds.southWest[1] &&
        lon <= bounds.northEast[1]
    );
}

// Fungsi untuk menambahkan marker dari data GeoJSON ke layer
function addMarkersToLayer(data, layer) {
    data.features.forEach(function (feature) {
        var lat = feature.geometry.coordinates[1]; // Latitude
        var lon = feature.geometry.coordinates[0]; // Longitude
        var name = feature.properties.name || "";
        var hasParkingAttendant = feature.properties.has_parking_attendant;

        // Jika berada dalam batas Bandung, tambahkan marker
        if (isWithinBounds(lat, lon, bandungBounds)) {
            var marker = L.marker([lat, lon]).addTo(layer);
            
            // Tampilkan informasi tukang parkir di popup
            var parkingInfo = hasParkingAttendant
                ? "Ada tukang parkir"
                : "Tidak ada tukang parkir";

            marker.bindPopup(`<b>${name}</b><br>${parkingInfo}`);
        }
    });
}

function filterByParkingAttendant(layer, data, showOnlyWithAttendant) {
    layer.clearLayers(); // Hapus marker sebelumnya
    var filteredData = data.features.filter(function (feature) {
        return showOnlyWithAttendant 
            ? feature.properties.has_parking_attendant === true 
            : true;
    });
    addMarkersToLayer({ features: filteredData }, layer);
}

// Fungsi pencarian Supermarket (Indomaret atau Alfamart) di Bandung berdasarkan query
function searchSupermarketInBandung(query, showOnlyWithAttendant = false) {
    if (!indomaretData || !alfamartData) {
        alert("Data Supermarket belum dimuat. Silakan coba lagi.");
        return;
    }

    indomaretLayer.clearLayers();
    alfamartLayer.clearLayers();

    var filteredIndomaret = indomaretData.features.filter(function (feature) {
        var name = feature.properties.name || "";
        var hasParkingAttendant = feature.properties.has_parking_attendant;
        return (
            name.toLowerCase().includes(query.toLowerCase()) &&
            (!showOnlyWithAttendant || hasParkingAttendant)
        );
    });

    var filteredAlfamart = alfamartData.features.filter(function (feature) {
        var name = feature.properties.name || "";
        var hasParkingAttendant = feature.properties.has_parking_attendant;
        return (
            name.toLowerCase().includes(query.toLowerCase()) &&
            (!showOnlyWithAttendant || hasParkingAttendant)
        );
    });

    if (filteredIndomaret.length > 0) {
        addMarkersToLayer({ features: filteredIndomaret }, indomaretLayer);
    }

    if (filteredAlfamart.length > 0) {
        addMarkersToLayer({ features: filteredAlfamart }, alfamartLayer);
    }

    if (filteredIndomaret.length > 0 || filteredAlfamart.length > 0) {
        var firstFeature = filteredIndomaret[0] || filteredAlfamart[0];
        var firstLatLon = [
            firstFeature.geometry.coordinates[1],
            firstFeature.geometry.coordinates[0]
        ];
        peta.setView(firstLatLon, 13);
    } else {
        alert("Tidak ada lokasi ditemukan di wilayah Bandung.");
    }
}

// Menambahkan fitur search bar khusus untuk mencari Indomaret atau Alfamart di Bandung
var customSearchControl = L.Control.extend({
    onAdd: function () {
        var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');

        // Buat input untuk search bar
        var input = L.DomUtil.create('input', 'search-bar', container);
        input.type = 'text';
        input.placeholder = 'Cari Indomaret atau Alfamart di Bandung...';

        // Tambahkan tombol cari
        var button = L.DomUtil.create('button', 'search-button', container);
        button.innerHTML = '🔍';

        // Tambahkan event handler untuk pencarian
        L.DomEvent.on(button, 'click', function () {
            var query = input.value.trim();
            if (query.toLowerCase().includes("indomaret") || query.toLowerCase().includes("alfamart")) {
                searchSupermarketInBandung(query);
            } else {
                alert("Hanya bisa mencari Indomaret atau Alfamart di Bandung.");
            }
        });

        return container;
    }
});

// Tambahkan search bar ke peta
peta.addControl(new customSearchControl({ position: 'topleft' }));

// Menambahkan kontrol layer
var overlaymaps = {
    "Indomaret": indomaretLayer,
    "Alfamart": alfamartLayer
};
L.control.layers(null, overlaymaps, { position: 'topright', collapsed: false }).addTo(peta);