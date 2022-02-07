<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',            
            'email' => 'swt.test2018@gmail.com',
            'user_role' => 1,
            'user_status' => 1,
            'created_by' => 'admin',
            'password' => Hash::make('shft12sdkl2ek'),
        ]);
      /*  DB::table('users')->insert([
            'first_name' => 'Publisher',            
            'email' => 'swt.test2020@gmail.com',
            'user_role' => 2,
            'user_status' => 1,
            'created_by' => 'admin',
            'password' => Hash::make('123456'),
        ]);
        DB::table('users')->insert([
            'first_name' => 'Advertiser',            
            'email' => 'swt.test2021@gmail.com',
            'user_role' => 3,
            'user_status' => 1,
            'created_by' => 'admin',
            'password' => Hash::make('123456'),
        ]);*/

        DB::table('options')->insert([
            'key' => 'average_min_CPC_bid',            
            'value' => '2.01',
        ]);
        DB::table('options')->insert([
            'key' => 'telegram_group_hrs',            
            'value' => '1',
        ]);
        DB::table('options')->insert([
            'key' => 'publisher_payout',            
            'value' => '75',
        ]);
        DB::table('options')->insert([
            'key' => 'adminstrater_email',            
            'value' => 'swt.test2018@gmail.com',
        ]);

    }
}
