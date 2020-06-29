<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DevelopmentAgencies extends Model
{
    //
    protected $fillable = ['nama', 'kategori', 'alamat', 'nomor_telepon', 'email'];
}
