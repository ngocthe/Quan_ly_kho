<?php

use App\Models\System\Role;
use App\Models\System\User;
use Illuminate\Database\Seeder;
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
        Role::create([
            'name' => 'System Admin',
            'code' => 'sysadmin',
            'home_url' => '/users',

        ]);
        
        User::create([
            'name' => 'System Admin',
            'username' => 'sysadmin',
            'role_id' => 1,
            'email' => 'sysadmin@gmail.com',
            'phone_number' => '0833266697',
            'password' => Hash::make('12345678'),
        ]);
        
    }
}
