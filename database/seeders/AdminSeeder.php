<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tài khoản admin mới
        User::create([
            'name' => 'Admin Hub',
            'email' => 'admin@hub.com',
            'password' => Hash::make('admin2233'),
            'role' => 'admin',
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        echo "Admin account created: admin@hub.com / admin2233\n";
    }
}
