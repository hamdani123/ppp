<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\TahapanProyek;

class DokumenTahapanProyek extends Model
{
    //
    protected $fillable = ['id_tahapan', 'nama_dokumen', 'jenis_dokumen'];
    protected $appends = ['tahapan','kegiatan'];	

    public function getTahapanAttribute() {
        $tahapan = TahapanProyek::withTrashed()->find($this->id_tahapan);

        if($tahapan != null) {
            return $tahapan->nama_tahapan;
        } else {
            return "";
        }
        
    }

    public function getKegiatanAttribute() {
        $kegiatan= TahapanProyek::withTrashed()->find($this->id_tahapan);

        if($kegiatan!= null) {
            return $kegiatan->nama_kegiatan;
        } else {
            return "";
        }
        
    }

    public function setIdTahapanAttribute($value) {
        $tahapan = TahapanProyek::find($value);
        $this->attributes['id_tahapan'] = $value;
        $this->attributes['id_proyek'] = $tahapan->id_proyek;
    }

    public function setFileAttribute($value) {
        $file = $value;

        $dataFile = $value;
        $t = microtime(true);
        $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
        $timestamp = date('YmdHis' . $micro, $t) . "_" . rand(0, 1000);

        $ext_file = $dataFile->getClientOriginalExtension();
        $name_file = str_replace(" ","_",$this->attributes['nama_dokumen']);
        
        $name_file = $name_file . '.' . $ext_file;
        $path_file = public_path() . '/file/proyek/tahapan';

        if(!$dataFile->move($path_file,$name_file)) {
            $output = [
                'err' => 'error dalam melakukan upload gambar',
                'result' => null
            ];
            
            return response()->json($output, 400);
        }

        $this->attributes['file'] = $name_file;
        // $tahapan_proyek->file_upload = $name_file;
    }

    public function getFileAttribute($value) {
        if($value != null) {
            return url('/').'/file/proyek/tahapan/'.$value;
        } else {
            return null;
        }
    }

    public function getOriginalFileAttribute() {
        return $this->attributes['file'];
    }

    public function setOriginalFileAttribute($value) {
        $this->attributes['file'] = $value;
    }
}
