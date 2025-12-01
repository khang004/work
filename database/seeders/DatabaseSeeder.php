<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Tạo tài khoản Employer mẫu
        User::create([
            'name' => 'Nhà tuyển dụng',
            'email' => 'employer@example.com',
            'password' => Hash::make('password'),
            'role' => 'employer',
            'phone' => '0123456789',
        ]);

        // Tạo tài khoản Candidate mẫu
        User::create([
            'name' => 'Ứng viên',
            'email' => 'candidate@example.com',
            'password' => Hash::make('password'),
            'role' => 'candidate',
            'phone' => '0987654321',
        ]);

        // Gọi các seeder khác
        $this->call([
            CategorySeeder::class,
            LocationSeeder::class,
            SkillSeeder::class,
        ]);
    }
}
