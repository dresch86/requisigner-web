<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function settings_form(Request $request) {
        
    }

    public function settings_store(Request $request) {
        
    }

    public function group_store(Request $request) {
        
    }

    public function group_form(Request $request) {
        return view('superadmin.groups.create', ['menuItem' => 'admin_tools']);
    }

    public function user_store(Request $request) {
        
    }

    public function user_form(Request $request) {
        return view('superadmin.users.create', ['menuItem' => 'admin_tools']);
    }

    public function tools(Request $request) {
        return view('superadmin.tools', ['menuItem' => 'admin_tools']);
    }
}
