<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Shop;

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

        foreach ($rows as $index => $row) {
            $data = str_getcsv($row);

            $review_data[] = [
                'rating' => $data[0],
                'title' => $data[1],
                'comment' => $data[2],
            ];
        }

        // user_id と shop_id の組み合わせを被りなくランダム取得
        $data_num = 200; // 作成するダミーデータの数
        $user_ids = User::all()->pluck('id');
        $shop_ids = Shop::all()->pluck('id');
        $matrix = $user_ids->crossJoin($shop_ids);
        $key_pairs = fake()->unique()->randomElements($matrix, $data_num);

        foreach ($key_pairs as $key_pair) {
            $random_num = fake()->numberBetween(0, count($review_data) - 1);
            $param = [
                'user_id' => $key_pair[0],
                'shop_id' => $key_pair[1],
                'rating' => $review_data[$random_num]['rating'],
                'comment' => $review_data[$random_num]['comment'],
                'image' => 'img/dummy_review_image.jpg',
            ];
            DB::table('reviews')->insert($param);
        }
    }
}
