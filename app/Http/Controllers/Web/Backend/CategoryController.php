<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Tag;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('backend.layouts.categories.index', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        try {
            Category::create([
                'name' => $request->name,
            ]);
            return redirect(route('admin.categories.index'))->with('t-success', 'Category created successfully');
        } catch (\Exception $exception) {
            return redirect(route('admin.categories.index'))->with('t-error', $exception->getMessage());
        }
    }

    public function tagStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            Tag::create([
                'name' => $request->name,
            ]);
            return redirect(route('admin.categories.index'))->with('t-success', 'Tag created successfully');
        } catch (\Exception $exception) {
            return redirect(route('admin.categories.index'))->with('t-error', $exception->getMessage());
        }
    }
}
