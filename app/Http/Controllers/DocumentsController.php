<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function tools(Request $request) {
        return view('documents.tools', ['menuItem' => 'docs_tools']);
    }

    public function signing(Request $request) {
        return view('documents.signing-list', ['menuItem' => 'docs_tools']);
    }

    public function library(Request $request) {
        return view('documents.library-view', ['menuItem' => 'docs_tools']);
    }

    public function upload(Request $request) {
        return view('documents.upload', ['menuItem' => 'docs_tools']);
    }
}