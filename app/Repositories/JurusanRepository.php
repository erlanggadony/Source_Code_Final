<?php
  namespace App\Repositories;

  use App\Jurusan;

  class JurusanRepository{

    public function findJurusanById($id){
      $jurusan = Jurusan::where('id', $id)->first();
      return $jurusan;
    }
  }

 ?>
