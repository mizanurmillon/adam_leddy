<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    use ApiResponse;


    public function AllCategories()
    {

        $data = Category::all();

        if ($data->isEmpty()) {

            return $this->error([], 'No Category Found', 200);
        }

        return $this->success($data, 'Category found successfully', 200);
    }
}
