<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->token = $user->createToken('test-token')->plainTextToken;
    }

    /**
     * Test to list posts.
     */
    public function test_can_list_posts()
    {
        Post::factory(10)->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'content',
                        'author',
                        'categories',
                    ],
                ],
            ]);
    }

    /**
     * Test to create a post.
     */
    public function test_can_create_post_with_categories()
    {
        $author = Author::factory()->create();
        $categories = Category::factory(3)->create();

        $data = [
            'title' => 'Testing post title',
            'content' => 'Testing post content.',
            'author_id' => $author->id,
            'categories' => $categories->pluck('id')->toArray(),
        ];

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/posts', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'title', 'content', 'author', 'categories']);
    }

    /**
     * Test to view a post.
     */
    public function test_can_view_post()
    {
        $post = Post::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'title', 'content', 'author', 'categories']);
    }

    /**
     * Test to update a post.
     */
    public function test_can_update_post()
    {
        $post = Post::factory()->create();

        $newData = [
            'title' => 'Updated testing title',
            'content' => 'Updated testing content.',
            'author_id' => $post->author_id,
        ];

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("/api/posts/{$post->id}", $newData);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated testing title',
                'content' => 'Updated testing content.',
                'author_id' => $post->author_id,
            ]);
    }

    /**
     * Test to delete a post.
     */
    public function test_can_delete_post()
    {
        $post = Post::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => "Post #{$post->id} was deleted successfully."]);
    }
}
