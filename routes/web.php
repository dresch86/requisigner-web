<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@home')->name('get-home');

Route::middleware(['guest'])->group(function () {
    Route::get('login', 'LoginController@index')->name('get-login');
    Route::post('login', 'LoginController@login')->name('post-login');
});

Route::middleware(['auth', 'suspension'])->group(function () {
    Route::get('logout', 'LoginController@logout')->name('logout');
    
    Route::get('documents/tools', 'DocumentsController@tools')->name('get-docs-tools');
    Route::get('documents/library', 'DocumentsController@library')->name('get-docs-lib');
    Route::get('documents/upload', 'DocumentsController@upload')->name('get-docs-upload');
    Route::get('documents/signing', 'DocumentsController@signing')->name('get-docs-signing');

    Route::get('profile', 'ProfileController@profile')->name('get-profile');
    Route::get('signatures', 'SignatureController@signatures')->name('get-signatures');
});

Route::middleware(['auth', 'suspension', 'superadmin'])->group(function () {
    
});