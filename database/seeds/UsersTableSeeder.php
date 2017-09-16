<?php

use App\Models\User;
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
        $user = User::firstOrCreate(['email' => 'user@dev.local']);
        $user->name = 'User Seeder';
        $user->password = 123123;
        $user->save();
    }
}
