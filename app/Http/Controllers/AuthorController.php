<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Author::query();

        // Filtering
        if($request->filled('name')){
            $query->where('name', 'ILIKE', '%'.$request->name.'%');
        }

        // Sorting
        $allowedSorts = ['name', 'created_at'];
        $sort = $request->input('sort');
        $direction = $request->input('direction', 'asc');

        if(in_array($sort, $allowedSorts) && in_array($direction, ['asc','desc'])){
            $query->orderBy($sort, $direction);
        }

        return response()->json([
            'status' => true,
            'message' => 'Authors Retreived Succesfully',
            'data' => $query->paginate(10)
        ]);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $author = Author::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Data Succefully Created',
            'data' => $author
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $author = Author::findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Succesfully retrieved authors',
            'data' => $author
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $author = Author::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $author->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Succesfully Updated author',
            'data' => $author
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $author = Author::findOrFail($id);

        $author->delete();

        return response()->json(['status' => true, 'message' => 'Deleted succesfully!']);
    }
}
