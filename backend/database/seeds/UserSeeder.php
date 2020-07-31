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
