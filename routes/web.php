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
    Route::get('documents/signing', 'DocumentsController@signing')->name('get-docs-signing');

    Route::get('templates', 'DocumentsController@templates')->name('get-templates');
    Route::get('templates/form', 'DocumentsController@template_form')->name('get-template-form');
    Route::post('templates/form', 'DocumentsController@template_store')->name('post-template-form');

    Route::get('profile', 'ProfileController@profile')->name('get-profile');
    Route::post('profile', 'ProfileController@update')->name('post-profile');

    Route::get('signatures', 'SignatureController@signatures')->name('get-signatures');
    Route::post('signatures', 'SignatureController@update')->name('post-signatures');
});

Route::middleware(['auth', 'suspension', 'superadmin'])->group(function () {
    Route::get('superadmin/tools', 'AdminController@tools')->name('get-admin-tools');

    Route::get('superadmin/settings', 'AdminController@settings_form')->name('get-settings-form');
    Route::post('superadmin/settings', 'AdminController@settings_store')->name('post-settings-form');

    Route::get('superadmin/users', 'AdminController@users')->name('get-users');
    Route::get('superadmin/users/{id}', 'AdminController@user')->whereNumber('id')
    ->name('get-user-by-id');

    Route::post('superadmin/users/delete', 'AdminController@user_delete')->name('post-delete-user');
    Route::get('superadmin/users/create', 'AdminController@user_form')->name('get-create-user-form');
    Route::post('superadmin/users/create', 'AdminController@user_store')->name('post-create-user-form');

    Route::get('superadmin/groups', 'AdminController@groups')->name('get-groups');
    Route::get('superadmin/groups/{id}', 'AdminController@group')->whereNumber('id')
    ->name('get-group-by-id');

    Route::get('superadmin/groups/create', 'AdminController@group_form')->name('get-create-group-form');
    Route::post('superadmin/groups/create', 'AdminController@group_store')->name('post-create-group-form');
});