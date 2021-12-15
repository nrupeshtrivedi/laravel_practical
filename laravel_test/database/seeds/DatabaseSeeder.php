<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
		    'firstname' => 'Super',
            'lastname' => 'Admin',
		    'email' => 'superadmin@gmail.com',
		    'password' => \Illuminate\Support\Facades\Hash::make('admin123!@#'),
            'mobile_number' => '9900990099',
            'status' => 'active',
		    'is_permission' => 1
	    ]);
    }
}
