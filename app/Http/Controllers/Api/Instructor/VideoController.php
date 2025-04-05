<?php

namespace App\Http\Controllers\Api\Instructor;

use Vimeo\Vimeo;
use App\Models\CourseVideo;
use App\Traits\ApiResponse;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    use ApiResponse;
    
    public function create(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'video_url' => 'required|mimetypes:video/mp4,video/quicktime,video/x-ms-wmv,video/x-msvideo|max:100000',
            'video_title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error([], $validator->errors(), 400);
        }

        $user = auth()->user();

        $data = CourseModule::where('id', $id)->first();

        $vimeo = new Vimeo(env('VIMEO_CLIENT'), env('VIMEO_SECRET'), env('VIMEO_ACCESS'));

        // Ensure the file is valid
        $videoFile = $request->file('video_url');

        if (! $videoFile->isValid()) {
            return $this->error([], "Invalid video file", 422);
        }

        // Allowed Mime Types
        $allowedMimeTypes = ['video/mp4', 'video/quicktime', 'video/x-ms-wmv', 'video/x-msvideo'];
        if (! in_array($videoFile->getMimeType(), $allowedMimeTypes)) {
            return $this->error([], "Unsupported video format", 422);
        }

        // Upload video to Vimeo
        try {
            $courseVideoResponse = $vimeo->upload($videoFile->getPathname(), [
                'name'        => $data->title,
                'privacy'     => [
                    'view' => 'anybody',
                ],
                'embed'       => [
                    'title'   => [
                        'name'     => 'hide',
                        'owner'    => 'hide',
                        'portrait' => 'hide',
                    ],
                    'buttons' => [
                        'like'       => false,
                        'watchlater' => false,
                        'share'      => false,
                        'embed'      => false,
                    ],
                    'logos'   => [
                        'vimeo' => false,
                    ],
                ],
            ]);

            $courseVideoData = $vimeo->request($courseVideoResponse, [], 'GET')['body'];
            $courseVideoId   = trim($courseVideoData['uri'], '/videos/');
            $videoEmbedUrl   = "https://player.vimeo.com/video/" . $courseVideoId . "?title=1&byline=1&portrait=1&badge=1&autopause=1&player_id=1";

            // Get video duration with retries
            $courseVideoDuration = 0;
            $retryCount          = 0;

            while ($courseVideoDuration == 0 && $retryCount < 5) {
                sleep(5); // Wait for the video to process
                $courseVideoData     = $vimeo->request($courseVideoResponse, [], 'GET')['body'];
                $courseVideoDuration = $courseVideoData['duration'];
                $retryCount++;
            }

            if ($courseVideoDuration > 0) {
                $formattedDuration = gmdate("H:i:s", $courseVideoDuration);
            } else {
                return $this->error([], "Video duration retrieval failed", 422);
            }
        } catch (\Exception $e) {
            return $this->error([], "Video upload failed: " . $e->getMessage(), 500);
        }

        $video = CourseVideo::create([
            'course_module_id' => $data->id,
            'video_title'      => $request->video_title,
            'video_url'        => $videoEmbedUrl,
            'duration'         => $formattedDuration,
        ]);

        return $this->success([
            'video' => $video,
        ], 'Video created successfully', 200);
        
    }
}
