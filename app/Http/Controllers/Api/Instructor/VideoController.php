<?php
namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\CourseVideo;
use App\Services\VimeoService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Vimeo\Vimeo;
use TusPhp\Tus\Server as TusServer;

class VideoController extends Controller
{
    use ApiResponse;

    /**
     * Get Vimeo upload URL using TUS protocol
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'size' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }

        try {
            $vimeoService = new VimeoService();
            $vimeo = $vimeoService->getClient();

            $response = $vimeo->request('/me/videos', [
                'upload' => [
                    'approach' => 'tus',
                    'size' => $request->size
                ]
            ], 'POST');

            // Check if we have a successful response (200 or 201)
            if ($response['status'] >= 200 && $response['status'] < 300) {
                // Verify we have the required upload data
                if (!isset($response['body']['upload']['upload_link']) || !isset($response['body']['uri'])) {
                    Log::error('Vimeo response missing required fields', [
                        'response' => $response
                    ]);
                    return $this->error([], 'Invalid response from Vimeo', 500);
                }

                return $this->success([
                    'upload_link' => $response['body']['upload']['upload_link'],
                    'video_uri' => $response['body']['uri']
                ], 'Upload URL generated successfully');
            }

            return $this->error([], 'Failed to create upload session', 500);

        } catch (\Exception $e) {
            Log::error('Vimeo API error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->error([], 'Failed to generate upload URL: ' . $e->getMessage(), 500);
        }
    }
    public function getVideoInfo($videoId)
    {
        try {

            $vimeoService = new VimeoService();
            $vimeo = $vimeoService->getClient();

            $response = $vimeo->request("/videos/$videoId", [], 'GET');

            if ($response['status'] !== 200) {
                return $this->error([], 'Failed to fetch video info', 500);
            }

            return $this->success([
                'name' => $response['body']['name'] ?? null,
                'status' => $response['body']['status'] ?? null,
                'player_embed_url' => $response['body']['player_embed_url'] ?? null,
                'link' => $response['body']['link'] ?? null,
            ], 'Video info retrieved');

        } catch (\Exception $e) {
            return $this->error([], 'Vimeo API error: ' . $e->getMessage(), 500);
        }
    }


    public function delete($id)
    {
        $user = auth()->user();

        if (
            $user->status != "active"
        ) {
            return $this->error([], 'You don\'t have permission to delete videos', 403);
        }

        $video = CourseVideo::find($id);

        if (! $video) {
            return $this->error([], 'Video Not Found', 404);
        }

        $vimeo = new Vimeo(env('VIMEO_CLIENT'), env('VIMEO_SECRET'), env('VIMEO_ACCESS'));
        if (! empty($video->video_url)) {
            preg_match('/\/video\/(\d+)/', $video->video_url, $matches);
            if (! empty($matches[1])) {
                $previousVideoId = $matches[1];
                $vimeo->request("/videos/{$previousVideoId}", [], 'DELETE');
            }
        }

        $video->delete();

        return $this->success([
            'video' => $video,
        ], 'Video deleted successfully', 200);

    }

}
