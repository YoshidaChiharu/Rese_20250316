<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            ['name' => 'administrator'],
            ['name' => 'shop_owner'],
            ['name' => 'general_user'],
        ];
        DB::table('roles')->insert($param);
    }
}
