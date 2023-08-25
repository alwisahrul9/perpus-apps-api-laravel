<?php

namespace App\Http\Controllers;

use App\Models\Pinjam;
use App\Models\Buku;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PinjamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getData = DB::table('pinjams')
                    ->join('anggotas', 'anggotas.id', '=', 'pinjams.id_anggota')
                    ->join('bukus', 'bukus.id', '=', 'pinjams.id_buku')
                    ->select('anggotas.*', 'bukus.*', 'pinjams.*')
                    ->where('status', 'active')
                    ->get();
        
        return response()->json([
            'message' => 'Data Didapatkan',
            'data' => $getData
        ]);
    
    }

    public function indexPengembalian()
    {
        $getData = DB::table('pinjams')
                    ->join('anggotas', 'anggotas.id', '=', 'pinjams.id_anggota')
                    ->join('bukus', 'bukus.id', '=', 'pinjams.id_buku')
                    ->select('anggotas.*', 'bukus.*', 'pinjams.*')
                    ->where('status', 'not active')
                    ->get();
        
        return response()->json([
            'message' => 'Data Didapatkan',
            'data' => $getData
        ]);
    
    }

    public function getTotal()
    {
        $getData = DB::table('pinjams')
                    ->join('anggotas', 'anggotas.id', '=', 'pinjams.id_anggota')
                    ->join('bukus', 'bukus.id', '=', 'pinjams.id_buku')
                    ->select('anggotas.*', 'bukus.*', 'pinjams.*')
                    ->where('status', 'active')
                    ->count();
        
        return response()->json([
            'message' => 'Data Didapatkan',
            'data' => $getData
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function search($name)
    {
        $data = DB::table('pinjams')
                ->join('anggotas', 'anggotas.id', '=', 'pinjams.id_anggota')
                ->join('bukus', 'bukus.id', '=', 'pinjams.id_buku')
                ->select('anggotas.*', 'bukus.*', 'pinjams.*')
                ->where('anggotas.name', 'LIKE', '%' . $name . '%')
                ->where('status', 'active')
                ->get();
                
        return response()->json([
            'message' => 'Data Ditemukan!',
            'data' => $data
        ]);
    }

    public function searchPengembalian($name)
    {
        $data = DB::table('pinjams')
                ->join('anggotas', 'anggotas.id', '=', 'pinjams.id_anggota')
                ->join('bukus', 'bukus.id', '=', 'pinjams.id_buku')
                ->select('anggotas.*', 'bukus.*', 'pinjams.*')
                ->where('anggotas.name', 'LIKE', '%' . $name . '%')
                ->where('status', 'not active')
                ->get();
                
        return response()->json([
            'message' => 'Data Ditemukan!',
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id_anggota' => 'required',
            'id_buku' => 'required',
            'tgl_pinjam' => 'required',
            'tgl_kembali' => 'required',
        ]);

        if($validation->fails()) {
            return response()->json([
                'Validation' => 'Validasi Gagal!'
            ]);
        }

        // Cari jumlah buku
        $getJml = Buku::where('id', $request->id_buku)->first();
        $getName = Pinjam::where('id_anggota', $request->id_anggota)
                    ->where('status', 'active')
                    ->get();
        $count = count($getName);

        // Jika stok sudah 0, maka buku sudah habis dan tidak bisa melanjutkan
        if ($getJml->stok <= 0) {

            return response()->json([
                'message' => 'Buku sudah habis!'
            ]);
            
        } else if ($count > 0) {
            
            return response()->json([
                'data' => $count
            ]);

        } else {

            $store = Pinjam::create([
                'id_anggota' => $request->id_anggota,
                'id_buku' => $request->id_buku,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
            ]);
    
            $getData = Buku::where('id', $request->id_buku)->decrement('stok');
    
            if($store) {
                return response()->json([
                    'message' => 'Data Tersimpan!',
                    'data' => $getJml
                ]);
            }

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Pinjam $pinjam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pinjam $pinjam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = Pinjam::find($id);

        $getData = Buku::where('id', $data->id_buku)->increment('stok');

        $data->update([
            'status' => 'not active'
        ]);

        return response()->json([
            'message' => 'Data Tersimpan!',
            'data' => $data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pinjam $pinjam)
    {
        //
    }
}
