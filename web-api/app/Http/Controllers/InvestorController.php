<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\Investor;

class InvestorController extends Controller
{
    //
    public function tambah_investor(Request $request) {
        $validation = Validator::make(request()->all(),[
            'nama_perusahaan' => 'required',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $investor = Investor::create(Input::all());
            
            $output = [
                'err' => null,
                'result' => array(
                    'id_investor' => $investor->id,
                    'message' => 'Selamat telah berhasil membuat Investor.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function get_semua_investor() {
        $semua_investor = Investor::all();

        $output = [
            'err' => null,
            'result' => $semua_investor
        ];
        
        return response()->json($output);        
    }

    public function update_investor(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_investor' => 'required|exists:investors,id',
            
        ]);
        
        if($validation->fails()) {
            $errors = $validation->errors()->first();
            
            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $investor = Investor::find($request->id_investor);

            $investor->update(Input::all());

            $output = [
                'err' => null,
                'result' => array(
                    'id_investor' => $investor->id,
                    'message' => 'Selamat telah berhasil update Investor.'
                )
            ];
            
            return response()->json($output);
        }
    }

    public function delete_investor(Request $request) {
        $validation = Validator::make(request()->all(),[
            'id_investor' => 'required|exists:investors,id',
        ]);

        if($validation->fails()) {
            $errors = $validation->errors()->first();

            $output = [
                'err' => $errors,
                'result' => null
            ];
            
            return response()->json($output, 400);
        } else {
            $investor = Investor::where('id', $request->id_investor)->delete();

            $output = [
                'err' => null,
                'result' => 'Selamat telah berhasil menghapus Investor.'
            ];
            
            return response()->json($output);
        }
    }

    public function get_detail_investor($id) {
        $investor = Investor::find($id);

        if($investor == null) {
            $output = [
                'err' => 'id investor is invalid',
                'result' => null
            ];
            
            return response()->json($output, 400);    
        } else {
            

            $output = [
                'err' => null,
                'result' => $investor
            ];

            return response()->json($output);
        }
    }
}
