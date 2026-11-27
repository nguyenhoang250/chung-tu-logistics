<?php

namespace Database\Seeders;

use App\Enums\VaiTro;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Quản trị viên',
                'password' => 'password',
                'role' => VaiTro::Admin,
                'dang_hoat_dong' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'ly@example.com'],
            [
                'name' => 'Chị Ly',
                'password' => 'password',
                'role' => VaiTro::NhanVien,
                'dang_hoat_dong' => true,
            ]
        );
        $this->call(LoaiChungTuSeeder::class);
        $this->call(MauChecklistSeeder::class);
    }
}