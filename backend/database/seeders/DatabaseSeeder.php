<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            OwnerSeeder::class,
            ScheduleSeeder::class,
            PriceSeeder::class,
            ParkingSeeder::class,
            FloorSeeder::class,
            SpotSeeder::class,
            UserSeeder::class,
        ]);
    }
}
