<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // csvファイルを使用してシーディング
        $csvFile = database_path('csv/shop_data.csv');
        $csvData = File::get($csvFile);
        $rows = explode("\n", $csvData);

        foreach ($rows as $row) {
            $data = str_getcsv($row);

            DB::table('shops')->insert([
                'name' => $data[0],
                'area' => $data[1],
                'genre' => $data[2],
                'detail' => $data[3],
                'image' => $data[4],
            ]);
        }
    }
}
