<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AccessControlSeeder::class,
            DepartmentSeeder::class,
            CreateAdminUserSeeder::class,
            WalkingJourneySeeder::class,
            WalkingLevelSeeder::class,
            WalkingPointRuleSeeder::class,
            WalkingAchievementSeeder::class,
            WalkingMentorResponseTemplateSeeder::class,
            WalkingPermissionSeeder::class,
        ]);
    }
}
