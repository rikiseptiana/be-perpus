<?php
// app/Http/Controllers/API/UserController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Tampilkan semua user
    public function index()
    {
        $users = User::all();
        return response()->json([
            'message' => 'Menampilkan semua User!',
            'users' => $users
        ], 200);
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $user = User::create($request->all());
        return response()->json([
            'message' => 'Berhasil Membuat User!',
            'user' => $user
        ], 201);
    }

    // Tampilkan detail user
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json([
            'message' => 'User Berhasil Memperbarui!',
            'user' => $user,
        ], 200);
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User Berhasil Dihapus!'], 204);
    }
}
