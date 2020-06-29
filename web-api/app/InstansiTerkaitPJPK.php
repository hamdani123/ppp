<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstansiTerkaitPJPK extends Model
{
    //
    protected $table = 'instansi_terkait_p_j_p_ks';
    protected $fillable = ['id_proyek', 'instansi', 'relasi', 'nama', 'jabatan', 'alamat', 'no_telepon', 'email'];
}
