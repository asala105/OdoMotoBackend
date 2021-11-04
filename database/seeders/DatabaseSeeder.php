<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
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
            'status' => 'Pending',
        ]);
        DB::table('statuses')->insert([
            'status' => 'Sent to manager',
        ]);
        DB::table('statuses')->insert([
            'status' => 'Sent to HR',
        ]);
        DB::table('statuses')->insert([
            'status' => 'Approved',
        ]);
        DB::table('statuses')->insert([
            'status' => 'Rejected',
        ]);
        DB::table('statuses')->insert([
            'status' => 'Done',
        ]);
        DB::table('statuses')->insert([
            'status' => 'In Progress',
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
            'organization_id' => 1,
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@mail.com',
            'rank' => 1,
            'date_of_birth' => '1999/09/09',
            'phone_nb' => '012345678',
            'password' => bcrypt('admin123'),
        ]);
        DB::table('users')->insert([
            'department_id' => 1,
            'manager_id' => 1,
            'user_type_id' => 3,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@mail.com',
            'rank' => 4,
            'date_of_birth' => '1999-09-27',
            'phone_nb' => '02834847473',
            'password' => bcrypt('1999-09-27'),
        ]);
        DB::table('users')->insert([
            'department_id' => 1,
            'user_type_id' => 4,
            'manager_id' => 1,
            'organization_id' => 1,
            'first_name' => 'Aya',
            'last_name' => 'Harfoush',
            'email' => 'aya@mail.com',
            'rank' => 4,
            'date_of_birth' => '2001-02-03',
            'phone_nb' => '818288383',
            'password' => bcrypt('2001-02-03'),
        ]);
        DB::table('users')->insert([
            'department_id' => 1,
            'user_type_id' => 3,
            'manager_id' => 1,
            'organization_id' => 1,
            'first_name' => 'Goerge',
            'last_name' => 'Williams',
            'email' => 'goerge@mail.com',
            'rank' => 4,
            'date_of_birth' => '1980-10-30',
            'phone_nb' => '487932646356',
            'password' => bcrypt('1980-10-30'),
        ]);
        DB::table('users')->insert([
            'department_id' => 1,
            'user_type_id' => 3,
            'manager_id' => 1,
            'organization_id' => 1,
            'first_name' => 'Jack',
            'last_name' => 'Waterson',
            'email' => 'jack@mail.com',
            'rank' => 4,
            'date_of_birth' => '1985-04-30',
            'phone_nb' => '937975676',
            'password' => bcrypt('1985-04-30'),
        ]);
        DB::table('users')->insert([
            'department_id' => 1,
            'user_type_id' => 3,
            'manager_id' => 1,
            'organization_id' => 1,
            'first_name' => 'Justin',
            'last_name' => 'Joe',
            'email' => 'joe@mail.com',
            'rank' => 4,
            'date_of_birth' => '1970-01-06',
            'phone_nb' => '018985496',
            'password' => bcrypt('1970-01-06'),
        ]);
        DB::table('vehicles')->insert([
            'id' => 3,
            'driver_id' => 6,
            'organization_id' => 1,
            'category' => 'Van',
            'registration_code' => 'rec003',
            'plate_number' => 'LB 1238263',
            'model' => 'Jeep 2014',
            'weight' => 1700.00,
            'odometer' => 100.00,
            'fuel_level' => 70,
            'is_rented' => 1
        ]);

        DB::table('vehicles')->insert([
            'id' => 4,
            'driver_id' => 5,
            'organization_id' => 1,
            'category' => '4x4',
            'registration_code' => 'rec009',
            'plate_number' => '1238483 LB',
            'model' => 'Hyundai 2009',
            'weight' => 1540.00,
            'odometer' => 100.00,
            'fuel_level' => 90,
            'is_rented' => 1
        ]);
        DB::table('vehicles')->insert([
            'id' => 5,
            'driver_id' => 3,
            'organization_id' => 1,
            'category' => 'Car',
            'registration_code' => 'rec010',
            'plate_number' => '12384 LB',
            'model' => 'Hyundai 2012',
            'weight' => 1520.00,
            'odometer' => 100.00,
            'fuel_level' => 60,
            'is_rented' => 0
        ]);
        DB::table('vehicles')->insert([
            'id' => 6,
            'driver_id' => 6,
            'organization_id' => 1,
            'category' => '4x4',
            'registration_code' => 'rec011',
            'plate_number' => '18483 LB',
            'model' => 'Hyundai 2009',
            'weight' => 1540.00,
            'odometer' => 100.00,
            'fuel_level' => 50,
            'is_rented' => 0
        ]);
        DB::table('vehicles')->insert([
            'id' => 7,
            'driver_id' => 5,
            'organization_id' => 1,
            'category' => '4x4',
            'registration_code' => 'rec012',
            'plate_number' => '1238483 ZH',
            'model' => 'Hyundai 2009',
            'weight' => 1540.00,
            'odometer' => 100.00,
            'fuel_level' => 90,
            'is_rented' => 1
        ]);

        DB::table('attendances')->insert([
            'id' => 1,
            'organization_id' => 1,
            'user_id' => 5,
            'status_id' => 4,
            'date' => '2021-10-15',
            'working_from' => '10:02:04',
            'working_to' => '16:02:04'
        ]);
        DB::table('attendances')->insert([
            'id' => 2,
            'organization_id' => 1,
            'user_id' => 5,
            'status_id' => 1,
            'date' => '2021-10-16',
            'working_from' => '10:02:04',
            'working_to' => '15:02:04'
        ]);
        DB::table('attendances')->insert([
            'id' => 3,
            'organization_id' => 1,
            'user_id' => 5,
            'status_id' => 5,
            'date' => '2021-10-17',
            'working_from' => '10:02:04',
            'working_to' => '16:02:04'
        ]);
        DB::table('fleet_requests')->insert([
            'id' => 1,
            'organization_id' => 1,
            'vehicle_id' => 5,
            'driver_id' => 5,
            'department_id' => 1,
            'date' => '2021-11-02',
            'start_time' => '10:25:00',
            'end_time' => '17:00:00',
            'purpose' => 'field visit'
        ]);
        DB::table('destinations')->insert([
            'id' => 1,
            'fleet_request_id' => 1,
            'location_from' => 'Beirut',
            'location_to' => 'Bekaa'
        ]);
        DB::table('notifications')->insert([
            'user_id' => 1,
            'title' => "Leave Request",
            'body' => 'A driver requested a leave.',
            'is_read' => 0,
            'created_at' => '2021-10-19 15:20:39',
            'updated_at' => '2021-10-19 15:20:39',
            'type' => 'Info'
        ]);
        DB::table('leaves')->insert([
            'status_id' => 4,
            'organization_id' => 1,
            'user_id' => NULL,
            'leave_from_date' => '2021-10-21',
            'leave_till_date' => '2021-10-22',
            'leave_type' => 'annual leave',
            'details' => NULL,
            'created_at' => '2021-10-19 15:20:39',
            'updated_at' => '2021-10-19 15:20:39'
        ]);

        DB::table('leaves')->insert([
            'status_id' => 4,
            'organization_id' => 1,
            'user_id' => NULL,
            'leave_from_date' => '2021-09-21',
            'leave_till_date' => '2021-10-22',
            'leave_type' => 'annual leave',
            'details' => NULL,
            'created_at' => '2021-10-19 15:21:13',
            'updated_at' => '2021-10-19 15:21:13'
        ]);

        DB::table('leaves')->insert([
            'status_id' => 2,
            'organization_id' => 1,
            'user_id' => NULL,
            'leave_from_date' => '2021-10-21',
            'leave_till_date' => '2021-10-22',
            'leave_type' => 'annual leave',
            'details' => NULL,
            'created_at' => '2021-10-19 15:22:41',
            'updated_at' => '2021-10-19 15:22:41'
        ]);

        DB::table('leaves')->insert([
            'status_id' => 4,
            'organization_id' => 1,
            'user_id' => NULL,
            'leave_from_date' => '2021-08-21',
            'leave_till_date' => '2021-10-22',
            'leave_type' => 'annual leave',
            'details' => NULL,
            'created_at' => '2021-10-19 15:23:06',
            'updated_at' => '2021-10-19 15:23:06'
        ]);

        DB::table('leaves')->insert([
            'status_id' => 4,
            'organization_id' => 1,
            'user_id' => NULL,
            'leave_from_date' => '2021-10-21',
            'leave_till_date' => '2021-10-22',
            'leave_type' => 'annual leave',
            'details' => NULL,
            'created_at' => '2021-10-19 15:27:20',
            'updated_at' => '2021-10-19 15:27:20'
        ]);

        DB::table('leaves')->insert([
            'status_id' => 4,
            'organization_id' => 1,
            'user_id' => 5,
            'leave_from_date' => '2021-10-21',
            'leave_till_date' => '2021-10-22',
            'leave_type' => 'annual leave',
            'details' => NULL,
            'created_at' => '2021-10-19 15:50:40',
            'updated_at' => '2021-10-19 15:50:40'
        ]);

        DB::table('leaves')->insert([
            'id' => 7,
            'organization_id' => 1,
            'status_id' => 4,
            'user_id' => 5,
            'leave_from_date' => '2021-09-21',
            'leave_till_date' => '2021-09-22',
            'leave_type' => 'annual leave',
            'details' => NULL,
            'created_at' => '2021-10-19 15:53:09',
            'updated_at' => '2021-10-19 15:53:09'
        ]);

        DB::table('leaves')->insert([
            'id' => 8,
            'status_id' => 4,
            'organization_id' => 1,
            'user_id' => 4,
            'leave_from_date' => '2021-09-21',
            'leave_till_date' => '2021-10-22',
            'leave_type' => 'annual leave',
            'details' => NULL,
            'created_at' => '2021-10-19 15:53:23',
            'updated_at' => '2021-10-19 15:53:23'
        ]);

        DB::table('leaves')->insert([
            'status_id' => 4,
            'organization_id' => 1,
            'user_id' => 4,
            'leave_from_date' => '2021-10-21',
            'leave_till_date' => '2021-10-22',
            'leave_type' => 'annual leave',
            'details' => NULL,
            'created_at' => '2021-10-19 15:57:50',
            'updated_at' => '2021-10-19 15:57:50'
        ]);

        DB::table('leaves')->insert([
            'status_id' => 4,
            'organization_id' => 1,
            'user_id' => 4,
            'leave_from_date' => '2021-10-21',
            'leave_till_date' => '2021-10-22',
            'leave_type' => 'annual leave',
            'details' => NULL,
            'created_at' => '2021-10-19 16:11:36',
            'updated_at' => '2021-10-19 16:11:36'
        ]);
    }
}
