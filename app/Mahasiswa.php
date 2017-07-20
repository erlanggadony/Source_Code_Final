<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    //
    public function jurusan(){
      return $this->belongsTo(Jurusan::class);
    }
    public function dosen(){
      return $this->belongsTo(Dosen::class);
    }
    public function pesanansurats(){
      return $this->hasMany(PesananSurat::class);
    }
    public function historysurats(){
      return $this->hasMany(Historysurat::class);
    }
    public function fakultas(){
      return $this->belongsTo(Fakultas::class);
    }
}
