<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PesananSurat extends Model
{
    //
    public function mahasiswa(){
      return $this->belongsTo(Mahasiswa::class);
    }

    public function formatsurat(){
      return $this->belongsTo(Formatsurat::class);
    }

    public function historysurat(){
      return $this->hasOne(Historysurat::class);
    }
}
