<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimelineProyek extends Model
{
    //
    protected $fillable = ['plan_tanggal_mulai', 'plan_tanggal_selesai', 'tahapan', 'deskripsi', 'warna', 'nama_kegiatan', 'actual_tanggal_mulai', 'actual_tanggal_selesai', 'id_proyek'];
}
