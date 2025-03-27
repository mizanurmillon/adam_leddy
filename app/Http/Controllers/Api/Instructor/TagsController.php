<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagsController extends Controller
{

    use ApiResponse;

    public function AllTags()
    {
        $data = Tag::all();

        if ($data->isEmpty()) {

            return $this->error([], 'No Tags Found', 200);
        }

        return $this->success($data, 'Tags found successfully', 200);
    }
}
