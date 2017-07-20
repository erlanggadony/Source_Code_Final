<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    //
    public function jurusans(){
      return $this->hasMany(Jurusan::class);
    }
    public function dosens(){
      return $this->hasMany(Dosen::class);
    }
    public function mahasiswas(){
      return $this->hasMany(Mahasiswa::class);
    }
}
