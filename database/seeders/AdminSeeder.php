<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'desman@pardosi.net',
            'username' => 'admin',
            'role' => '0',
            'email_verified_at' => null,
            'password' => '$2y$10$Z3IPRUNAXHq0ks1hh4R0Quy2LmXmpzW7FIbXyTDptfLPLhM3uoxgS',
            'remember_token' => 'Qvw7jzYGWDmULCBF1DW3oIAW0V2MOUZcD7msFWdP88sCZPhfpId7GcOvMydC',
            'created_at' => '2021-02-18 15:15:56',
            'updated_at' => '2021-02-18 15:15:56',
        ]);    
    }
}
