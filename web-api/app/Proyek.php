<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    //
    protected $fillable = ['nama_proyek', 'sektor_proyek', 'lingkup_proyek', 'lokasi_proyek', 'pjpk_proyek', 'tipe_kpbu', 'badan_usaha_penyiapan', 'fasilitator', 'skema_pengembalian_investasi', 'dukungan_vgf', 'dukungan_konstruksi', 'dukungan_lain', 'jaminan_proyek', 'nilai_investasi', 'financial_npv', 'financial_irr', 'economical_irr', 'masa_konsesi', 'kurs', 'mata_uang', 'financier', 'kode_proyek', 'struktur_kontrak_kpbu'];
}
