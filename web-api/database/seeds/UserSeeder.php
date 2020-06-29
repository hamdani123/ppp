<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = ['Bappenas1', 'Bappenas2', 'Bappenas3', 'Bappenas4', 'Bappenas5', 'Bappenas6'];

        foreach($users as $user) {
            User::create(['username' => $user, 'email' => $user, 'password' => Hash::make('123456')]);
        }
        
    }
}
