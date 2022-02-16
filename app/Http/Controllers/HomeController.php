<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request) {
        if (!Auth::check()) {
            return redirect()->route('get-login');
        } else {
            return view('home.index', ['menuItem' => 'home']);
        }
    }
}
