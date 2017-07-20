<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    //
    public function jurusan(){
      return $this->belongsTo(Jurusan::class);
    }

    public function pesanansurats(){
		  return $this->belongsToMany(PesananSurat::class);
		}
}
