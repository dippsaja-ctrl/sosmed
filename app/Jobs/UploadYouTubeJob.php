<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Google\Client;
use Google\Service\YouTube;
use Google\Service\YouTube\Video;
use Google\Service\YouTube\VideoSnippet;
use Google\Service\YouTube\VideoStatus;

class UploadYouTubeJob implements ShouldQueue
{
    use Queueable;

    protected $videoPath;
    protected $videoTitle;
    protected $googleToken;

    public function __construct($videoPath, $videoTitle, $googleToken)
    {
        $this->videoPath = $videoPath;
        $this->videoTitle = $videoTitle;
        $this->googleToken = $googleToken;
    }

    public function handle(): void
    {
        Log::info('Queue YouTube dimulai untuk video: ' . $this->videoTitle);

        if (!$this->googleToken) {
            Log::error('Upload YouTube Gagal: Token Google tidak ditemukan.');
            return;
        }

        if (!file_exists($this->videoPath)) {
            Log::error('Upload YouTube Gagal: Video tidak ditemukan di ' . $this->videoPath);
            return;
        }

        // Inisialisasi Google Client
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setAccessToken($this->googleToken);

        $youtube = new YouTube($client);
        $video = new Video();

        $video->setSnippet(
            new VideoSnippet([
                'title' => $this->videoTitle,
                'description' => 'Video upload otomatis dari Laravel Multi-Sosmed Poster',
                'categoryId' => '22',
            ])
        );

        $video->setStatus(
            new VideoStatus([
                'privacyStatus' => 'private',
            ])
        );

        try {
            $response = $youtube->videos->insert(
                'snippet,status',
                $video,
                [
                    'data' => file_get_contents($this->videoPath),
                    'mimeType' => 'video/mp4',
                    'uploadType' => 'multipart',
                ]
            );

            Log::info('Queue YouTube SUKSES! Video ID: ' . $response->getId());
        } catch (\Exception $e) {
            Log::error('Queue YouTube ERROR saat upload: ' . $e->getMessage());
        }
    }
}