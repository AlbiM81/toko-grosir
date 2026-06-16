<?php
// database/seeders/RoleAndPermissionSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── BUAT PERMISSIONS ──────────────────────────────────────────
        $permissions = [
            // Produk
            'produk.lihat',
            'produk.tambah',
            'produk.edit',
            'produk.hapus',
            'produk.upload-foto',

            // Kategori
            'kategori.lihat',
            'kategori.tambah',
            'kategori.edit',
            'kategori.hapus',

            // Pesanan
            'pesanan.lihat-semua',
            'pesanan.lihat-sendiri',
            'pesanan.verifikasi',
            'pesanan.proses',
            'pesanan.kirim',
            'pesanan.selesai',
            'pesanan.checkout',

            // Pembayaran
            'pembayaran.upload-bukti',

            // Laporan
            'laporan.lihat',
            'laporan.export',

            // User management
            'karyawan.kelola',
            'pembeli.lihat',

            // Keranjang
            'keranjang.kelola',
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm, 'guard_name' => 'web']);
        }

        // ── BUAT ROLES & ASSIGN PERMISSIONS ──────────────────────────

        // Role: Admin
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        // Role: Karyawan
        $karyawan = Role::create(['name' => 'karyawan', 'guard_name' => 'web']);
        $karyawan->givePermissionTo([
            'produk.lihat',
            'produk.tambah',
            'produk.edit',
            'produk.hapus',
            'produk.upload-foto',
            'pesanan.lihat-semua',
            'pesanan.verifikasi',
            'pesanan.proses',
            'pesanan.kirim',
            'pesanan.selesai',
        ]);

        // Role: Pembeli
        $pembeli = Role::create(['name' => 'pembeli', 'guard_name' => 'web']);
        $pembeli->givePermissionTo([
            'produk.lihat',
            'pesanan.lihat-sendiri',
            'pesanan.checkout',
            'pembayaran.upload-bukti',
            'keranjang.kelola',
        ]);

        // ── BUAT USER DEFAULT ─────────────────────────────────────────

        $adminUser = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@tokogrosir.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);
        $adminUser->assignRole('admin');

        $karyawanUser = User::create([
            'name'     => 'Karyawan Satu',
            'email'    => 'karyawan@tokogrosir.com',
            'password' => Hash::make('password'),
            'role'     => 'karyawan',
        ]);
        $karyawanUser->assignRole('karyawan');

        $pembeliUser = User::create([
            'name'     => 'Pembeli Demo',
            'email'    => 'pembeli@tokogrosir.com',
            'password' => Hash::make('password'),
            'role'     => 'pembeli',
        ]);
        $pembeliUser->assignRole('pembeli');
    }
}