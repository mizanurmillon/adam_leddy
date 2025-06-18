<?php
namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\CourseVideo;
use App\Traits\ApiResponse;
use App\Jobs\VimeoTusUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Vimeo\Vimeo;
use TusPhp\Tus\Server as TusServer;

class VideoController extends Controller
{
    use ApiResponse;

    public function create(Request $request)
    {
        $isChunked = $request->has('dzuuid');

        $rules = [
            'file' => ['required', 'file'],
            'dzchunkindex' => 'required|integer',
            'dztotalchunkcount' => 'required|integer',
            'dzfilename' => 'required|string',
            'course_module_id' => 'required|exists:course_modules,id',
            'video_title' => 'required|string|max:255',
        ];

        if (!$isChunked) {
            // Only apply full MIME type validation if not chunked
            $rules['file'][] = 'mimetypes:video/mp4,video/quicktime,video/x-ms-wmv,video/x-msvideo';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }


        $file = $request->file('file');
        $chunkIndex = $request->input('dzchunkindex');
        $totalChunks = $request->input('dztotalchunkcount');
        $fileName = $request->input('dzfilename');
        $courseModuleId = $request->input('course_module_id');
        $videoTitle = $request->input('video_title');

        $chunkDir = storage_path('app/uploads/chunks');
        if (!is_dir($chunkDir)) {
            mkdir($chunkDir, 0777, true);
        }

        $chunkPath = $chunkDir . '/' . $fileName . '.part' . $chunkIndex;
        $file->move($chunkDir, $fileName . '.part' . $chunkIndex);

        // If last chunk, merge
        if ($chunkIndex == $totalChunks - 1) {
            $finalPath = $this->mergeChunks($fileName, $totalChunks);

            // Upload to Vimeo
            $vimeo = new Vimeo(env('VIMEO_CLIENT'), env('VIMEO_SECRET'), env('VIMEO_ACCESS'));
            $vimeoResponse = $vimeo->upload($finalPath, [
                'name'    => $videoTitle,
                'privacy' => ['view' => 'anybody'],
                'embed'   => [
                    'title' => ['name' => 'hide', 'owner' => 'hide', 'portrait' => 'hide'],
                    'buttons' => ['like' => false, 'watchlater' => false, 'share' => false, 'embed' => false],
                    'logos' => ['vimeo' => false],
                ]
            ]);
            $videoData = $vimeo->request($vimeoResponse, [], 'GET')['body'];
            $videoId = trim($videoData['uri'], '/videos/');
            $embedUrl = "https://player.vimeo.com/video/{$videoId}";
//             Wait for duration
            $duration = 0;
            $retry = 0;
            while ($duration == 0 && $retry++ < 20) {
                sleep(.5);
                $videoData = $vimeo->request($vimeoResponse, [], 'GET')['body'];
                $duration = $videoData['duration'] ?? 0;
            }
            if ($duration <= 0) {
                return response()->json(['status' => 'error', 'message' => 'Video duration not available'], 422);
            }

            // Save to DB
            $video = CourseVideo::create([
                'course_module_id' => $courseModuleId,
                'video_title'      => $videoTitle,
                'video_url'        => $embedUrl,
                'duration'         => gmdate("H:i:s", $duration),
            ]);

            // Delete merged file
            if (file_exists($finalPath)) {
                unlink($finalPath);
            }

            return response()->json(['status' => 'complete', 'video' => $video]);
        }

        return response()->json(['status' => 'chunk_received']);
    }

    private function mergeChunks($fileName, $totalChunks)
    {

        $chunkDir = storage_path('app/uploads/chunks');
        $finalDir = storage_path('app/uploads');
        if (!is_dir($finalDir)) {
            mkdir($finalDir, 0777, true);
        }
        $finalPath = $finalDir . '/' . $fileName;
        $file = fopen($finalPath, 'wb');
        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = $chunkDir . '/' . $fileName . '.part' . $i;
            $chunk = fopen($chunkPath, 'rb');
            stream_copy_to_stream($chunk, $file);
            fclose($chunk);
//            unlink($chunkPath);
        }
        fclose($file);
        return $finalPath;
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

    /**
     * Handle large video upload using TUS protocol
     */
    public function uploadLargeVideo(Request $request)
    {
        $isChunked = $request->has('dzuuid');

        $rules = [
            'file' => ['required', 'file'],
            'dzchunkindex' => 'required|integer',
            'dztotalchunkcount' => 'required|integer',
            'dzfilename' => 'required|string',
            'course_module_id' => 'required|exists:course_modules,id',
            'video_title' => 'required|string|max:255',
        ];

        if (!$isChunked) {
            // Only apply full MIME type validation if not chunked
            $rules['file'][] = 'mimetypes:video/mp4,video/quicktime,video/x-ms-wmv,video/x-msvideo';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Store the file temporarily
            $file = $request->file('file');
            $finalDir = storage_path('app/uploads/chunks');
            if (!is_dir($finalDir)) {
                mkdir($finalDir, 0777, true);
            }
//            $tempPath = $file->storeAs(
//                $finalDir,
//                time() . '_' . $file->getClientOriginalName(),
//                'local'
//            );
                $fileName = $request->input('dzfilename');
            $tempPath= $file->move($finalDir, $fileName. '.part'.$request->dzchunkindex);
            if ($request->dzchunkindex == ($request->dztotalchunkcount-1)) {
                $finalPath = $this->mergeChunks($fileName, $request->dztotalchunkcount);
                // Dispatch the upload job
                VimeoTusUpload::dispatch(
                    $finalPath,
                    $request->video_title,
                    $request->course_module_id
                )->onQueue('videos');
            }



            return $this->success([], 'Video upload started successfully', 202);

        } catch (\Exception $e) {
            Log::error('Failed to start video upload', [
                'error' => $e->getMessage(),
                'file' => $request->file('file')->getClientOriginalName()
            ]);
             return $e;
            return $this->error([], 'Failed to start video upload: ' . $e->getMessage(), 500);
        }
    }
}
