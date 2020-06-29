<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\PJPK;
use App\ProyekPJPK;
use App\InstansiTerkaitPJPK;



class PJPKController extends Controller
{
    //
    public function tambah_pjpk(Request $request) {
        
        $validation = Validator::make(request()->all(),[
            'pjpk' => 'required',
        ]);
        
        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {

            $pjpk = new PJPK;

            $pjpk->lokasi = $request->lokasi;
            $pjpk->pjpk = $request->pjpk;
            $pjpk->pelaksana_tugas = $request->pelaksana_tugas;
            $pjpk->simpul_kpbu = $request->simpul_kpbu;
            
            $pjpk->save();
        

            $output = [
                'err' => null,
                'result' => array(
                    'id_pjpk' => $pjpk->id,
                    'message' => 'Selamat telah berhasil membuat PJPK.'
                )
            ];
            
            return response()->json($output);

        }
        
    }

    public function tambah_proyek(Request $request) {
        
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
            'id_pjpk' => 'required|exists:p_j_p_ks,id',
            'pjpk' => 'required',
        ]);
        
        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {

            $proyek_pjpk = new ProyekPJPK;

            $proyek_pjpk->id_proyek = $request->id_proyek;
            $proyek_pjpk->id_pjpk = $request->id_pjpk;
            $proyek_pjpk->pjpk = $request->pjpk;
            $proyek_pjpk->pic = $request->pic;
            $proyek_pjpk->jabatan = $request->jabatan;
            $proyek_pjpk->instansi = $request->instansi;
            $proyek_pjpk->alamat = $request->alamat;
            $proyek_pjpk->no_telepon = $request->no_telepon;
            $proyek_pjpk->email = $request->email;
            
            
            $proyek_pjpk->save();
        

            $output = [
                'err' => null,
                'result' => array(
                    'id_proyek_pjpk' => $proyek_pjpk->id,
                    'message' => 'Selamat telah berhasil membuat Proyek PJPK.'
                )
            ];
            
            return response()->json($output);

        }
        
    }

    public function tambah_instansi_terkait(Request $request) {
        
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
            'id_pjpk' => 'required|exists:p_j_p_ks,id',
            'instansi' => 'required'
        ]);
        
        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {

            $instansi_pjpk = new InstansiTerkaitPJPK;

            $instansi_pjpk->id_proyek = $request->id_proyek;
            $instansi_pjpk->id_pjpk = $request->id_pjpk;
            $instansi_pjpk->instansi = $request->instansi;
            $instansi_pjpk->relasi = $request->relasi;
            $instansi_pjpk->nama = $request->nama;
            $instansi_pjpk->jabatan = $request->jabatan;
            $instansi_pjpk->alamat = $request->alamat;
            $instansi_pjpk->no_telepon = $request->no_telepon;
            $instansi_pjpk->email = $request->email;
            
            
            $instansi_pjpk->save();
        

            $output = [
                'err' => null,
                'result' => array(
                    'id_instansi_pjpk' => $instansi_pjpk->id,
                    'message' => 'Selamat telah berhasil membuat Intansi Terkait PJPK.'
                )
            ];
            
            return response()->json($output);

        }
        
    }

    public function get_semua_pjpk() {
        $pjpk = PJPK::all();

        $output = [
            'err' => null,
            'result' => array(
                'pjpk' => $pjpk
            )
        ];
        
        return response()->json($output);
    }

    public function get_pjpk_by_id($id) {
        $pjpk = PJPK::find($id);

        if($pjpk == null) {
            $output = [
                'err' => 'PJPK tidak ditemukan',
                'result' => null
            ];
            
            return response()->json($output, 400);

        } else {
            $pjpk->proyek = ProyekPJPK::where('id_pjpk', $id)->get();
            $pjpk->instansi_terkait = InstansiTerkaitPJPK::where('id_pjpk', $id)->get();

            $output = [
                'err' => null,
                'result' => $pjpk
            ];
            
            return response()->json($output);
        }
    }

    public function update_pjpk(Request $request) {
        $pjpk = PJPK::find($request->id_pjpk);

        if($pjpk == null) {
            $output = [
                'err' => 'PJPK tidak ditemukan',
                'result' => null
            ];
            
            return response()->json($output, 400);

        } else {
            $pjpk->update(Input::all());
            
            $output = [
                'err' => null,
                'result' => 'Selamat anda berhasil melakukan update PJPK'
            ];
            
            return response()->json($output);
        }
    }

    public function update_instansi_terkait(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_instansi_pjpk' => 'required|exists:instansi_terkait_p_j_p_ks,id',
            'id_proyek' => 'exists:proyeks,id',
            'email' => 'email',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $instansi_pjpk = InstansiTerkaitPJPK::find($request->id_instansi_pjpk);

            $instansi_pjpk->update(Input::all());
            
            $output = [
                'err' => null,
                'result' => 'Selamat anda berhasil melakukan update Instnasi PJPK'
            ];
            
            return response()->json($output);
        }
    }

    public function update_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek_pjpk' => 'required|exists:proyek_p_j_p_ks,id',
            'id_proyek' => 'exists:proyeks,id',
            'email' => 'email',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $proyek_pjpk = ProyekPJPK::find($request->id_proyek_pjpk);

            $proyek_pjpk->update(Input::all());
            
            $output = [
                'err' => null,
                'result' => 'Selamat anda berhasil melakukan update Proyek PJPK'
            ];
            
            return response()->json($output);
        }
    }

    public function get_proyek_by_pjpk_id($id = 0) {
        if($id != 0) {
            $proyek_pjpk = ProyekPJPK::where('id_pjpk', $id)->get();
        } else {
            $proyek_pjpk = ProyekPJPK::all();
        }
        

        $output = [
            'err' => null,
            'result' => array(
                'proyek_pjpk' => $proyek_pjpk
            )
        ];
        
        return response()->json($output);
    }

    public function get_instansi_terkait_by_pjpk_id($id = 0) {
        if($id != 0) {
            $instansi_pjpk = InstansiTerkaitPJPK::where('id_pjpk', $id)->get();
        } else {
            $instansi_pjpk = InstansiTerkaitPJPK::all();
        }
        

        $output = [
            'err' => null,
            'result' => array(
                'instansi_pjpk' => $instansi_pjpk
            )
        ];
        
        return response()->json($output);
    }


}
