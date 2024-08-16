<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Author::paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:128',
                'bio' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $author = Author::create($validator->validated());
        return response()->json([
            'message' => 'ِuthor successfully created',
            'author' => $author
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }
        return $author;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:128',
                'bio' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'author not found'], 404);
        }

        $author->update($validator->validated());
        return response()->json(['data' => $author, 'message' => 'Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        {
            $author = Author::find($id);
    
            if (!$author) {
                return response()->json(['message' => 'Author not found'], 404);
            }
    
            $author->delete();
    
            return response()->json(['message' => 'Author deleted successfully'], 200);
        }
    }
}
