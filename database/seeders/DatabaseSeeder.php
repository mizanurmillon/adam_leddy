<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(InstructorSeeder::class);
        $this->call(CoursesSeeder::class);
        $this->call(CourseWatchSeeder::class);
        $this->call(SubscriptionSeeder::class);
        $this->call(MembershipSeeder::class);
        $this->call(InstructorPaymentSeeder::class);
        $this->call(CourseWatchHistorySeeder::class);
    }
}
