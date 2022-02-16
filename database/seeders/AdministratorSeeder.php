<?php

namespace Database\Seeders;

use Hash;
use App\Models\User;
use Illuminate\Database\Seeder;

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

        echo 'Admin password: ' . $password . "\n";
    }
}
