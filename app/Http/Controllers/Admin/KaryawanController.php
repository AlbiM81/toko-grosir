<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = User::query()
            ->where('role', 'karyawan')
            ->latest()
            ->paginate(10);

        return view('admin.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('admin.karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'karyawan',
        ]);

        if (! Role::where('name', 'karyawan')->where('guard_name', 'web')->exists()) {
            Role::create(['name' => 'karyawan', 'guard_name' => 'web']);
        }

        $user->assignRole('karyawan');

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Akun karyawan berhasil dibuat.');
    }

    public function edit(User $karyawan)
    {
        abort_if($karyawan->role !== 'karyawan', 404);
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, User $karyawan)
    {
        abort_if($karyawan->role !== 'karyawan', 404);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $karyawan->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $karyawan->update($data);

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diupdate.');
    }

    public function destroy(User $karyawan)
    {
        abort_if($karyawan->role !== 'karyawan', 404);

        $karyawan->forceDelete();

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Akun karyawan berhasil dihapus.');
    }
}