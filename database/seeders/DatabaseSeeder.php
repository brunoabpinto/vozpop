<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@mail.com',
            'password' => bcrypt('12345678'),
        ]);
        User::factory(10)->create()->each(function ($user) {
            $this->call(
                (new PostSeeder($user->id))->run()
            );
        });
    }
}
