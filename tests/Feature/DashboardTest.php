<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
    }

    public function test_dashboard_renders_failed_posts_attention(): void
    {
        $user = User::factory()->create();

        $post = Post::create([
            'user_id' => $user->id,
            'type' => 'photo',
            'caption' => 'Test posting gagal',
            'media' => [['path' => 'posts/test.jpg', 'name' => 'test.jpg', 'kind' => 'image', 'size' => 100]],
            'mode' => 'now',
            'state' => 'done',
        ]);

        $post->targets()->create([
            'platform' => 'instagram',
            'status' => 'failed',
            'error' => 'API error',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Test posting gagal');
    }
}
