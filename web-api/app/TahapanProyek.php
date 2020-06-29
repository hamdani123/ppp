<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahapanProyek extends Model
{
    //
    use SoftDeletes;

    public function getFileUploadAttribute($value)
    {
        // if($value != null) {
        //     return url('/').'/file/proyek/tahapan/'.$value;
        // } else {
        //     return null;
        // }
        
    }

    protected $dates = ['deleted_at'];
}
