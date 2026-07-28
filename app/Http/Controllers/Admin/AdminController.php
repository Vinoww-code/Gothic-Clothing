<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Mengarahkan ke file view dashboard admin kamu
        return view('admin.dashboard'); 
    }
}