<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DropdownList;
use App\CheckList;

class DropdownController extends Controller
{
    //
    public function dropdown_list(Request $request) {
        $final_dropdowns = [];
        $tipe = "";
        
        if($request->has('tipe')) {
            $tipe = $request->tipe;
        }

        $dropdowns = DropdownList::where('tipe', $tipe)->get();

        foreach($dropdowns as $dropdown) {
            array_push($final_dropdowns, $dropdown->dropdown);
        }

        $output = [
            'err' => null,
            'result' => array(
                'dropdown' => $final_dropdowns
            )
        ];
        
        return response()->json($output);
    }

    public function check_list(Request $request) {
        $final_checklists = [];
        $tipe = "";
        
        if($request->has('tipe')) {
            $tipe = $request->tipe;
        }

        $checklists = CheckList::where('tipe', $tipe)->get();

        foreach($checklists as $checklist) {
            array_push($final_checklists, $checklist->checklist);
        }

        $output = [
            'err' => null,
            'result' => array(
                'checklist' => $final_checklists
            )
        ];
        
        return response()->json($output);
    }
}
