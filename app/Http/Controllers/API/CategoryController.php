<?php
// app/Http/Controllers/API/CategoryController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{   
    public function __construct()
    {
        $this->middleware(['auth:api', 'isOwner'])->except(['index', 'show']);
    }
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'message' => 'Tampilkan semua categori',
            'data' => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Berhasil membuat category baru',
            'data' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = Category::with('books')->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json([
            'message' => 'Detail kategori dengan id: ' . $id, 
            'data' => $category
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Berhasil mengupdate nama category dengan id: ' . $id,
            'data' => $category
        ], 200);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(
            ['message' => 'Berhasil menghapus category'], 200);
    }
}
