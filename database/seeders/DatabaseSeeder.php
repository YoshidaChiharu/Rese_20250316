<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\UsersTableSeeder;
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
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ShopsTableSeeder::class);
        User::factory(50)->create();
        Favorite::factory(500)->create();
        Reservation::factory(500)->create();
        $this->call(ReviewsTableSeeder::class);
    }
}
