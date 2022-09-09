<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(Request $request) {
        return response()->json(Category::createNew($request->user(), $request->all()));
    }

    public function remove(Request  $request) {
        return response()->json(Category::remove($request->user(), $request->all()));
    }

    public function getAll() {
        return response()->json([
            'categories' => [
                'status' => 'success',
                'data' => Category::getAll()
            ]
        ]);
    }
}
