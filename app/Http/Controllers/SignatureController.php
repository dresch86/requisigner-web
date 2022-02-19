<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public function update(Request $request) {

    }

    public function signatures() {
        return view('signatures.index', ['menuItem' => 'my_sigs']);
    }
}