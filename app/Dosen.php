<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    //
    public function jurusan(){
      return $this->belongsTo(Jurusan::class);
    }

    public function mahasiswas(){
      return $this->hasMany(Mahasiswa::class);
    }

    public function fakultas(){
      return $this->belongsTo(fakultas::class);
    }
}
