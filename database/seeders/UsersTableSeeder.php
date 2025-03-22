<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'first_name' => 'Admin',
                'last_name' => null,
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'first_name' => 'Student',
                'last_name' => null,
                'email' => 'student@student.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'student',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'first_name' => 'Instructor',
                'last_name' => null,
                'email' => 'instructor@instructor.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'instructor',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            
        ]);
    }
}
