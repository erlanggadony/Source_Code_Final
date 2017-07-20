<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historysurat extends Model
{
    //
    public function pesanansurat(){
      return $this->belongsTo(PesananSurat::class);
    }
    public function mahasiswa(){
      return $this->belongsTo(Mahasiswa::class);
    }
    public function formatsurat(){
      return $this->belongsTo(Formatsurat::class);
    }
}
