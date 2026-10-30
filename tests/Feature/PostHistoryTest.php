<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_log_success_creates_history_record_with_relationships(): void
    {
        $user = User::factory()->create();

        $post = Post::create([
            'user_id' => $user->id,
            'type' => 'photo',
            'caption' => 'Test caption',
            'media' => [['path' => 'posts/test.jpg', 'name' => 'test.jpg', 'kind' => 'image', 'size' => 100]],
            'mode' => 'now',
            'state' => 'processing',
        ]);

        $target = $post->targets()->create([
            'platform' => 'instagram',
            'status' => 'sending',
            'attempts' => 1,
        ]);

        $history = PostHistory::logSuccess($target, 'https://instagram.com/p/abc123xyz');

        $this->assertDatabaseHas('post_histories', [
            'id' => $history->id,
            'post_id' => $post->id,
            'post_target_id' => $target->id,
            'platform' => 'instagram',
            'status' => 'success',
            'attempt' => 1,
            'post_url' => 'https://instagram.com/p/abc123xyz',
        ]);

        $this->assertNotNull($history->published_at);
        $this->assertEquals($post->id, $history->post->id);
        $this->assertEquals($target->id, $history->target->id);
    }

    public function test_log_failure_records_error_and_increments_attempt(): void
    {
        $user = User::factory()->create();

        $post = Post::create([
            'user_id' => $user->id,
            'type' => 'photo',
            'caption' => 'Test caption',
            'media' => [['path' => 'posts/test.jpg', 'name' => 'test.jpg', 'kind' => 'image', 'size' => 100]],
            'mode' => 'now',
            'state' => 'processing',
        ]);

        $target = $post->targets()->create([
            'platform' => 'tiktok',
            'status' => 'sending',
            'attempts' => 1,
        ]);

        $firstHistory = PostHistory::logFailure($target, 'Gagal menghubungkan ke server');
        $this->assertEquals(1, $firstHistory->attempt);
        $this->assertEquals('failed', $firstHistory->status);
        $this->assertEquals('Gagal menghubungkan ke server', $firstHistory->error_message);

        $secondHistory = PostHistory::logFailure($target, 'Gagal pada percobaan kedua');
        $this->assertEquals(2, $secondHistory->attempt);
        $this->assertDatabaseCount('post_histories', 2);
    }
}
