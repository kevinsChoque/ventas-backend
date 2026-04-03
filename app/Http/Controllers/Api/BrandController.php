<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
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
        $query = Brand::query();
        if (!empty($search)) {
            $query->where('name', 'like', "%$search%");
        }

        // Paginate the results
        $brands = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $brand = Brand::create($request->all());
        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Brand::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->update($request->all());
        return response()->json($brand);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return response()->json(['message' => 'Brand deleted successfully']);
    }
}
