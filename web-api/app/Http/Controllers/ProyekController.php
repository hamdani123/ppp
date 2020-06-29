<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\Proyek;
use App\TimelineProyek;
use App\TahapanProyek;
use App\KategoriProyek;
use App\TahapanProyekDropdown;
use App\KegiatanProyekDropdown;
use App\JenisInfrastruktur;
use App\IssueProyek;
use App\DokumenTahapanProyek;
use App\AnalisisResiko;
use App\PenjaminanProyek;
use App\PemegangSaham;
use App\BadanUsaha;
use App\HistoryTahapan;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


use DB;

class ProyekController extends Controller
{
    //
    public function tambah_proyek(Request $request) {
        
        $validation = Validator::make(request()->all(),[
            'image' => 'mimes:jpeg,jpg,png,gif',
            'nama_proyek' => 'required',
            'dukungan_vgf' => 'numeric',
            'jaminan_proyek' => 'boolean',
            'nilai_investasi' => 'numeric',
            'kurs' => 'numeric',
        ]);
        
        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            

            $proyek = Proyek::create(Input::all());
            if($request->has('image')) {
                $file = $request->file('image');

                $dataImg = $file;
                $t = microtime(true);
                $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
                $timestamp = date('YmdHis' . $micro, $t) . "_" . rand(0, 1000);

                $ext_file = $dataImg->getClientOriginalExtension();
                $name_file = $timestamp . '_img_item.' . $ext_file;
                $path_file = public_path() . '/image/proyek/';

                if(!$dataImg->move($path_file,$name_file)) {
                    $output = [
                        'err' => 'error dalam melakukan upload gambar',
                        'result' => null
                    ];
                    
                    return response()->json($output, 400);
                }

                $proyek->image = $name_file;

                $proyek->save();
            }

            

            $kategoris = explode('|', $request->kategori_proyek);

            foreach($kategoris as $kategori) {
                if($kategori != ''){
                    $obj_kategori = new KategoriProyek;
                
                    $obj_kategori->id_proyek = $proyek->id;
                    $obj_kategori->nama_kategori = $kategori;

                    $obj_kategori->save();
                }
                
            }

            $output = [
                'err' => null,
                'result' => array(
                    'id_proyek' => $proyek->id,
                    'message' => 'Selamat telah berhasil membuat proyek.'
                )
            ];
            
            return response()->json($output);

        }
        
    }

    public function update_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
            'dukungan_vgf' => 'numeric',
            'image' => 'mimes:jpeg,jpg,png,gif',
            'jaminan_proyek' => 'boolean',
            'nilai_investasi' => 'numeric',
            'kurs' => 'numeric',
            
        ]);
        
        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $proyek = Proyek::find($request->id_proyek);

            $proyek->update(Input::all());

            if($request->has('image')) {
                $file = $request->file('image');

                $dataImg = $file;
                $t = microtime(true);
                $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
                $timestamp = date('YmdHis' . $micro, $t) . "_" . rand(0, 1000);

                $ext_file = $dataImg->getClientOriginalExtension();
                $name_file = $timestamp . '_img_item.' . $ext_file;
                $path_file = public_path() . '/image/proyek/';

                if(!$dataImg->move($path_file,$name_file)) {
                    $output = [
                        'err' => 'error dalam melakukan upload gambar',
                        'result' => null
                    ];
                    
                    return response()->json($output, 400);
                }

                $proyek->image = $name_file;

                $proyek->save();
            }

            if($request->has('kategori_proyek')) {
                $deleted_kategori_proyek = KategoriProyek::where('id_proyek', $proyek->id)->delete();
                $kategoris = explode('|', $request->kategori_proyek);

                foreach($kategoris as $kategori) {
                    if($kategori != ''){
                        $obj_kategori = new KategoriProyek;
                    
                        $obj_kategori->id_proyek = $proyek->id;
                        $obj_kategori->nama_kategori = $kategori;

                        $obj_kategori->save();
                    }
                    
                }
            }

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil update proyek.'
            ];
            
            return response()->json($output);
        }
    }

    public function get_semua_proyek() {
        $final_proyek = [];
        $proyeks = Proyek::all();

        foreach($proyeks as $proyek) {
            $temp_proyek = $this->get_semua_property_proyek($proyek);
            array_push($final_proyek, $temp_proyek);
        }

        $output = [
            'err' => null,
            'result' => $final_proyek
        ];
        
        return response()->json($output);
    }

    public function get_proyek_by_id($id) {
        $proyek = Proyek::find($id);

        if($proyek == null) {
            $output = [
                'err' => 'Proyek tidak ditemukan',
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $proyek = $this->get_semua_property_proyek($proyek);

            $output = [
                'err' => null,
                'result' => $proyek
            ];
            
            return response()->json($output);
        }
    }

    private function get_semua_property_proyek($proyek) {
        $final_kategori_proyek = [];

        $semua_kategori_proyek = KategoriProyek::where('id_proyek', $proyek->id)->get();
        
        foreach($semua_kategori_proyek as $kategori_proyek) {
            array_push($final_kategori_proyek, $kategori_proyek->nama_kategori);
        }

        $proyek->kategori = $final_kategori_proyek;
        $proyek->image = url('/').'/image/proyek/'.$proyek->image;
        $proyek->timeline = TimelineProyek::where('id_proyek', $proyek->id)->get();
        $proyek->tahapan = TahapanProyek::where('id_proyek', $proyek->id)->get();

        $posisi_timeline = TimelineProyek::find($proyek->posisi_timeline);

        if($posisi_timeline == null) {
            $posisi_timeline = TimelineProyek::where('id_proyek', $proyek->id)->first();
        }

        if($posisi_timeline != null) {

            if($posisi_timeline->plan_tanggal_selesai != null && $posisi_timeline->plan_tanggal_selesai != "") {
                $plan_tanggal_selesai = date_create($posisi_timeline->plan_tanggal_selesai);

                if($posisi_timeline->actual_tanggal_selesai != null) {
                    $actual_tanggal_selesai = date_create($posisi_timeline->actual_tanggal_selesai);
                } else {
                    $actual_tanggal_selesai = date_create();
                }
                
                $hari_ini = date_create();

                $date_diff_plan_actual = date_diff($plan_tanggal_selesai, $actual_tanggal_selesai);
                $perbedaan_plan_actual = $date_diff_plan_actual->format("%R%a");
                $perbedaan_plan_actual = intval($perbedaan_plan_actual);

                $date_diff_plan_hari_ini = date_diff($plan_tanggal_selesai, $hari_ini);
                $perbedaan_plan_hari_ini = $date_diff_plan_hari_ini->format("%R%a");
                $perbedaan_plan_hari_ini = intval($perbedaan_plan_hari_ini);


                if($posisi_timeline->actual_tanggal_selesai != null) {
                    if($perbedaan_plan_actual > 0 ) {
                        $posisi_timeline->status = "red";
                    }else if($perbedaan_plan_actual < 0 ) {
                        $posisi_timeline->status = "green";
                    } else {
                        $posisi_timeline->status = "white";
                    }
                } else {
                    if($perbedaan_plan_hari_ini > -10) {
                        $posisi_timeline->status = "yellow";
                    } else if($perbedaan_plan_hari_ini > 0) {
                        $posisi_timeline->status = "red";
                    } else {
                        $posisi_timeline->status = "white";
                    }
                }
            } else {
                $posisi_timeline->status = "white";
            }
            
            
            
            
        }

        $proyek->posisi_timeline = $posisi_timeline;
        // $proyek->posisi_proyek = TahapanProyek::where('id_proyek', $proyek->id)->orderBy('tanggal_info','desc')->first();
        $proyek->posisi_proyek = DB::table('tahapan_proyeks')
        ->where('id_proyek', $proyek->id)
        ->where('tanggal_info','<>','')
        ->orderBy('id', 'DESC')
        ->first();

        return $proyek;
    }

    public function update_posisi_timeline(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
            'id_timeline_proyek' => 'required|exists:timeline_proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $proyek = Proyek::find($request->id_proyek);
            $timeline_proyek = TimelineProyek::find($request->id_timeline_proyek);

            if($timeline_proyek->id_proyek != $proyek->id) {
                $output = [
                    'err' => 'Id Timeline tidak cocok dengan proyek',
                    'result' => null
                ];
                
                return response()->json($output, 400);
            } else {
                $proyek->posisi_timeline = $timeline_proyek->id;

                $proyek->save();

                $output = [
                    'err' => null,
                    'result' => 'Selamat telah berhasil melakukan update timeline proyek.'
                ];
                
                return response()->json($output);
            }


        }
    }

    public function tambah_timeline_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $timeline_proyek = TimelineProyek::create(Input::all());

            
            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil membuat timeline proyek.'
            ];
            
            return response()->json($output);
        }
    }

    public function update_timeline_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_timeline_proyek' => 'required|exists:timeline_proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $timeline_proyek = TimelineProyek::find($request->id_timeline_proyek);

            $timeline_proyek->update(Input::all());

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil melakukan update timeline proyek.'
            ];
            
            return response()->json($output);
            
        }

    }

    public function get_timeline_all() {
        $timeline_proyek = TimelineProyek::all();
        $output = [
            'err' => null,
            'result' => $timeline_proyek
        ];
        
        return response()->json($output);
    }

    public function get_timeline_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $timeline_proyek = TimelineProyek::where('id_proyek', $request->id_proyek)->get();
            $output = [
                'err' => null,
                'result' => $timeline_proyek
            ];
            
            return response()->json($output);
        }
    }

    public function delete_timeline_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
            'id_timeline_proyek' => 'required|exists:timeline_proyeks,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $timeline_proyek = TimelineProyek::where('id', $request->id_timeline_proyek)->delete();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil menghapus timeline proyek.'
            ];
            
            return response()->json($output);
        }
    }

    public function tambah_tahapan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
            
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            try {
                if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(Utils::format_error('user_not_found'), 404);
                }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(Utils::format_error('token_expired'), $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(Utils::format_error('token_invalid'), $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(Utils::format_error('token_absent'), $e->getStatusCode());
            }
    
            $tahapan_proyek = new TahapanProyek;

            $tahapan_proyek->id_proyek = $request->id_proyek;
            $tahapan_proyek->nama_tahapan = $request->nama_tahapan;
            $tahapan_proyek->nama_kegiatan = $request->nama_kegiatan;
            $tahapan_proyek->nama_sub_kegiatan = $request->nama_sub_kegiatan;
            $tahapan_proyek->keterangan = $request->keterangan;
            $tahapan_proyek->tanggal_info = $request->tanggal_info;
            $tahapan_proyek->sumber_info = $request->sumber_info;
            $tahapan_proyek->penanggung_jawab = $request->penanggung_jawab;
            $tahapan_proyek->status = $request->status;

            if($request->has('tindak_lanjut')){
                if($request->tindak_lanjut != null && $request->tindak_lanjut != ""){
                    $tahapan_proyek->tindak_lanjut = $request->tindak_lanjut;
                }
            }

            if($request->has('pihak_terkait')){
                if($request->pihak_terkait != null && $request->pihak_terkait != ""){
                    $tahapan_proyek->pihak_terkait = $request->pihak_terkait;
                }
            }

            if($request->has('target_penyelesaian')){
                if($request->target_penyelesaian != null && $request->target_penyelesaian != ""){
                    $tahapan_proyek->target_penyelesaian = $request->target_penyelesaian;
                }
            }

            if($request->has('tanggal_selesai')){
                if($request->tanggal_selesai != null && $request->tanggal_selesai != ""){
                    $tahapan_proyek->tanggal_selesai = $request->tanggal_selesai;
                }
            }

            // if($request->has('file_upload')){
            //     $file = $request->file('file_upload');

            //     $dataFile = $file;
            //     $t = microtime(true);
            //     $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            //     $timestamp = date('YmdHis' . $micro, $t) . "_" . rand(0, 1000);

            //     $ext_file = $dataFile->getClientOriginalExtension();
            //     $name_file = $timestamp . '_img_item.' . $ext_file;
            //     $path_file = public_path() . '/file/proyek/tahapan';

            //     if(!$dataFile->move($path_file,$name_file)) {
            //         $output = [
            //             'err' => 'error dalam melakukan upload gambar',
            //             'result' => null
            //         ];
                    
            //         return response()->json($output, 400);
            //     }

            //     $tahapan_proyek->file_upload = $name_file;
            // }

            

            $tahapan_proyek->save();

            $history_tahapan = new HistoryTahapan;

            $history_tahapan->username = $user->username;
            $history_tahapan->action = 'CREATE';
            $history_tahapan->id_tahapan = $tahapan_proyek->id;
            $history_tahapan->id_proyek = $tahapan_proyek->id_proyek;
            $history_tahapan->nama_tahapan = $tahapan_proyek->nama_tahapan;
            $history_tahapan->nama_kegiatan = $tahapan_proyek->nama_kegiatan;
            $history_tahapan->nama_sub_kegiatan = $tahapan_proyek->nama_sub_kegiatan;
            $history_tahapan->keterangan = $tahapan_proyek->keterangan;
            $history_tahapan->tanggal_info = $tahapan_proyek->tanggal_info;
            $history_tahapan->sumber_info = $tahapan_proyek->sumber_info;
            $history_tahapan->tindak_lanjut = $tahapan_proyek->tindak_lanjut;
            $history_tahapan->penanggung_jawab = $tahapan_proyek->penanggung_jawab;
            $history_tahapan->pihak_terkait = $tahapan_proyek->pihak_terkait;
            $history_tahapan->target_penyelesaian = $tahapan_proyek->target_penyelesaian;
            $history_tahapan->status = $tahapan_proyek->status;
            $history_tahapan->tanggal_selesai = $tahapan_proyek->tanggal_selesai;

            $history_tahapan->save();

            $output = [
                'err' => null,
                'result' => array(
                    'id_tahapan_proyek' => $tahapan_proyek->id,
                    'message' =>'Selamat telah berhasil melakukan update timeline proyek.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function delete_tahapan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
            'id_tahapan_proyek' => 'required|exists:tahapan_proyeks,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            try {
                if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(Utils::format_error('user_not_found'), 404);
                }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(Utils::format_error('token_expired'), $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(Utils::format_error('token_invalid'), $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(Utils::format_error('token_absent'), $e->getStatusCode());
            }

            $tahapan_proyek = TahapanProyek::where('id', $request->id_tahapan_proyek)->first();
            
            if($tahapan_proyek != null) {
                $history_tahapan = new HistoryTahapan;

                $history_tahapan->username = $user->username;
                $history_tahapan->action = 'DELETE';
                $history_tahapan->id_tahapan = $tahapan_proyek->id;
                $history_tahapan->id_proyek = $tahapan_proyek->id_proyek;
                $history_tahapan->nama_tahapan = $tahapan_proyek->nama_tahapan;
                $history_tahapan->nama_kegiatan = $tahapan_proyek->nama_kegiatan;
                $history_tahapan->nama_sub_kegiatan = $tahapan_proyek->nama_sub_kegiatan;
                $history_tahapan->keterangan = $tahapan_proyek->keterangan;
                $history_tahapan->tanggal_info = $tahapan_proyek->tanggal_info;
                $history_tahapan->sumber_info = $tahapan_proyek->sumber_info;
                $history_tahapan->tindak_lanjut = $tahapan_proyek->tindak_lanjut;
                $history_tahapan->penanggung_jawab = $tahapan_proyek->penanggung_jawab;
                $history_tahapan->pihak_terkait = $tahapan_proyek->pihak_terkait;
                $history_tahapan->target_penyelesaian = $tahapan_proyek->target_penyelesaian;
                $history_tahapan->status = $tahapan_proyek->status;
                $history_tahapan->tanggal_selesai = $tahapan_proyek->tanggal_selesai;

                $history_tahapan->save();

                $tahapan_proyek->delete();
            }
            
            // $issue_proyek = IssueProyek::where('id_tahapan', $request->id_tahapan_proyek)->delete();
            // $dokumen_tahapan_proyek = DokumenTahapanProyek::where('id_tahapan', $request->id_tahapan_proyek)->delete();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil menghapus tahapan proyek.'
            ];
            
            return response()->json($output);
        }
    }

    public function update_tahapan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_tahapan_proyek' => 'required|exists:tahapan_proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            try {
                if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(Utils::format_error('user_not_found'), 404);
                }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(Utils::format_error('token_expired'), $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(Utils::format_error('token_invalid'), $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(Utils::format_error('token_absent'), $e->getStatusCode());
            }

            $tahapan_proyek = TahapanProyek::find($request->id_tahapan_proyek);

            if($request->has('nama_tahapan')){
                if($request->nama_tahapan != null && $request->nama_tahapan != ""){
                    $tahapan_proyek->nama_tahapan = $request->nama_tahapan;
                }
            }
            
            if($request->has('nama_kegiatan')){
                if($request->nama_kegiatan != null && $request->nama_kegiatan != ""){
                    $tahapan_proyek->nama_kegiatan = $request->nama_kegiatan;
                }
            }

            if($request->has('nama_sub_kegiatan')){
                if($request->nama_sub_kegiatan != null && $request->nama_sub_kegiatan != ""){
                    $tahapan_proyek->nama_sub_kegiatan = $request->nama_sub_kegiatan;
                }
            }

            if($request->has('keterangan')){
                if($request->keterangan != null && $request->keterangan != ""){
                    $tahapan_proyek->keterangan = $request->keterangan;
                }
            }

            if($request->has('tanggal_info')){
                if($request->tanggal_info != null && $request->tanggal_info != ""){
                    $tahapan_proyek->tanggal_info = $request->tanggal_info;
                }
            }

            if($request->has('sumber_info')){
                if($request->sumber_info != null && $request->sumber_info != ""){
                    $tahapan_proyek->sumber_info = $request->sumber_info;
                }
            }

            if($request->has('tindak_lanjut')){
                if($request->tindak_lanjut != null && $request->tindak_lanjut != ""){
                    $tahapan_proyek->tindak_lanjut = $request->tindak_lanjut;
                }
            }

            if($request->has('penanggung_jawab')){
                if($request->penanggung_jawab != null && $request->penanggung_jawab != ""){
                    $tahapan_proyek->penanggung_jawab = $request->penanggung_jawab;
                }
            }

            if($request->has('pihak_terkait')){
                if($request->pihak_terkait != null && $request->pihak_terkait != ""){
                    $tahapan_proyek->pihak_terkait = $request->pihak_terkait;
                }
            }

            if($request->has('target_penyelesaian')){
                if($request->target_penyelesaian != null && $request->target_penyelesaian != ""){
                    $tahapan_proyek->target_penyelesaian = $request->target_penyelesaian;
                }
            }

            if($request->has('status')){
                if($request->status != null && $request->status != ""){
                    $tahapan_proyek->status = $request->status;
                }
            }

            if($request->has('tanggal_selesai')){
                if($request->tanggal_selesai != null && $request->tanggal_selesai != ""){
                    $tahapan_proyek->tanggal_selesai = $request->tanggal_selesai;
                }
            }

            // if($request->has('file_upload')){
            //     $file = $request->file('file_upload');

            //     $dataFile = $file;
            //     $t = microtime(true);
            //     $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            //     $timestamp = date('YmdHis' . $micro, $t) . "_" . rand(0, 1000);

            //     $ext_file = $dataFile->getClientOriginalExtension();
            //     $name_file = $timestamp . '_img_item.' . $ext_file;
            //     $path_file = public_path() . '/file/proyek/tahapan';

            //     if(!$dataFile->move($path_file,$name_file)) {
            //         $output = [
            //             'err' => 'error dalam melakukan upload gambar',
            //             'result' => null
            //         ];
                    
            //         return response()->json($output, 400);
            //     }

            //     $tahapan_proyek->file_upload = $name_file;
            // }
            

            $tahapan_proyek->save();

            $history_tahapan = new HistoryTahapan;

            $history_tahapan->username = $user->username;
            $history_tahapan->action = 'UPDATE';
            $history_tahapan->id_tahapan = $tahapan_proyek->id;
            $history_tahapan->id_proyek = $tahapan_proyek->id_proyek;
            $history_tahapan->nama_tahapan = $tahapan_proyek->nama_tahapan;
            $history_tahapan->nama_kegiatan = $tahapan_proyek->nama_kegiatan;
            $history_tahapan->nama_sub_kegiatan = $tahapan_proyek->nama_sub_kegiatan;
            $history_tahapan->keterangan = $tahapan_proyek->keterangan;
            $history_tahapan->tanggal_info = $tahapan_proyek->tanggal_info;
            $history_tahapan->sumber_info = $tahapan_proyek->sumber_info;
            $history_tahapan->tindak_lanjut = $tahapan_proyek->tindak_lanjut;
            $history_tahapan->penanggung_jawab = $tahapan_proyek->penanggung_jawab;
            $history_tahapan->pihak_terkait = $tahapan_proyek->pihak_terkait;
            $history_tahapan->target_penyelesaian = $tahapan_proyek->target_penyelesaian;
            $history_tahapan->status = $tahapan_proyek->status;
            $history_tahapan->tanggal_selesai = $tahapan_proyek->tanggal_selesai;

            $history_tahapan->save();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil melakukan update timeline proyek.'
            ];
            
            return response()->json($output);
            
        }

    }

    public function get_tahapan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $tahapan_proyek = TahapanProyek::where('id_proyek', $request->id_proyek)->get();
            $output = [
                'err' => null,
                'result' => $tahapan_proyek
            ];
            
            return response()->json($output);
        }
    }

    public function get_tahapan_proyek_with_deleted(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $tahapan_proyek = TahapanProyek::withTrashed()->where('id_proyek', $request->id_proyek)->get();
            $output = [
                'err' => null,
                'result' => $tahapan_proyek
            ];
            
            return response()->json($output);
        }
    }

    public function tambah_dokumen_tahapan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_tahapan' => 'required|exists:tahapan_proyeks,id',
            'file' => 'required|unique:users,nama_dokumen',
            'jenis_dokumen' => 'required',
            'file' => 'required|file',
            
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $dokumen_tahapan_proyek = DokumenTahapanProyek::create(Input::all());
            $dokumen_tahapan_proyek->setAttribute('file', $request->file);
            $dokumen_tahapan_proyek->save();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil membuat dokumen tahapan proyek.'
            ];
            
            return response()->json($output);
        }
    }

    public function update_dokumen_tahapan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_dokumen_tahapan_proyeks' => 'required|exists:dokumen_tahapan_proyeks,id',
            'id_tahapan' => 'exists:tahapan_proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $dokumen_tahapan_proyek = DokumenTahapanProyek::find($request->id_dokumen_tahapan_proyeks);
            
            if($request->has('nama_dokumen')) {
                $oldname = public_path() . '/file/proyek/tahapan/' . $dokumen_tahapan_proyek->original_file;
                $name_file = str_replace(" ","_",$request->nama_dokumen) . '.' . substr($dokumen_tahapan_proyek->original_file, strrpos($dokumen_tahapan_proyek->original_file, ".") + 1);
                $newname = public_path() . '/file/proyek/tahapan/' . $name_file ;
                rename($oldname, $newname);

                $dokumen_tahapan_proyek->setAttribute('original_file', $name_file);
                $dokumen_tahapan_proyek->save();
            }
            
            $dokumen_tahapan_proyek->update(Input::all());

            if($request->has('file')) {
                $dokumen_tahapan_proyek->setAttribute('file', $request->file);
                $dokumen_tahapan_proyek->save();
            }

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil update dokumen tahapan proyek.'
            ];
            
            return response()->json($output);

        }
    }

    public function delete_dokumen_tahapan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_dokumen_tahapan_proyeks' => 'required|exists:dokumen_tahapan_proyeks,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $dokumen_tahapan_proyek = DokumenTahapanProyek::where('id', $request->id_dokumen_tahapan_proyeks)->delete();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil mendelete dokumen proyek.'
            ];
            
            return response()->json($output);

        }
    }

    public function get_dokumen_tahapan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'exists:proyeks,id',
            'id_tahapan' => 'exists:tahapan_proyeks,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $dokumen_tahapan_proyek = [];

            if($request->has('id_tahapan')) {
                $dokumen_tahapan_proyek = DokumenTahapanProyek::where('id_tahapan', $request->id_tahapan)->get();
            } else if($request->has('id_proyek')) {
                $dokumen_tahapan_proyek = DokumenTahapanProyek::where('id_proyek', $request->id_proyek)->get();
            }
            

            $output = [
                'err' => null,
                'result' => array(
                    'dokumen_tahapan_proyek' => $dokumen_tahapan_proyek
                )
            ];
            
            return response()->json($output);

        }
    }

    public function get_tahapan_proyek_dropdown(Request $request) {
        $final_tahapans = [];

        $tahapans = TahapanProyekDropdown::where('tipe', $request->tipe)->get();

        foreach($tahapans as $tahapan) {
            $tahapan->kegiatan = KegiatanProyekDropdown::where('id_tahapan', $tahapan->id)->get();

            array_push($final_tahapans, $tahapan);
        }

        $output = [
            'err' => null,
            'result' => $final_tahapans
        ];
        
        return response()->json($output);

    }

    public function get_jenis_infrastruktur() {
        $jenis_infratruktur = JenisInfrastruktur::all();

        $output = [
            'err' => null,
            'result' => $jenis_infratruktur
        ];
        
        return response()->json($output);
    }

    public function tambah_issue(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_tahapan' => 'required|exists:tahapan_proyeks,id',
            'deskripsi' => 'required',
            
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $issue_proyek = IssueProyek::create(Input::all());
            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil membuat issue proyek.'
            ];
            
            return response()->json($output);
        }
    }

    public function delete_issue(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_issue_proyeks' => 'required|exists:issue_proyeks,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $issue_proyek = IssueProyek::where('id', $request->id_issue_proyeks)->delete();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil mendelete issue proyek.'
            ];
            
            return response()->json($output);

        }
    }

    public function update_issue(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_issue_proyeks' => 'required|exists:issue_proyeks,id',
            'id_tahapan' => 'exists:tahapan_proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $issue_proyek = IssueProyek::find($request->id_issue_proyeks);
            
            $issue_proyek->update(Input::all());

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil update issue proyek.'
            ];
            
            return response()->json($output);

        }
    }

    public function get_semua_issue() {
        $issue_proyek = IssueProyek::all();

        $output = [
            'err' => null,
            'result' => array(
                'issue_proyek' => $issue_proyek
            )
        ];
        
        return response()->json($output);
    }

    public function get_issue_by_proyek_or_tahapan(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'exists:proyeks,id',
            'id_tahapan' => 'exists:tahapan_proyeks,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $issue_proyek = [];

            if($request->has('id_tahapan')) {
                $issue_proyek = IssueProyek::where('id_tahapan', $request->id_tahapan)->get();
            } else if($request->has('id_proyek')) {
                $issue_proyek = IssueProyek::where('id_proyek', $request->id_proyek)->get();
            }
            

            $output = [
                'err' => null,
                'result' => array(
                    'issue_proyek' => $issue_proyek
                )
            ];
            
            return response()->json($output);

        }
        

        
    }

    public function delete_proyek_by_id(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $proyek = Proyek::where('id', $request->id_proyek)->delete();
            $issue_proyek = IssueProyek::where('id_proyek', $request->id_proyek)->delete();
            $dokumen_tahapan_proyek = DokumenTahapanProyek::where('id_proyek', $request->id_proyek)->delete();
            $kategori_proyek = KategoriProyek::where('id_proyek', $request->id_proyek)->delete();
            $tahapan_proyek = TahapanProyek::where('id_proyek', $request->id_proyek)->delete();
            $timeline_proyek = TimelineProyek::where('id_proyek', $request->id_proyek)->delete();
            $analisis_resiko = AnalisisResiko::where('id_proyek', $request->id_proyek)->delete();
            $penjaminan_proyek = PenjaminanProyek::where('id_proyek', $request->id_proyek)->delete();


            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil mendelete proyek.'
            ];
            
            return response()->json($output);

        }
    }

    public function tambah_penjaminan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
            'durasi_penjaminan' => 'numeric',
            'nilai_maksimal' => 'numeric',
            
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $penjaminan_proyek = PenjaminanProyek::create(Input::all());

            $output = [
                'err' => null,
                'result' => array(
                    'id_penjaminan_proyek' => $penjaminan_proyek->id,
                    'message' => 'Selamat telah berhasil membuat penjaminan proyek.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function update_penjaminan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_penjaminan_proyek' => 'required|exists:penjaminan_proyeks,id',
            'id_proyek' => 'exists:proyeks,id',
            'durasi_penjaminan' => 'numeric',
            'nilai_maksimal' => 'numeric',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $penjaminan_proyek = PenjaminanProyek::find($request->id_penjaminan_proyek);
            
            $penjaminan_proyek->update(Input::all());

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil update penjaminan proyek.'
            ];
            
            return response()->json($output);

        }
    }

    public function delete_penjaminan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_penjaminan_proyek' => 'required|exists:penjaminan_proyeks,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $penjaminan_proyek = PenjaminanProyek::where('id', $request->id_penjaminan_proyek)->delete();
            $analisis_resiko = AnalisisResiko::where('id_penjaminan', $request->id_penjaminan_proyek)->delete();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil mendelete penjaminan proyek.'
            ];
            
            return response()->json($output);

        }
    }

    public function get_penjaminan_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'exists:proyeks,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $penjaminan_proyek = PenjaminanProyek::where('id_proyek', $request->id_proyek)->first();
            
            $output = [
                'err' => null,
                'result' => array(
                    'penjaminan_proyek' => $penjaminan_proyek
                )
            ];
            
            return response()->json($output);

        }
    }

    public function tambah_analisis_resiko(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_penjaminan' => 'required|exists:penjaminan_proyeks,id',
            'nilai' => 'numeric',
            
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $analisis_resiko = AnalisisResiko::create(Input::all());

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil membuat analisis resiko proyek.'
            ];
            
            return response()->json($output);
        }
    }

    public function update_analisis_resiko(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_analisis_resiko' => 'required|exists:analisis_resikos,id',
            'id_penjaminan' => 'exists:penjaminan_proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $analisis_resiko = AnalisisResiko::find($request->id_analisis_resiko);
            
            $analisis_resiko->update(Input::all());

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil update analisis resiko proyek.'
            ];
            
            return response()->json($output);

        }
    }

    public function delete_analisis_resiko(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_analisis_resiko' => 'required|exists:analisis_resikos,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $analisis_resiko = AnalisisResiko::where('id', $request->id_analisis_resiko)->delete();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil mendelete analisis resiko proyek.'
            ];
            
            return response()->json($output);

        }
    }

    public function get_analisis_resiko(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'exists:proyeks,id',
            'id_penjaminan' => 'exists:penjaminan_proyeks,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $analisis_resiko = [];

            if($request->has('id_penjaminan')) {
                $analisis_resiko = AnalisisResiko::where('id_penjaminan', $request->id_penjaminan)->get();
            } else if($request->has('id_proyek')) {
                $analisis_resiko = AnalisisResiko::where('id_proyek', $request->id_proyek)->get();
            }
            

            $output = [
                'err' => null,
                'result' => array(
                    'analisis_resiko' => $analisis_resiko
                )
            ];
            
            return response()->json($output);

        }
    }

    public function tambah_pemegang_saham_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_badan_usaha' => 'required|exists:badan_usahas,id',
            'nama' => 'required',
            'saham' => 'required|numeric',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $pemegang_saham = PemegangSaham::create(Input::all());

            
            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil membuat pemegang saham.'
            ];
            
            return response()->json($output);
        }
    }

    public function update_pemegang_saham_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_badan_usaha' => 'required|exists:badan_usahas,id',
            'id_pemegang_saham' => 'required|exists:pemegang_sahams,id',
            'saham' => 'numeric',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $pemegang_saham = PemegangSaham::find($request->id_pemegang_saham);

            $pemegang_saham->update(Input::all());

            
            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil mengupdate pemegang saham.'
            ];
            
            return response()->json($output);
        }
    }

    public function get_pemegang_saham_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_badan_usaha' => 'required|exists:badan_usahas,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $pemegang_saham = PemegangSaham::where('id_badan_usaha', $request->id_badan_usaha)->get();
            $output = [
                'err' => null,
                'result' => $pemegang_saham
            ];
            
            return response()->json($output);
        }
    }

    public function delete_pemegang_saham_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_pemegang_saham' => 'required|exists:pemegang_sahams,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $pemegang_saham = PemegangSaham::where('id', $request->id_pemegang_saham)->delete();

            
            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil mendelete pemegang saham.'
            ];
            
            return response()->json($output);
        }
    }

    public function tambah_badan_usaha(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
            'nama' => 'required',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $badan_usaha = BadanUsaha::create(Input::all());

            
            $output = [
                'err' => null,
                'result' => [
                    'id_badan_usaha' => $badan_usaha->id,
                    'message' => 'Selamat telah berhasil membuat badan usaha.'
                ]
            ];
            
            return response()->json($output);
        }
    }

    public function update_badan_usaha(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_badan_usaha' => 'required|exists:badan_usahas,id',
            'id_proyek' => 'required|exists:proyeks,id'
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            
            $badan_usaha = BadanUsaha::find($request->id_badan_usaha);

            $badan_usaha->update(Input::all());

            
            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil mengupdate badan usaha.'
            ];
            
            return response()->json($output);
        }
    }

    public function get_badan_usaha(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $badan_usaha = BadanUsaha::where('id_proyek', $request->id_proyek)->get();
            $output = [
                'err' => null,
                'result' => $badan_usaha
            ];
            
            return response()->json($output);
        }
    }

    public function delete_badan_usaha(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_badan_usaha' => 'required|exists:badan_usahas,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $badan_usaha = BadanUsaha::where('id', $request->id_badan_usaha)->delete();

            
            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil mendelete badan usaha.'
            ];
            
            return response()->json($output);
        }
    }

    public function get_semua_badan_usaha() {
        $final_proyek = [];
        $badan_usahas = BadanUsaha::all();

        $output = [
            'err' => null,
            'result' => $badan_usahas
        ];
        
        return response()->json($output);
    }

    public function get_history_tahapan(Request $request) {
        $history_tahapan = HistoryTahapan::where('id_tahapan', $request->id_tahapan)->get();

        $output = [
            'err' => null,
            'result' => $history_tahapan
        ];
        
        return response()->json($output);
    }


}
