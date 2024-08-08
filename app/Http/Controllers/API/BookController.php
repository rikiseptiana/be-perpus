<?php
// app/Http/Controllers/API/BookController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isOwner'])->except(['index', 'show', 'dashboard']);
    }

    public function dashboard () {
        $limitBook = Book::with('category')->orderBy('created_at')->take(3)->get();
        return response()->json([
            "message" => "tampil limit 2 data movie terbaru",
            "data" => $limitBook
        ], 200); 
    }
    public function index()
    {
        $books = Book::all()->load('category');
        return response()->json([
            'message' => 'Menampilkan semua buku',
            'data' => $books
        ], 200);
    }

    public function store(BookRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $imageName = time() . '-image.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/images', $imageName);
            $path = 'http://localhost:8000' . '/storage/images/';
            $data['image'] = $path .  $imageName;
        }
        $book = Book::create($data);
        return response()->json([
            'message' => 'berhasil Menambahkan buku ',
            'data' => $book
        ], 201);


        // // Handle the file upload
        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     $imageName = now()->format('YmdHis') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        //     $path = $image->storeAs('public/images', $imageName);
        // }

        // $book = Book::create([
        //     'title' => $request->title,
        //     'summary' => $request->summary,
        //     'image' => $imageName,
        //     'stok' => $request->stok,
        //     'category_id' => $request->category_id,
        // ]);

        // return response()->json([
        //     'message' => 'berhasil Menambahkan buku ',
        //     'data' => $book
        // ], 201);
    }

    public function show($id)
    {
        $book = Book::with('category')->find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json([
            'message' => 'Menampilkan buku',
            'data' => $book
        ], 200);
    }

    public function update(UpdateBookRequest $request, $id)
    {
        $data = $request->validated();
        $book = Book::findOrFail($id);

        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }
        // Handle the file upload
        if ($request->hasFile('image')) {
            // Delete the old image
            if ($book->image) {
                $namaBook = basename($book->image);
                Storage::delete('public/images/' . $namaBook);
            }

            // Upload the new image
            $namaBook = time() . '-image.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('public/images', $namaBook);
            $path = 'http://localhost:8000' . '/storage/images/';
            $data['image'] = $path .  $namaBook;
        }

        $book->update($data);
        return response()->json([
            'message' => 'Book updated sukses',
        ], 201);
    }

       

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Delete the image
        if ($book->image && Storage::exists('public/images/' . $book->image)) {
            Storage::delete('public/images/' . $book->image);
        }

        $book->delete();
        return response()->json([
            'message' => 'Book sukses dihapus',
        ], 200);
    }
}
