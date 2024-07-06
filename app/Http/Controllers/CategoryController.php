<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\RequestAddCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addCategory (RequestAddCategory $request) {

        $category = [
            'title' => $request->title,
            'description' => $request->description,
            'number' => $request->number,
        ];
        $new_category = Category::create($category);
        return response()->json([
            'data' => $new_category,
            'message' => 'Add Category Success !',
        ], 200);
    }
}
