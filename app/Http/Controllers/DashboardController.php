<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pinjam;
use App\Models\Anggota;
use App\Models\Buku;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function jmlPeminjamPerTahun($tahun)
    {
        $bulanMax = 12;
        $array = array();

        for($i = 1; $i <= 12; $i++) {
            $data = Pinjam::whereYear('tgl_pinjam', $tahun)
                            ->whereMonth('tgl_pinjam', sprintf("%02d", $i))->count();
            
            array_push($array, $data);
        }

        return response()->json([
            'data' => $array
        ]);
    }

    public function jmlPeminjamRentang($tahunAwal, $tahunAkhir)
    {
        $setAwal = $tahunAwal;
        $setAkhir = $tahunAkhir;
        $array = [];

        for($i = $setAwal; $i <= $setAkhir; $i++) {

            $getData = Pinjam::whereYear('tgl_pinjam', $i)->count();

            array_push($array, $getData);

        }

        return response()->json([
            'data' => $array
        ]);
        
    }

    public function getPeminjam()
    {
        $tahunMin = Pinjam::orderBy('tgl_pinjam', 'ASC')->first();
        $tahunMax = Pinjam::orderBy('tgl_pinjam', 'DESC')->first();

        return response()->json([
            'min' => $tahunMin,
            'max' => $tahunMax
        ]);
    }

    // public function jmlAnggotaPerTahun($tahun)
    // {
    //     $bulanMax = 12;
    //     $array = array();

    //     for($i = 1; $i <= 12; $i++) {
    //         $data = Anggota::whereYear('created_at', $tahun)->count();
            
    //         array_push($array, $data);
    //     }

    //     return response()->json([
    //         'data' => $array
    //     ]);
    // }
    
    // public function jmlAnggotaRentang($tahunAwal, $tahunAkhir)
    // {
    //     $setAwal = $tahunAwal;
    //     $setAkhir = $tahunAkhir;
    //     $array = [];

    //     for($i = $setAwal; $i <= $setAkhir; $i++) {

    //         $getData = Anggota::whereYear('created_at', $i)->count();

    //         array_push($array, $getData);

    //     }
        
    //     return response()->json([
    //         'data' => $array
    //     ]);
        
    // }

    // public function jmlBukuPerTahun($tahun)
    // {
    //     $bulanMax = 12;
    //     $array = array();

    //     for($i = 1; $i <= 12; $i++) {
    //         $data = Buku::whereYear('created_at', $tahun)->count();
            
    //         array_push($array, $data);
    //     }

    //     return response()->json([
    //         'data' => $array
    //     ]);
    // }

    // public function jmlBukuRentang($tahunAwal, $tahunAkhir)
    // {
    //     $setAwal = $tahunAwal;
    //     $setAkhir = $tahunAkhir;
    //     $array = [];

    //     for($i = $setAwal; $i <= $setAkhir; $i++) {

    //         $getData = Buku::whereYear('created_at', $i)->count();

    //         array_push($array, $getData);

    //     }
        
    //     return response()->json([
    //         'data' => $array
    //     ]);
        
    // }
}
