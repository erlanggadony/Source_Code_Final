<?php
  namespace App\Repositories;

  use App\TU;

  class TURepository{
    public function findTUById($id){
      $tu = TU::where('id', $id)->first();
      return $tu;
    }

    public function findTUyUsername($username){
      $tu = TU::where('username', $username)->first();
      return $tu;
    }
  }
?>
