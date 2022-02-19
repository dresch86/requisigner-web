<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function tools(Request $request) {
        return view('superadmin.tools', ['menuItem' => 'admin_tools']);
    }
}
