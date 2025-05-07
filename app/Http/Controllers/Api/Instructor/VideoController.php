<?php
namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\CourseVideo;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vimeo\Vimeo;

class VideoController extends Controller
{
    use ApiResponse;

    public function create(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'video_url'   => 'required|mimetypes:video/mp4,video/quicktime,video/x-ms-wmv,video/x-msvideo|max:512000',
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
                'name'    => $data->title,
                'privacy' => [
                    'view' => 'anybody',
                ],
                'embed'   => [
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

    public function delete($id)
    {
        $user = auth()->user();

        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to delete videos', 403);
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
