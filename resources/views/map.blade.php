<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Parkless Parking Locator</title>
    <!-- Menggunakan Blade's asset helper untuk mengarahkan ke file CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('Maps/stylemap.css') }}">
</head>

<body>
    <div id="map"></div>
    <button id="locate-button">Find Supermarkets Near Me</button>
    <button id="back-button">Back To Home Page</button>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <!-- Menambahkan script.js dengan menggunakan Blade asset helper -->
    <script src="{{ asset('Maps/scriptmap.js') }}"></script>

    <script>
        // Tombol back untuk kembali ke halaman index.blade.php
        document.getElementById('back-button').addEventListener('click', function() {
            window.location.href = '{{ route('index') }}'; // Mengarah ke rute dengan nama 'index'
        });
    </script>
</body>

</html>
