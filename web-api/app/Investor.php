<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    //
    protected $fillable = ['nama_perusahaan', 'sektor', 'jenis', 'asal_zona', 'asal_negara', 'pemegang_saham', 'komisaris_utama', 'komisaris', 'direktur_utama', 'direksi', 'kantor_pusat', 'kantor_perwakilan_indonesia', 'proyek_di_indonesia', 'proyek_luar_indonesia', 'kontak_person', 'website'];
}
