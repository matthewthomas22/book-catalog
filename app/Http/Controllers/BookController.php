<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with(['author', 'publisher']);

        // Filter by title
        if($request->filled('title')){
            $query->where('title', 'ILIKE', '%'.$request->input('title').'%');
        }

        // Filter by author
        if($request->filled('author_id')){
            $query->where('author_id', $request->input('author_id'));
        }

        // Filter by Publisher
        if($request->filled('publisher_id')){
            $query->where('publisher_id', $request->input('publisher_id'));
        }
        
        // Sorting
        $allowedSorts = ['title', 'published_date'];
        $sort = $request->input('sort');
        $direction = $request->input('direction', 'asc');

        if(in_array($sort, $allowedSorts) && in_array($direction, ['asc','desc'])){
            $query->orderBy($sort, $direction);
        }
        

        return response()->json([$query->paginate(10)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exist:authors,id',
            'publisher_id' => 'required|exists:publisher,id',
            'published_date' => 'nullable|date'
        ]);

        $book = Book::create($validated);

        return response()->json($book, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::with(['author', 'publisher'])->findOrFail($id);

        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exist:authors,id',
            'publisher_id' => 'required|exists:publisher,id',
            'published_date' => 'nullable|date'
        ]);

        $book->update($validated);

        return response()->json($book);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);

        $book->delete();

        return response()->json(['message' => 'Deleted Succesfully!']);
    }
}
