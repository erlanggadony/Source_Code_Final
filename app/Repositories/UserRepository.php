<?php
  namespace App\Repositories;

  use App\User;

  class UserRepository{

    public function findUserByUsername($username){
      $users = User::where('username', $username)->first();
      return $users;
    }
  }

 ?>
