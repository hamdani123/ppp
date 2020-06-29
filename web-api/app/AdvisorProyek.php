<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Proyek;

class AdvisorProyek extends Model
{
    //
    protected $fillable = ['id_advisor', 'id_proyek', 'benefit', 'tenaga_ahli', 'tahun_pelaksana', 'sumber_dana'];

    protected $appends = array('proyek');

    public function getProyekAttribute() {
        $proyek = Proyek::find($this->id_proyek);

        $ouput = array(
            'nama_proyek' => $proyek->nama_proyek,
            'pjpk_proyek' => $proyek->pjpk_proyek,
            'sektor_proyek' => $proyek->sektor_proyek,
            'nilai_proyek' => $proyek->nilai_investasi
        );

        return $ouput;
    }

    
}
