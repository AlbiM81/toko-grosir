<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class PembeliController extends Controller
{
    public function index()
    {
        $pembeli = User::where('role', 'pembeli')->latest()->paginate(15);
        return view('admin.pembeli.index', compact('pembeli'));
    }
}