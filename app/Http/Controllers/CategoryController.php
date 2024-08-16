<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:128',
                'description' => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $category = Category::create($validator->validated());
            return response()->json([
                'message' => 'Category successfully created',
                'category' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create category'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'sometimes|string|max:128',
                'description' => 'sometimes|string|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        try {
            $category->update($validator->validated());
            return response()->json([
                'data' => $category,
                'message' => 'Category updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update category'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
