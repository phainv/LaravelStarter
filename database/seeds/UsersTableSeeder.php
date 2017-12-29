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
        $user = User::firstOrCreate(['email' => 'nguyenphai.cntt@gmail.com']);
        $user->first_name = 'Test Customer';
        $user->last_name = '12';
        $user->mobile = '84985503074';
        $user->gender = 'male';
        $user->address = 'Hoang Ngan - Thanh Xuan - Hanoi';
        $user->password = 123123;
        $user->save();
    }
}
