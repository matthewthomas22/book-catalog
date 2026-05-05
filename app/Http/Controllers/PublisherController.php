<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Publisher::query();

        // Filter by name
        if($request->input('name')){
            $query->where('name', 'ILIKE', '%'.$request->input('name').'%');
        }

        // Sorting
        $allowedSorts = ['name', 'created-at'];
        $sort = $request->input('sort');
        $direction = $request->input('direction', 'asc');

        if(in_array($sort, $allowedSorts) && in_array($direction, ['asc','desc'])){
            $query->orderBy('$sort', $direction);
        }

        return response()->json([
            'status' => true,
            'message' => 'Succesfully retrieved Publishers',
            'data' => $query->paginate(10)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max;255'
        ]);

        $publisher = Publisher::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Succesfully created new Publisher',
            'data' => $publisher
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $publisher = Publisher::with('books')->findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Succesfully Retrieved Publishers',
            'data' => $publisher
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $publisher = Publisher::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max;255'
        ]);

        $publisher->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Succefully Updated Publisher',
            'data' => $publisher
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $publisher = Publisher::findOrFail($id);

        $publisher->delete();

        return response()->json(['status'=> true, 'message' => 'Deleted Succesfully!']);
    }
}
