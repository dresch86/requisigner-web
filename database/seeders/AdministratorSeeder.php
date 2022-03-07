<?php

namespace Database\Seeders;

use Hash;
use App\Models\User;
use App\Models\Signature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = uniqid('Test.');
        $user = User::create([
            'name' => 'Administrator',
            'username' => 'Administrator',
            'password' => Hash::make($password),
            'email' => 'administrator@mybusiness.com',
            'superadmin' => 1,
            'suspended' => 0
        ]);

        $user->save();

        $sig_directory = 'private/signatures/' . $user->id;
        Storage::disk('local')->makeDirectory($sig_directory);

        $signature = Signature::create([
            'user_id' => $user->id
        ]);
        $signature->save();

        echo 'Admin password: ' . $password . "\n";
    }
}
