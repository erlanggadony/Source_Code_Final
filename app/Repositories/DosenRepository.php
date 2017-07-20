<?php
  namespace App\Repositories;

  use App\Dosen;

  class DosenRepository{
    public function findDosenById($id){
      $dosen = Dosen::where('id', $id)->first();
      return $dosen;
    }

    public function findDosenByUsername($username){
      $dosen = Dosen::where('username', $username)->first();
      return $dosen;
    }
  }
?>
