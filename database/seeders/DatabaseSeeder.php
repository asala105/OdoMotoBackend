<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')->insert([
            'name' => 'admin',
        ]);
        DB::table('user_types')->insert([
            'name' => 'HR',
        ]);
        DB::table('user_types')->insert([
            'name' => 'driver',
        ]);
        DB::table('user_types')->insert([
            'name' => 'employee',
        ]);

        DB::table('statuses')->insert([
            'status' => 'pending',
        ]);
        DB::table('statuses')->insert([
            'status' => 'sent to manager',
        ]);
        DB::table('statuses')->insert([
            'status' => 'sent to HR',
        ]);
        DB::table('statuses')->insert([
            'status' => 'approved',
        ]);
        DB::table('organizations')->insert([
            'name' => 'organization1',
        ]);
        DB::table('departments')->insert([
            'name' => 'Logistics',
        ]);
        DB::table('users')->insert([
            'department_id' => 1,
            'user_type_id' => 1,
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@mail.com',
            'rank' => 1,
            'date_of_birth' => '1999/09/09',
            'phone_nb' => '012345678',
            'password' => bcrypt('admin123'),
        ]);
    }
}
