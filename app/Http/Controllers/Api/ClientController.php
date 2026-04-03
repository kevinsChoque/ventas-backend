<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
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
        $query = Client::query();
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('document_number', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        // Paginate the results
        $clients = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $client = Client::create($request->all());
        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Client::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json(['message' => 'Client deleted successfully']);
    }
}
