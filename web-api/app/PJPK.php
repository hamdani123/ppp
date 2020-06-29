<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PJPK extends Model
{
    //
    protected $table = 'p_j_p_ks';
    protected $fillable = ['lokasi', 'pjpk', 'pelaksana_tugas', 'simpul_kpbu'];
}
