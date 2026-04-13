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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para la imagen
            'price' => 'required|numeric|min:0', // Validación para el precio
            'stock' => 'required|integer|min:0', // Validación para el stock
            'unit_id' => 'required|exists:units,id', // Validación para la unidad
            'brand_id' => 'nullable|exists:brands,id', // Validación para la marca
            'category_id' => 'nullable|exists:categories,id', // Validación para la categoría
        ]);
        $productData = $request->only(['name', 'unit_id', 'brand_id', 'price', 'stock', 'category_id']);
        $product = Product::create($productData);
        if ($request->hasFile('image')) {
            $imageName = "producto_{$product->id}." . $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->storeAs('products', $imageName, 'public');
            $fullImageUrl = asset('storage/' . $imagePath); // Generar la URL completa
            $product->update(['image' => $fullImageUrl]);
        }
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para la imagen
            'unit_id' => 'required|exists:units,id', // Validación para la unidad
            'brand_id' => 'nullable|exists:brands,id', // Validación para la marca
            'category_id' => 'nullable|exists:categories,id', // Validación para la categoría
        ]);
        $product->update($request->only(['unit_id', 'brand_id', 'name', 'price', 'stock', 'category_id']));
        // Manejar la subida de la imagen nueva si existe
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($product->image) {
                $oldImagePath = str_replace(asset('storage/'), '', $product->image); // Obtener la ruta relativa
                \Storage::disk('public')->delete($oldImagePath); // Eliminar la imagen anterior
            }
            // Guardar la nueva imagen
            $imageName = "producto_{$product->id}." . $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->storeAs('products', $imageName, 'public');
            $fullImageUrl = asset('storage/' . $imagePath); // Generar la URL completa
            $product->update(['image' => $fullImageUrl]);
        }
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
