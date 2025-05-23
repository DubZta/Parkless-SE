<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supermarket;

class SupermarketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = public_path('indo_alfa.geojson');
        $geojson = json_decode(file_get_contents($filePath), true);

        foreach ($geojson['features'] as $feature) {
            $props = $feature['properties'];
            $coords = $feature['geometry']['coordinates'];

            Supermarket::create([
                'external_id' => explode('/', $feature['id'])[1],
                'name' => $props['name'] ?? null,
                'brand' => $props['brand'] ?? null,
                'operator' => $props['operator'] ?? null,
                'phone' => $props['phone'] ?? null,
                'branch' => $props['branch'] ?? null,
                'latitude' => $coords[1],
                'longitude' => $coords[0],
                'has_parking_attendant' => (bool) random_int(0, 1),
            ]);
        }
    }
}
