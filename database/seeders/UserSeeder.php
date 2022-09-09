<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        // \App\Models\User::create([
        //         'first_name' => 'trần',
        //         'last_name' => 'tình',
        //         'email' => 'tinhkien@gmail.com',
        //         'password' => Hash::make('123123123'),
        //         'role_id' => 1
        //     ]);




        // DB::table('users')->factory(10)->insert([
        //     'first_name' => Str::random(10),
        //     'last_name' => Str::random(10),
        //     'email' => Str::random(10).'@gmail.com',
        //     'password' => Hash::make('password'),
        //     'role_id' => 3
        // ]);
    }
}
