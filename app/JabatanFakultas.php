<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JabatanFakultas extends Model
{
    //
    public function fakultas(){
      return $this->belongsTo(fakultas::class);
    }
    public function dosens(){
      return $this->hasMany(Dosen::class);
    }
}
