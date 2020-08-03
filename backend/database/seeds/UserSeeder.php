<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {      

        $admin = new User();
        $admin->name = 'admin';
        $admin->email = 'admin@test.com';
        $admin->password = Hash::make('password');
        $admin->isAdmin = TRUE;
        $admin->save();


        $user = new User();
        $user->name = 'john doe';
        $user->email = 'johndoe@test.com';
        $user->password = Hash::make('password');
        $user->save();

        $user = new User();
        $user->name = 'user';
        $user->email = 'user@test.com';
        $user->password = Hash::make('password');
        $user->save();

        $user = new User();
        $user->name = 'guest';
        $user->email = 'guest@test.com';
        $user->password = Hash::make('password');
        $user->save();
    }
}
