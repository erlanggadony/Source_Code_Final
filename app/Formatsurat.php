<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formatsurat extends Model
{
    //
    public function pesanansurats(){
      return $this->hasMany(PesananSurat::class);
    }
    public function historysurats(){
      return $this->hasMany(Historysurat::class);
    }
}
