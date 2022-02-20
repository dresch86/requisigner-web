<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::select('id')->limit(1)->first();
        $group = Group::create([
            'name' => 'Root',
            'parent_id' => null,
            'manager_id' => $user->id,
            'description' => 'The default system group'
        ]);

        $group->save();
        $user->group_id = $group->id;
        $user->save();
    }
}
