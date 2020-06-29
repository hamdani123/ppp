<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Proyek;

class DevelopmentAgenciesProyek extends Model
{
    //
    protected $fillable = ['id_development_agent', 'id_proyek', 'goal', 'tujuan_proyek', 'output', 'periode', 'aktivitas', 'pic'];

    protected $appends = array('nama_proyek');

    public function getNamaProyekAttribute() {
        $proyek = Proyek::find($this->id_proyek);

        if($proyek != null) {
            return $proyek->nama_proyek;
        } else {
            return null;
        }

        
    }
}
