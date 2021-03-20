<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->times(10)->create();
        $users = \App\User::whereType(\App\Models\Common::TYPE_XM)->get();

        foreach ($users as $user) {
            $user->assignRole('xm');
        }
    }
}
