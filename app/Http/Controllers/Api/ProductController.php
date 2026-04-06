<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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

        // Query with optional search filter and ordering
        $query = Product::with(['unit', 'brand', 'category'])->orderBy('created_at', 'desc');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('unit_id', 'like', "%$search%");
            });
        }

        // Paginate the results
        $products = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($products);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_id' => 'nullable|exists:units,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        $product = Product::create($request->only(['name', 'unit_id', 'brand_id', 'price', 'stock', 'category']));
        return response()->json($product, 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);
        $product->update($request->only(['unit_id', 'brand_id', 'name', 'price', 'stock', 'category_id' ]));
        return response()->json($product);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Producto eliminado'], 200);
    }
}
