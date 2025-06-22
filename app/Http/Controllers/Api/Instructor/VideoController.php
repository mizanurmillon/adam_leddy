<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\CourseVideo;
use App\Services\VimeoService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Vimeo\Vimeo;
use TusPhp\Tus\Server as TusServer;
use function PHPUnit\Framework\returnArgument;

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
            'title' => 'required|string|max:128',
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
                ],
                'name' => $request->title,
                'privacy' => [
                    'add' => 'false', // can't add the video to a showcase, channel, or group.
                    'download' => 'false',
                    'view' => 'anybody', // anyone can view the video
                ],
                'embed' => [
                    'title' => [
                        'name' => 'hide',
                        'owner' => 'hide',
                        'portrait' => 'hide',
                    ],
                    'buttons' => [
                        'like' => false,
                        'watchlater' => false,
                        'share' => false,
                        'embed' => false,
                    ],
                    'logos' => [
                        'vimeo' => false,
                    ],
                ],

            ], 'POST');

            // Check if we have a successful response (200 or 201)
            if ($response['status'] >= 200 && $response['status'] < 300) {
                // Verify we have the required upload data
                if (!isset($response['body']['upload']['upload_link']) || !isset($response['body']['uri'])) {
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'videos' => 'required|array',
            'videos.*.video_id' => 'required|string',
            'course_module_title' => 'required|string',
            'course_module_description' => 'required|string',
            'course_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }
        try {
            DB::beginTransaction();

            // Create the course module
            $module = CourseModule::create([
                'course_id' => $request->course_id,
                'module_title' => $request->course_module_title,
                'description' => $request->course_module_description,
            ]);

            $vimeoService = new VimeoService();
            $vimeo = $vimeoService->getClient();

            $videos = [];
            foreach ($request->videos as $videoData) {
                $videoId = $videoData['video_id'];
                $response = $vimeo->request("/videos/$videoId", [], 'GET');

                if ($response['status'] != 200) {
                    DB::rollBack();
                    return $this->error([], 'Failed to fetch video info', 500);
                }

                $formattedDuration = gmdate("H:i:s", $response['body']['duration']);

                $video = CourseVideo::create([
                    'course_module_id' => $module->id,
                    'video_title' => $response['body']['name'],
                    'video_url' => $response['body']['player_embed_url'],
                    'duration' => $formattedDuration,
                ]);
                $videos[] = $video;
            }

            DB::commit();

            return $this->success([
                'module' => $module,
                'videos' => $videos,
            ], 'Module and videos created successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], 'error: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'videos' => 'required|array',
            'videos.*.video_id' => 'required|string',
            'course_module_title' => 'required|string',
            'course_module_description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation failed', 422);
        }

        try {
            DB::beginTransaction();

            $module = CourseModule::find($id);
            if (!$module) {
                return $this->error([], 'Module not found', 404);
            }
            $module->update([
                'module_title' => $request->course_module_title,
                'description' => $request->course_module_description,
            ]);

            $vimeoService = new VimeoService();
            $vimeo = $vimeoService->getClient();

            // Get current videos in DB for the module
            $existingVideos = $module->videos;
            $existingVideoIds = $existingVideos->pluck('video_url')->map(function ($url) {
                preg_match('/video\/(\d+)/', $url, $matches);
                return $matches[1] ?? null;
            })->filter()->values()->all();
            $newVideoIds = collect($request->videos)->pluck('video_id')->all();
            $videos = [];

            //  Delete videos that are no longer in the request
            foreach ($existingVideoIds as $videoId) {
                if (!in_array($videoId, $newVideoIds)) {
                    $vimeo->request("/videos/$videoId", [], 'DELETE'); // Delete from Vimeo
                    $video = $existingVideos->firstWhere('video_url', 'like', "%$videoId%");
                    if ($video) {
                        $video->delete(); // Delete from DB
                    }
                }
            }

            // Add new videos not already in DB
            foreach ($newVideoIds as $videoId) {
                if (!in_array($videoId, $existingVideoIds)) {
                    $response = $vimeo->request("/videos/$videoId", [], 'GET');

                    if ($response['status'] != 200) {
                        DB::rollBack();
                        return $this->error([$videoId], 'Failed to fetch video info', 500);
                    }

                    $formattedDuration = gmdate("H:i:s", $response['body']['duration']);

                    $video = CourseVideo::create([
                        'course_module_id' => $module->id,
                        'video_title' => $response['body']['name'],
                        'video_url' => $response['body']['player_embed_url'],
                        'duration' => $formattedDuration,
                    ]);

                    $videos[] = $video;
                }
            }


            DB::commit();

            return $this->success([
                'module' => $module
            ], 'Module and videos updated successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], 'error: ' . $e->getMessage(), 500);
        }
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $module = CourseModule::findOrFail($id);

            $vimeoService = new VimeoService();
            $vimeo = $vimeoService->getClient();

            // Delete each associated video from Vimeo and DB
            foreach ($module->videos as $video) {
                // Extract video ID from the Vimeo URL
                if (preg_match('/video\/(\d+)/', $video->video_url, $matches)) {
                    $vimeoVideoId = $matches[1];
                    try {
                        $vimeo->request("/videos/$vimeoVideoId", [], 'DELETE');
                    } catch (\Exception $e) {
                        // Optional: log error but continue
                        \Log::warning("Failed to delete Vimeo video $vimeoVideoId: " . $e->getMessage());
                    }
                }

                $video->delete(); // Delete from DB
            }

            // Delete the module itself
            $module->delete();

            DB::commit();

            return $this->success(null, 'Module and associated videos deleted successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], 'error: ' . $e->getMessage(), 500);
        }
    }


}
