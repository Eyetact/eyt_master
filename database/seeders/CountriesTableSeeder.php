<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            // Asia
            ['name' => 'India', 'region' => 'Asia'],
            ['name' => 'China', 'region' => 'Asia'],
            ['name' => 'Japan', 'region' => 'Asia'],
            ['name' => 'South Korea', 'region' => 'Asia'],
            ['name' => 'Indonesia', 'region' => 'Asia'],
            ['name' => 'Thailand', 'region' => 'Asia'],
            ['name' => 'Malaysia', 'region' => 'Asia'],
            ['name' => 'Pakistan', 'region' => 'Asia'],
            ['name' => 'Bangladesh', 'region' => 'Asia'],
            ['name' => 'Sri Lanka', 'region' => 'Asia'],

            // Europe
            ['name' => 'United Kingdom', 'region' => 'Europe'],
            ['name' => 'Germany', 'region' => 'Europe'],
            ['name' => 'France', 'region' => 'Europe'],
            ['name' => 'Italy', 'region' => 'Europe'],
            ['name' => 'Spain', 'region' => 'Europe'],
            ['name' => 'Poland', 'region' => 'Europe'],
            ['name' => 'Sweden', 'region' => 'Europe'],
            ['name' => 'Norway', 'region' => 'Europe'],
            ['name' => 'Greece', 'region' => 'Europe'],
            ['name' => 'Russia', 'region' => 'Europe'],

            // Africa
            ['name' => 'Nigeria', 'region' => 'Africa'],
            ['name' => 'South Africa', 'region' => 'Africa'],
            ['name' => 'Egypt', 'region' => 'Africa'],
            ['name' => 'Kenya', 'region' => 'Africa'],
            ['name' => 'Ghana', 'region' => 'Africa'],
            ['name' => 'Ethiopia', 'region' => 'Africa'],
            ['name' => 'Morocco', 'region' => 'Africa'],
            ['name' => 'Algeria', 'region' => 'Africa'],
            ['name' => 'Tanzania', 'region' => 'Africa'],
            ['name' => 'Uganda', 'region' => 'Africa'],

            // Americas
            ['name' => 'United States', 'region' => 'Americas'],
            ['name' => 'Canada', 'region' => 'Americas'],
            ['name' => 'Brazil', 'region' => 'Americas'],
            ['name' => 'Argentina', 'region' => 'Americas'],
            ['name' => 'Mexico', 'region' => 'Americas'],
            ['name' => 'Chile', 'region' => 'Americas'],
            ['name' => 'Colombia', 'region' => 'Americas'],
            ['name' => 'Peru', 'region' => 'Americas'],
            ['name' => 'Venezuela', 'region' => 'Americas'],
            ['name' => 'Cuba', 'region' => 'Americas'],

            // Oceania
            ['name' => 'Australia', 'region' => 'Oceania'],
            ['name' => 'New Zealand', 'region' => 'Oceania'],
            ['name' => 'Fiji', 'region' => 'Oceania'],
            ['name' => 'Papua New Guinea', 'region' => 'Oceania'],
            ['name' => 'Samoa', 'region' => 'Oceania'],
            ['name' => 'Tonga', 'region' => 'Oceania'],
            ['name' => 'Micronesia', 'region' => 'Oceania'],
            ['name' => 'Palau', 'region' => 'Oceania'],
            ['name' => 'Vanuatu', 'region' => 'Oceania'],
            ['name' => 'Solomon Islands', 'region' => 'Oceania'],

            // Middle East
            ['name' => 'Saudi Arabia', 'region' => 'Middle East'],
            ['name' => 'United Arab Emirates', 'region' => 'Middle East'],
            ['name' => 'Israel', 'region' => 'Middle East'],
            ['name' => 'Turkey', 'region' => 'Middle East'],
            ['name' => 'Iran', 'region' => 'Middle East'],
            ['name' => 'Iraq', 'region' => 'Middle East'],
            ['name' => 'Syria', 'region' => 'Middle East'],
            ['name' => 'Lebanon', 'region' => 'Middle East'],
            ['name' => 'Jordan', 'region' => 'Middle East'],
            ['name' => 'Qatar', 'region' => 'Middle East'],
        ];

        DB::table('countries_region')->insert($countries);
    }
}
