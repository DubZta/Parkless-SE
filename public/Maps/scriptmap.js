// Batas wilayah Jakarta
var jakartaBounds = L.latLngBounds(
    L.latLng(-6.395, 106.689), // southWest
    L.latLng(-5.955, 107.0885) // northEast
);

// Inisialisasi peta
var peta = L.map("map", {
    center: [-6.2146, 106.8451], // Pusat Jakarta
    zoom: 14,
});

// Tambahkan tile layer
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(peta);

// Layer Group untuk marker
var indomaretLayer = L.layerGroup().addTo(peta);
var alfamartLayer = L.layerGroup().addTo(peta);

// Variabel data gabungan
var combinedData;

// Fungsi pengecekan lokasi di Jakarta
function isWithinJakarta(lat, lon) {
    return jakartaBounds.contains(L.latLng(lat, lon));
}

// Geolokasi pengguna
document.addEventListener("DOMContentLoaded", () => {
    if (!navigator.geolocation) {
        alert("Geolokasi tidak didukung oleh browser Anda.");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function (position) {
            const userLat = position.coords.latitude;
            const userLon = position.coords.longitude;
            console.log(userLat, userLon);

            // Pindahkan peta ke lokasi pengguna
            peta.setView([userLat, userLon], 15);

            // Tambahkan marker lokasi
            L.marker([userLat, userLon])
                .addTo(peta)
                .bindPopup("Lokasi Anda")
                .openPopup();
        },
        function (error) {
            alert("Tidak dapat mengakses lokasi Anda.");
        }
    );
});

let userMarker = null;
let userCircle = null;
let isFindNearMe = false;

document.getElementById("locate-button").addEventListener("click", function () {
    const button = this;
    if (!navigator.geolocation) {
        alert("Geolokasi tidak didukung oleh browser Anda.");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function (position) {
            try {
                var userLat = position.coords.latitude;
                var userLon = position.coords.longitude;

                if (!isWithinJakarta(userLat, userLon)) {
                    alert("Anda berada di luar Jakarta!");
                    return;
                }

                if (!isFindNearMe) {
                    if (userMarker) userMarker.remove();

                    if (userCircle) userCircle.remove();

                    // Tambahkan marker lokasi pengguna
                    userMarker = L.marker([userLat, userLon])
                        .addTo(peta)
                        .bindPopup("Lokasi Anda")
                        .openPopup();

                    userCircle = L.circle([userLat, userLon], {
                        radius: 2000,
                        color: "black",
                        opacity: 0.25,
                    }).addTo(peta);

                    // Cari toko terdekat
                    findNearbyStores(userLat, userLon);

                    button.textContent = "Show All Supermarkets";
                    isFindNearMe = true;
                } else {
                    indomaretLayer.clearLayers();
                    alfamartLayer.clearLayers();

                    processStoreData(
                        userLat,
                        userLon,
                        "Indomaret",
                        indomaretLayer
                    );
                    processStoreData(
                        userLat,
                        userLon,
                        "Alfamart",
                        indomaretLayer
                    );
                    button.textContent = "Find Supermarkets Near Me";
                    isFindNearMe = false;
                }
            } catch (error) {
                alert("Terjadi kesalahan saat memproses lokasi.");
            }
        },
        function (error) {
            alert("Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.");
        }
    );
});

// Fungsi pencarian toko terdekat
function findNearbyStores(userLat, userLon) {
    const radius = 2;
    let found = false;
    indomaretLayer.clearLayers();
    alfamartLayer.clearLayers();
    processStoreData(userLat, userLon, "Indomaret", indomaretLayer, radius);
    processStoreData(userLat, userLon, "Alfamart", alfamartLayer, radius);
}

function processStoreData(userLat, userLon, brand, layer, radius = -1) {
    let found = false;

    combinedData.features
        .filter((feature) => feature.properties.brand === brand)
        .forEach((feature) => {
            var [lon, lat] = feature.geometry.coordinates;
            const distance = calculateDistance(userLat, userLon, lat, lon);

            if (radius != -1) {
                if (distance <= radius && isWithinJakarta(lat, lon)) {
                    addMarker(feature, layer, distance);
                    found = true;
                }
            } else {
                if (userMarker) {
                    userMarker.remove();
                }

                if (userCircle) {
                    userCircle.remove();
                }
                const layer =
                    feature.properties.brand === "Indomaret"
                        ? indomaretLayer
                        : alfamartLayer;
                addMarker(feature, layer, distance);
                found = true;
            }
        });

    if (!found) alert(`Tidak ada ${brand} dalam radius ${radius} km.`);
}

// Fungsi pembantu
function addMarker(feature, layer, distance) {
    var [lon, lat] = feature.geometry.coordinates;
    var name = feature.properties.name || feature.properties.brand;

    const placeID = parseInt(feature.id.split("/")[1]);

    L.marker([lat, lon])
        .addTo(layer)
        .bindPopup(
            `<b>${name}</b></span><br>Jarak: ${distance.toFixed(
                2
            )} km<br><a href='/review/${encodeURIComponent(
                placeID
            )}' target='_blank'>Lihat detail & review</a>`
        );
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    var R = 6371;
    var dLat = ((lat2 - lat1) * Math.PI) / 180;
    var dLon = ((lon2 - lon1) * Math.PI) / 180;
    var a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos((lat1 * Math.PI) / 180) *
            Math.cos((lat2 * Math.PI) / 180) *
            Math.sin(dLon / 2) *
            Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

// Kontrol Pencarian
var searchControl = L.Control.extend({
    onAdd: function () {
        var container = L.DomUtil.create("div", "leaflet-bar leaflet-control");

        var input = L.DomUtil.create("input", "search-input");
        input.placeholder = "Cari brand di Jakarta...";

        var button = L.DomUtil.create("button", "search-btn");
        button.innerHTML = "🔍";

        L.DomEvent.on(button, "click", () => {
            var query = input.value.toLowerCase();
            var results = combinedData.features.filter((feature) => {
                var props = feature.properties;
                var [lon, lat] = feature.geometry.coordinates;

                var valuesToSearch = [
                    props.name,
                    props.brand,
                    props.operator,
                    props.phone,
                    props["addr:street"],
                    props["addr:housenumber"],
                    lon.toString(),
                    lat.toString(),
                ];

                return (
                    valuesToSearch.some((val) =>
                        val?.toLowerCase().includes(query)
                    ) && isWithinJakarta(lat, lon)
                );
            });

            if (results.length > 0) {
                indomaretLayer.clearLayers();
                alfamartLayer.clearLayers();
                results.forEach((feature) => {
                    var layer =
                        feature.properties.brand === "Indomaret"
                            ? indomaretLayer
                            : alfamartLayer;
                    addMarker(feature, layer, 0);
                });
            } else {
                alert("Lokasi tidak ditemukan!");
            }
        });

        container.appendChild(input);
        container.appendChild(button);
        return container;
    },
});

// Load GeoJSON Data
fetch("indo_alfa.geojson")
    .then((res) => res.json())
    .then((data) => {
        combinedData = data;
        data.features.forEach((feature) => {
            var layer =
                feature.properties.brand === "Indomaret"
                    ? indomaretLayer
                    : alfamartLayer;
            addMarker(feature, layer, 0);
        });
    })
    .catch((error) => console.error("Error loading data:", error));

// Tambahkan kontrol brand ke peta
peta.addControl(new searchControl({ position: "topleft" }));
L.control
    .layers(
        null,
        {
            Indomaret: indomaretLayer,
            Alfamart: alfamartLayer,
        },
        { position: "topright" }
    )
    .addTo(peta);
