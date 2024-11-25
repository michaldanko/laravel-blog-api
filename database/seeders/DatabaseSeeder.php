<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create authors
        Author::factory(10)->create();

        // Create categories
        Category::factory(10)->create();

        // Create posts
        Post::factory(100)->create()->each(function ($post) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');

            $post->categories()->attach($categories);
        });
    }
}
