<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Buku::orderBy('created_at', 'DESC')->paginate(5);
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
            'judul' => 'required',
            'stok' => 'required',
            'gambar' => 'required|mimes:jpg,png|file|max:3072'
        ]);

        if($validator->fails()){
            return response()->json([
                'Validation' => 'Validasi Gagal!'
            ]);
        }

        $image = $request->file('gambar');
        $image->storeAs('public/gambar', $image->hashName());

        $store = Buku::create([
            'judul' => $request->judul,
            'stok' => $request->stok,
            'gambar' => $image->hashName()
        ]);

        if($store) {
            return response()->json([
                'message' => 'Data Tersimpan',
                'data' => $store
            ]);
        }

        return response()->json([
            'message' => 'Data Gagal Disimpan!'
        ]);
    }

    public function search($judul)
    {
        $data = Buku::where('judul', 'LIKE' , '%' .$judul . '%')->get();
        return $data;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $find = Buku::find($id);

        // if($find) {
        //     return response()->json([
        //         'message' => 'Data Ditemukan!',
        //         'data' => $find
        //     ]);
        // }

        // return response()->json([
        //     'message' => 'Data Tidak Ditemukan!'
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buku $buku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $find = Buku::find($id);

        //check if image is not empty
        if ($request->hasFile('gambar')) {

            //upload image
            $image = $request->file('gambar');
            $image->storeAs('public/gambar', $image->hashName());

            //delete old image
            Storage::delete('public/gambar/' . $find->gambar);

            //update find with new image
            $find->update([
                'judul' => $request->judul,
                'stok' => $request->stok,
                'gambar' => $image->hashName()
            ]);
        } else {

            //update post without image
            $find->update([
                'judul' => $request->judul,
                'stok' => $request->stok,
            ]);
        }

        return response()->json([
            'message' => 'Data Berubah!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Buku::find($id);
        $image_path = ('public/gambar/' . basename($data->gambar));

        if (!$data) {
            return response()->json([
                'message' => 'Data Tidak Ada!'
            ]);
        }

        Storage::delete($image_path);

        $data->delete();

        return response()->json([
            'message' => 'Data Terhapus!'
        ]);
    }
}
