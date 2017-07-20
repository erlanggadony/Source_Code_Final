<?php
  namespace App\Repositories;

  use App\Mahasiswa;

  class MahasiswaRepository{

    public function findAllMahasiswa(){
      $mahasiswas = Mahasiswa::orderBy('npm', 'ASC')->paginate(15);
      return $mahasiswas;
    }
    public function findAllMhs(){
      $mahasiswas = Mahasiswa::all();
      return $mahasiswas;
    }
    public function findMahasiswaByNIRM($nirm){
      $mahasiswas = Mahasiswa::where('nirm', 'like',  $nirm)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $mahasiswas;
    }
    public function findMahasiswaByNPM($npm){
      $mahasiswas = Mahasiswa::where('npm', $npm)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $mahasiswas;
    }
    public function findMahasiswaByNama($nama_mahasiswa){
      $mahasiswas = Mahasiswa::where('nama_mahasiswa', $nama_mahasiswa)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $mahasiswas;
    }
    public function findMahasiswaByProdi($prodi){
      $mahasiswas = Mahasiswa::where('jurusan_id','like' , $prodi)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $mahasiswas;
    }
    public function findMahasiswaByAngkatan($angkatan){
      $mahasiswas = Mahasiswa::where('angkatan', $angkatan)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $mahasiswas;
    }
    public function findMahasiswaByKotaLahir($kota_lahir){
      $mahasiswas = Mahasiswa::where('kota_lahir','like', $kota_lahir)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $mahasiswas;
    }
    public function findMahasiswaByTanggalLahir($tanggal_lahir){
      $mahasiswas = Mahasiswa::where('tanggal_lahir', $tanggal_lahir)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $mahasiswas;
    }
    public function findMahasiswaByFakultas($fakultas){
      $mahasiswas = Mahasiswa::where('fakultas_id', $fakultas)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $mahasiswas;
    }
    public function findMahasiswaByDosenWali($dosenWali){
      $mahasiswas = Mahasiswa::where('dosen_id', $dosenWali)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $mahasiswas;
    }
    public function findMahasiswaById($id){
      $mahasiswa = Mahasiswa::where('id', $id)->first();
      return $mahasiswa;
    }
    public function findMahasiswaByUsername($username){
      $mahasiswa = Mahasiswa::where('username', $username)->first();
      return $mahasiswa;
    }
    public function findPasword($password){
      $mahasiswas = Mahasiswa::where('password', $password)->get();
      return $mahasiswas;
    }
    public function dosenWaliMhs(){

    }
  }

 ?>
