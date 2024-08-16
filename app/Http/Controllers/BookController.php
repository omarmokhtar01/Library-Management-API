<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $categoryId = $request->input('category_id');
        $authorId = $request->input('author_id');
        $publishedFrom = $request->input('published_from');
        $publishedTo = $request->input('published_to');
        $sortBy = $request->input('sort_by', 'title');
        $sortDirection = $request->input('sort_direction', 'asc');

        $books = Book::with('author', 'category')
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where('title', 'like', '%' . $searchQuery . '%')
                    ->orWhereHas('author', function ($authorQuery) use ($searchQuery) {
                        $authorQuery->where('name', 'like', '%' . $searchQuery . '%');
                    })
                    ->orWhere('isbn', 'like', '%' . $searchQuery . '%');
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($authorId, function ($query) use ($authorId) {
                $query->where('author_id', $authorId);
            })
            ->when($publishedFrom, function ($query) use ($publishedFrom) {
                $query->whereDate('published_date', '>=', $publishedFrom);
            })
            ->when($publishedTo, function ($query) use ($publishedTo) {
                $query->whereDate('published_date', '<=', $publishedTo);
            })
            ->when($sortBy == 'author.name', function ($query) use ($sortDirection) {
                $query->join('authors', 'books.author_id', '=', 'authors.id')
                    ->orderBy('authors.name', $sortDirection);
            }, function ($query) use ($sortBy, $sortDirection) {
                $query->orderBy($sortBy, $sortDirection);
            })
            ->paginate(15);

        return response()->json($books);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:128',
                'author_id' => 'required|exists:authors,id',
                'category_id' => 'required|exists:categories,id',
                'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'isbn' => 'required|string',
                'published_date' => 'required|date',
                'copies_available' => 'required|boolean'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $coverImagePath = $request->file('cover_image')->store('books');

            $bookData = array_merge(
                $validator->validated(),
                ['cover_image' => $coverImagePath]
            );

            $book = Book::create($bookData);

            return response()->json([
                'message' => 'Book successfully registered',
                'book' => $book
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to store book'], 500);
        }
    }

    public function show(string $id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'sometimes|string|max:128',
                'author_id' => 'sometimes|exists:authors,id',
                'category_id' => 'sometimes|exists:categories,id',
                'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'isbn' => 'sometimes|string',
                'published_date' => 'sometimes|date',
                'copies_available' => 'sometimes|boolean'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->update($validator->validated());

        return response()->json(['data' => $book, 'message' => 'Updated successfully'], 200);
    }

    public function destroy(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted successfully'], 200);
    }
}
