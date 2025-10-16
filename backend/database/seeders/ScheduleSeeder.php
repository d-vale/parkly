<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $schedules = [
            ['schedule_type' => '24/7'],
            ['schedule_type' => '6am-7pm'],
            ['schedule_type' => '7am-6pm'],
            ['schedule_type' => '8am-5pm'],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }

        $this->command->info('✓ Horaires créés avec succès');
    }
}
