<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ShopsTableSeeder;
use Database\Seeders\ReviewsTableSeeder;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Reservation;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ShopsTableSeeder::class);
        User::factory(10000)->create();
        Favorite::factory(10000)->create();
        Reservation::factory(5000)->create();
        $this->call(ReviewsTableSeeder::class);
    }
}
