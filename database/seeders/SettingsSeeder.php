<?php

namespace Database\Seeders;

use Carbon\Carbon;

use App\Models\Settings;

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        Settings::insert([
            ['setting' => 'org_name', 'value' => 'Requisigner Org', 'created_at' => $now, 'updated_at' => $now],
            ['setting' => 'admin_email', 'value' => 'admin@myorg.com', 'created_at' => $now, 'updated_at' => $now]
        ]);
    }
}
