<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\Advisor;
use App\AdvisorProyek;

class AdvisorController extends Controller
{
    //
    public function tambah_advisor(Request $request) {
        $validation = Validator::make(request()->all(),[
            'nama_konsultan' => 'required',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $advisor = Advisor::create(Input::all());
            
            $output = [
                'err' => null,
                'result' => array(
                    'id_advisor' => $advisor->id,
                    'message' => 'Selamat telah berhasil membuat Advisor.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function get_semua_advisor() {
        $semua_advisor = Advisor::all();

        $output = [
            'err' => null,
            'result' => $semua_advisor
        ];
        
        return response()->json($output);        
    }

    public function update_advisor(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_advisor' => 'required|exists:advisors,id',
            
        ]);
        
        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $advisor = Advisor::find($request->id_advisor);

            $advisor->update(Input::all());

            $output = [
                'err' => null,
                'result' => array(
                    'id_advisor' => $advisor->id,
                    'message' => 'Selamat telah berhasil update Advisor.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function delete_advisor(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_advisor' => 'required|exists:advisors,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $advisor = Advisor::where('id', $request->id_advisor)->delete();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil menghapus Advisor.'
            ];
            
            return response()->json($output);
        }
    }

    public function tambah_advisor_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_proyek' => 'required|exists:proyeks,id',
            'id_advisor' => 'required|exists:advisors,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $advisor_proyek = AdvisorProyek::create(Input::all());
            
            $output = [
                'err' => null,
                'result' => array(
                    'advisor_proyek' => $advisor_proyek->id,
                    'message' => 'Selamat telah berhasil membuat Advisor Proyek.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function update_advisor_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_advisor_proyek' => 'required|exists:advisor_proyeks,id',
            'id_proyek' => 'required|exists:proyeks,id',
            'id_advisor' => 'required|exists:advisors,id',
        ]);
        
        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $advisor_proyek = AdvisorProyek::find($request->id_advisor_proyek);

            $advisor_proyek->update(Input::all());

            $output = [
                'err' => null,
                'result' => array(
                    'id_advisor_proyek' => $advisor_proyek->id,
                    'message' => 'Selamat telah berhasil update Advisor Proyek.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function delete_advisor_proyek(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_advisor_proyek' => 'required|exists:advisor_proyeks,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $advisor_proyek = AdvisorProyek::where('id', $request->id_advisor_proyek)->delete();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil menghapus Advisor Proyek.'
            ];
            
            return response()->json($output);
        }
    }

    public function get_detail_advisor($id) {
        $advisor = Advisor::find($id);

        if($advisor == null) {
            $output = [
                'err' => 'id advisor is invalid',
                'result' => null
            ];
            
            return response()->json($output, 400);    
        } else {
            $advisor->advisor_proyek = AdvisorProyek::where('id_advisor', $id)->get();

            $output = [
                'err' => null,
                'result' => $advisor
            ];

            return response()->json($output);
        }
    }
}
