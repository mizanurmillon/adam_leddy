<?php

namespace App\Jobs;

use App\Models\CourseVideo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TusPhp\Tus\Client;
use Vimeo\Vimeo;

class VimeoTusUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $videoTitle;
    protected $courseModuleId;
    protected $tries = 3;
    protected $timeout = 3600; // 1 hour timeout

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, string $videoTitle, int $courseModuleId)
    {
        $this->filePath = $filePath;
        $this->videoTitle = $videoTitle;
        $this->courseModuleId = $courseModuleId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Initialize Vimeo client
            $vimeo = new Vimeo(
                config('services.vimeo.client_id'),
                config('services.vimeo.client_secret'),
                config('services.vimeo.access_token')
            );
            Log::info($this->filePath);
            $response = $vimeo->upload( $this->filePath, [
                'name'    => $this->videoTitle,
                'privacy' => ['view' => 'anybody'],
                'embed'   => [
                    'title' => ['name' => 'hide', 'owner' => 'hide', 'portrait' => 'hide'],
                    'buttons' => ['like' => false, 'watchlater' => false, 'share' => false, 'embed' => false],
                    'logos' => ['vimeo' => false],
                ]
            ]);
            // Create upload ticket
//            $response = $vimeo->request('/me/videos', [
//                'upload' => [
//                    'approach' => 'tus',
//                    'size' => 345435
//                ],
//                'name' => $this->videoTitle,
//                'privacy' => ['view' => 'anybody'],
//                'embed' => [
//                    'title' => ['name' => 'hide', 'owner' => 'hide', 'portrait' => 'hide'],
//                    'buttons' => ['like' => false, 'watchlater' => false, 'share' => false, 'embed' => false],
//                    'logos' => ['vimeo' => false],
//                ]
//            ], 'POST');
//
//
//            $uploadData = $response['body'];
//            $uploadUrl = $uploadData['upload']['upload_link'];

            // Initialize Tus client
//            $client = new Client();
//            $client->setApiKey(config('services.vimeo.access_token'));
//
//            // Upload file using Tus
//            $key = $client->upload($this->filePath, [
//                'upload_url' => $uploadUrl,
//                'metadata' => [
//                    'filename' => basename($this->filePath),
//                    'filetype' => mime_content_type($this->filePath)
//                ]
//            ]);

            // Wait for video processing and get duration
            $videoId = trim($response, '/videos/');
            $duration = 0;
            $retry = 0;
            $maxRetries = 20;

            while ($duration == 0 && $retry++ < $maxRetries) {
                sleep(3);
                $videoData = $vimeo->request("/videos/{$videoId}", [], 'GET')['body'];
                $duration = $videoData['duration'] ?? 0;
            }

            if ($duration <= 0) {
                throw new \Exception('Failed to get video duration after processing');
            }

            // Create video record
            $video = CourseVideo::create([
                'course_module_id' => $this->courseModuleId,
                'video_title' => $this->videoTitle,
                'video_url' => "https://player.vimeo.com/video/{$videoId}",
                'duration' => gmdate("H:i:s", $duration),
            ]);

            // Clean up local file
            if (file_exists($this->filePath)) {
                unlink($this->filePath);
            }

            Log::info('Video uploaded successfully', [
                'video_id' => $video->id,
                'vimeo_id' => $videoId
            ]);

        } catch (\Exception $e) {
            Log::error('Video upload failed', [
                'error' => $e->getMessage(),
                'file' => $this->filePath,
                'title' => $this->videoTitle
            ]);

            // Clean up local file on failure
            if (file_exists($this->filePath)) {
                unlink($this->filePath);
            }

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Video upload job failed', [
            'error' => $exception->getMessage(),
            'file' => $this->filePath,
            'title' => $this->videoTitle
        ]);
    }
}
