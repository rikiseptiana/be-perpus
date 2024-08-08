<?php
// app/Http/Controllers/API/RoleController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    // Menambahkan middleware pada constructor
    public function __construct()
    {
        $this->middleware(['auth:api', 'isOwner']);
    }
    public function index()
    {
        $role = Role::all();
        return response()->json([
            'message' => 'Data berhasil ditampilkan',
            'data' => $role
        ], 200);
    }
    // Fungsi untuk menambahkan role
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $role = Role::create($request->all());
        return response()->json([
            'message' => 'Data role berhasil ditambahkan',
            'data' => $role
        ], 201);
    }

    public function show($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role tidak ditemukan'], 404);
        }
        return response()->json($role, 200);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $role = Role::findOrFail($id);
        $role->update($request->all());
        return response()->json([
            'message' => 'Data role berhasil diupdate',
            'data' => $role
        ], 200);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if (!$role) {
            return response()->json(['message' => 'Role tidak ditemukan'], 404);
        }
        $role->delete();
        return response()->json([
            "message" => "data dengan id : $id telah berhasil dihapus"
        ]);
    }
}
