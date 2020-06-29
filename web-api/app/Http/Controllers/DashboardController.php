<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyek;

class DashboardController extends Controller
{
    //

    public function get_data_dashboard() {
        $semua_proyek = Proyek::all();
        $total_nilai_proyek = 0;
        
        foreach($semua_proyek as $proyek) {
            $total_nilai_proyek = $total_nilai_proyek + $proyek->nilai_proyek;
        }


        $output = [
            'err' => null,
            'result' => [
                'total_proyek' => count($semua_proyek),
                'total_proyek_sign' => 0,
                'total_nilai_proyek' => $total_nilai_proyek,
                'total_nilai_investasi' => $total_nilai_proyek
            ]
        ];
        
        return response()->json($output); 
    }
}
