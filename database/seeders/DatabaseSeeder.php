<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(10)->create();
         \App\Models\Post::factory(10)->create();

         \App\Models\User::factory()->create([
             'name' => 'Md. Tanjil Hasan',
             'email' => 'admin@test.com',
//             'password' => password_hash('password', PASSWORD_BCRYPT, ['cost' => 11]),
             'password' => bcrypt('password'),
         ]);
    }
}
