<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstructorPaymentSeeder extends Seeder
{
    public function run()
    {
        $instructors = DB::table('instructors')->pluck('id');

        if ($instructors->isEmpty()) {
            return;
        }

        foreach ($instructors as $instructorId) {
            for ($i = 0; $i < 4; $i++) {
                $paymentDate = Carbon::now()->subMonths($i);

                DB::table('instructor_payments')->insert([
                    'instructor_id' => $instructorId,
                    'price' => rand(50, 500),
                    'created_at' => $paymentDate,
                    'updated_at' => $paymentDate,
                ]);
            }
        }
    }
}
