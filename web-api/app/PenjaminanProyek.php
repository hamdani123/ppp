<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenjaminanProyek extends Model
{
    //
    protected $fillable = ['id_proyek', 'durasi_penjaminan', 'satuan_penjaminan', 'nilai_maksimal'];
}
