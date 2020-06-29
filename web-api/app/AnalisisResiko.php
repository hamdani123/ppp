<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PenjaminanProyek;
class AnalisisResiko extends Model
{
    //
    protected $fillable = ['id_penjaminan', 'jenis', 'penjamin', 'nilai', 'mitigasi'];

    public function setIdPenjaminanAttribute($value) {
        $penjaminan_proyek = PenjaminanProyek::find($value);
        $this->attributes['id_penjaminan'] = $value;
        $this->attributes['id_proyek'] = $penjaminan_proyek->id_proyek;
    }
}
