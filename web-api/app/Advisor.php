<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advisor extends Model
{
    //
    protected $fillable = ['nama_konsultan', 'sektor', 'keterangan', 'nomor_telepon', 'website', 'alamat'];
}
