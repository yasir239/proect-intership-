<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Student::create([
            'name' => 'Ahmed',
            'username' => 'ahmed',
            'password' => '123456',
        ]);

        Student::create([
            'name' => 'Saeed',
            'username' => 'saeed',
            'password' => '123456',
        ]);
    }
}
