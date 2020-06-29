<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\DevelopmentAgencies;
use App\DevelopmentAgenciesProyek;

class DevelopmentAgenciesController extends Controller
{
    //
    public function tambah_development_agencies(Request $request) {
        $validation = Validator::make(request()->all(),[
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
            $development_agencies = DevelopmentAgencies::create(Input::all());
            
            $output = [
                'err' => null,
                'result' => array(
                    'id_development_agencies' => $development_agencies->id,
                    'message' => 'Selamat telah berhasil membuat DevelopmentAgencies.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function get_semua_development_agencies() {
        $semua_development_agencies = DevelopmentAgencies::all();

        $output = [
            'err' => null,
            'result' => $semua_development_agencies
        ];
        
        return response()->json($output);        
    }

    public function update_development_agencies(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_development_agencies' => 'required|exists:development_agencies,id',
            
        ]);
        
        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $development_agencies = DevelopmentAgencies::find($request->id_development_agencies);

            $development_agencies->update(Input::all());

            $output = [
                'err' => null,
                'result' => array(
                    'id_development_agencies' => $development_agencies->id,
                    'message' => 'Selamat telah berhasil update DevelopmentAgencies.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function delete_development_agencies(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_development_agencies' => 'required|exists:development_agencies,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $development_agencies = DevelopmentAgencies::where('id', $request->id_development_agencies)->delete();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil menghapus DevelopmentAgencies.'
            ];
            
            return response()->json($output);
        }
    }

    public function tambah_development_agencies_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
            'id_development_agent' => 'required|exists:development_agencies,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $development_agencies_proyek = DevelopmentAgenciesProyek::create(Input::all());
            
            $output = [
                'err' => null,
                'result' => array(
                    'development_agencies_proyek' => $development_agencies_proyek->id,
                    'message' => 'Selamat telah berhasil membuat DevelopmentAgencies Proyek.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function update_development_agencies_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_development_agencies_proyek' => 'required|exists:development_agencies_proyeks,id',
            'id_proyek' => 'required|exists:proyeks,id',
            'id_development_agent' => 'required|exists:development_agencies,id',
        ]);
        
        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $development_agencies_proyek = DevelopmentAgenciesProyek::find($request->id_development_agencies_proyek);

            $development_agencies_proyek->update(Input::all());

            $output = [
                'err' => null,
                'result' => array(
                    'id_development_agencies_proyek' => $development_agencies_proyek->id,
                    'message' => 'Selamat telah berhasil update DevelopmentAgencies Proyek.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function delete_development_agencies_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_development_agencies_proyek' => 'required|exists:development_agencies_proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $development_agencies_proyek = DevelopmentAgenciesProyek::where('id', $request->id_development_agencies_proyek)->delete();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil menghapus DevelopmentAgencies Proyek.'
            ];
            
            return response()->json($output);
        }
    }

    public function get_detail_development_agencies($id) {
        $development_agencies = DevelopmentAgencies::find($id);

        if($development_agencies == null) {
            $output = [
                'err' => 'id development_agencies is invalid',
                'result' => null
            ];
            
            return response()->json($output, 400);    
        } else {
            $development_agencies->development_agencies_proyek = DevelopmentAgenciesProyek::where('id_development_agent', $id)->get();

            $output = [
                'err' => null,
                'result' => $development_agencies
            ];

            return response()->json($output);
        }
    }
}
