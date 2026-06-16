<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

it('assigns the karyawan role when an admin creates an employee account', function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    Role::create(['name' => 'admin', 'guard_name' => 'web']);
    Role::create(['name' => 'karyawan', 'guard_name' => 'web']);

    $admin = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
    $admin->assignRole('admin');

    $response = $this->actingAs($admin)->post('/admin/karyawan', [
        'name' => 'Karyawan Baru',
        'email' => 'karyawan-baru@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('admin.karyawan.index'));

    $karyawan = User::where('email', 'karyawan-baru@example.com')->firstOrFail();

    expect($karyawan->role)->toBe('karyawan');
    expect($karyawan->hasRole('karyawan'))->toBeTrue();
});
