<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrow;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class BorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'update', 'index']);
        // $this->middleware(['auth:api','isOwner'])->only(['index']);
    }

    /**
     * Display a listing of the borrows.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = auth()->user();
        $borrows = Borrow::all()->load('book', 'user');
        return response()->json([
            'message' => 'Menampilkan semua pinjaman buku',
            'data' => $borrows
        ], 200);
        // // Jika user adalah admin, ambil semua data Borrow
        // if ($user->role->name === 'owner') {
        //     $borrows = Borrow::with(['book', 'user'])->get();
        // } else {
        //     // Jika bukan admin, ambil hanya data Borrow milik user yang login
        //     $borrows = Borrow::with(['book', 'user'])->where('user_id', $user->id)->get();
        // }

        // return response()->json([
        //     message => 'Data peminjaman',
        //     data => $borrows
        // ], 200);
    }

    /**
     * Store a newly created borrow in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'load_date' => 'required|date',
            'borrow_date' => 'required|date',
            'book_id' => 'required|exists:books,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = auth()->user();
        $borrow = Borrow::create([
            'load_date' => $request->load_date,
            'borrow_date' => $request->borrow_date,
            'book_id' => $request->book_id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Peminjaman berhasil dibuat',
            'data' => $borrow
        ], 201);
    }

    /**
     * Display the specified borrow.
     *
     * @param  \App\Models\Borrow  $borrow
     * @return \Illuminate\Http\Response
     */
    public function show(Borrow $borrow)
    {
        return response()->json($borrow->load(['book', 'user']));
    }

    /**
     * Update the specified borrow in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Borrow  $borrow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Borrow $borrow)
    {
        $validator = Validator::make($request->all(), [
            'load_date' => 'required|date',
            'borrow_date' => 'required|date',
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $borrow->update([
            'load_date' => $request->load_date,
            'borrow_date' => $request->borrow_date,
            'book_id' => $request->book_id,
            'user_id' => $request->user_id,
        ]);

        return response()->json($borrow);
    }

    /**
     * Remove the specified borrow from storage.
     *
     * @param  \App\Models\Borrow  $borrow
     * @return \Illuminate\Http\Response
     */
    public function destroy(Borrow $borrow)
    {
        $borrow->delete();
        return response()->json(null, 204);
    }
}
