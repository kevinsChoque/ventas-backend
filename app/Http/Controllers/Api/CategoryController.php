<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 6); // Default to 6 if not provided
        $page = (int) $request->query('page', 1); // Default to page 1 if not provided
        $search = $request->query('search', ''); // Default to empty string if not provided

        // Ensure perPage and page are valid numbers
        $perPage = $perPage > 0 ? $perPage : 6;
        $page = $page > 0 ? $page : 1;

        // Query with optional search filter
        $query = Category::query();
        if (!empty($search)) {
            $query->where('name', 'like', "%$search%");
        }

        // Paginate the results
        $categories = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Category::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
