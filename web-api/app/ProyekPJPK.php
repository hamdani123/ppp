<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyekPJPK extends Model
{
    //
    protected $table = 'proyek_p_j_p_ks';
    protected $fillable = ['id_proyek', 'instansi', 'pjpk', 'pic', 'jabatan', 'alamat', 'no_telepon', 'email'];
}
