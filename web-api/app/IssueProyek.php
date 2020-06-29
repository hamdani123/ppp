<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\TahapanProyek;

class IssueProyek extends Model
{
    //
    protected $table = 'issue_proyeks';
    protected $fillable = ['id_tahapan', 'tipe', 'deskripsi', 'status', 'resolved_date', 'penanggung_jawab', 'tindak_lanjut'];
    protected $appends = array('tahapan');

   if($tahapan != null) {
		return $tahapan->nama_tahapan;
   } else {
      return "";
   }

	
	
    public function setIdTahapanAttribute($value) {
        $tahapan = TahapanProyek::find($value);
        $this->attributes['id_tahapan'] = $value;
        $this->attributes['id_proyek'] = $tahapan->id_proyek;
    }
}
