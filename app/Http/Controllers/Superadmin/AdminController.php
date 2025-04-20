<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'Admin')->get();
        return view('superadmin.admins.index', compact('admins'));
    }
}
