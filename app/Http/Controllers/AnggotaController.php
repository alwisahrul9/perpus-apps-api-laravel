<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Anggota::orderBy('created_at', 'DESC')->paginate(5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'Validation' => 'Validasi Gagal!'
            ]);
        }

        $store = Anggota::create([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ]);

        if($store) {
            return response()->json([
                'message' => 'Data Tersimpan',
                'data' => $store
            ]);
        }
    }

    public function search($name)
    {
        $data = Anggota::where('name', 'LIKE' , '%' .$name . '%')->get();
        return response()->json([
            'message' => 'Data Ditemukan!',
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $find = Anggota::find($id);

        if($find) {
            return response()->json([
                'message' => 'Data Ditemukan!',
                'data' => $find
            ]);
        }

        return response()->json([
            'message' => 'Data Tidak Ditemukan!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anggota $anggota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $find = Anggota::find($id);

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required'
        ]);

        if($validation->fails()){
            return response()->json([
                'Validation' => 'Validasi Gagal!'
            ]);
        }

        $store = $find->update([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ]);

        if($store) {
            return response()->json([
                'message' => 'Data Terupdate',
                'data' => $store
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $find = Anggota::find($id);

        if($find){

            $find->delete();

            return response()->json([
                'message' => 'Data Terhapus'
            ]);
        }

        return response()->json([
            'message' => 'Data Gagal'
        ]);
    }
}
