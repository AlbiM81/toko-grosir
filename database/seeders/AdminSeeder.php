<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Admin
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@tokokgrosir.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Buat Karyawan
        User::create([
            'name'     => 'Karyawan Satu',
            'email'    => 'karyawan@tokokgrosir.com',
            'password' => Hash::make('password'),
            'role'     => 'karyawan',
        ]);

        // Buat Pembeli Demo
        User::create([
            'name'     => 'Pembeli Demo',
            'email'    => 'pembeli@tokokgrosir.com',
            'password' => Hash::make('password'),
            'role'     => 'pembeli',
        ]);

        // Buat Kategori
        $categories = ['Beras & Sembako', 'Minyak & Lemak', 'Gula & Pemanis', 'Bumbu & Rempah', 'Minuman'];
        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }
    }
}