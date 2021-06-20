<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class CreateStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Student::factory()->count(50)->create(); 
    }
}
