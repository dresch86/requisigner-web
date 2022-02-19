<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request) {
        
    }

    public function profile(Request $request) {
        return view('profile', ['menuItem' => 'profile']);
    }
}
