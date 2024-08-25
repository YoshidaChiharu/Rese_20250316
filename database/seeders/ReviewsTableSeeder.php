<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // csvファイル読み込み
        $csvFile = database_path('csv/review_data.csv');
        $csvData = File::get($csvFile);
        $rows = explode(";\n", $csvData);

        // dd($rows);

        foreach ($rows as $index => $row) {
            $data = str_getcsv($row);

            $review_data[] = [
                'rating' => $data[0],
                'title' => $data[1],
                'comment' => $data[2],
            ];
        }

        $data_num = 200;
        for ($i = 0; $i < $data_num; $i++) {
            $random_num = fake()->numberBetween(0, count($review_data) - 1);

            DB::table('reviews')->insert([
                'reservation_id' => fake()->unique()->numberBetween(1, Reservation::count()),
                'rating' => $review_data[$random_num]['rating'],
                'title' => $review_data[$random_num]['title'],
                'comment' => $review_data[$random_num]['comment'],
            ]);
        }
    }
}
