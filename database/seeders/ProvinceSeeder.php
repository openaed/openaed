<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;

class ProvinceSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Province::create([
            'name' => 'Groningen',
            'latitude' => 53.3439871,
            'longitude' => 6.7651259,
            'zoom' => 9
        ]);

        Province::create([
            'name' => 'Friesland',
            'latitude' => 53.0923689,
            'longitude' => 5.7770430,
            'zoom' => 9
        ]);

        Province::create([
            'name' => 'Drenthe',
            'latitude' => 52.9067922,
            'longitude' => 6.6368423,
            'zoom' => 9
        ]);

        Province::create([
            'name' => 'Overijssel',
            'latitude' => 52.4254143,
            'longitude' => 6.4610611,
            'zoom' => 9
        ]);

        Province::create([
            'name' => 'Flevoland',
            'latitude' => 52.4484375,
            'longitude' => 5.4235397,
            'zoom' => 10
        ]);

        Province::create([
            'name' => 'Gelderland',
            'latitude' => 52.1014041,
            'longitude' => 5.9515701,
            'zoom' => 9
        ]);

        Province::create([
            'name' => 'Utrecht',
            'latitude' => 52.0454749,
            'longitude' => 5.1841279,
            'zoom' => 10
        ]);

        Province::create([
            'name' => 'Noord-Holland',
            'latitude' => 52.7212825,
            'longitude' => 4.8206650,
            'zoom' => 9
        ]);

        Province::create([
            'name' => 'Zuid-Holland',
            'latitude' => 51.9966792,
            'longitude' => 4.5597397,
            'zoom' => 10
        ]);

        Province::create([
            'name' => 'Zeeland',
            'latitude' => 51.4162975,
            'longitude' => 3.7028061,
            'zoom' => 10
        ]);

        Province::create([
            'name' => 'Noord-Brabant',
            'latitude' => 51.6017723,
            'longitude' => 5.4441391,
            'zoom' => 9
        ]);

        Province::create([
            'name' => 'Limburg',
            'latitude' => 51.2015196,
            'longitude' => 5.9046302,
            'zoom' => 9
        ]);
    }
}