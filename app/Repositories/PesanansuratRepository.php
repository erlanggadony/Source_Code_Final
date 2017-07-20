<?php
  namespace App\Repositories;

  use App\PesananSurat;

  class PesanansuratRepository{

    public function findAllPesananSurat(){
      $pesanansurats = PesananSurat::where([
                                    ['persetujuanDekan','=',true],
                                    ['persetujuanWDI','=',true],
                                    ['persetujuanWDII','=',true],
                                    ['persetujuanKaprodi','=',true],
                                    ['persetujuanDosenWali','=',true],
                                  ])
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(9);
      return $pesanansurats;
    }

    public function findPesananSurat(){
     $pesanansurats = PesananSurat::orderBy('created_at', 'DESC')
                                  ->paginate(9);
      return $pesanansurats;
    }
    
    public function findPesanansuratByJenisSurat($jenis_surat){
      $pesanansurats = PesananSurat::where('formatsurat_id', $jenis_surat)
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(11);
      return $pesanansurats;
    }
    public function findPesanansuratByPenerimaSurat($penerimaSurat){
      $pesanansurats = PesananSurat::where('penerimaSurat', $penerimaSurat)
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(11);
      return $pesanansurats;
    }
    public function findPesanansuratByPemohonSurat($pemohonSurat){
      $pesanansurats = PesananSurat::where('mahasiswa_id', $pemohonSurat)
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(11);
      return $pesanansurats;
    }
    public function findPesananSuratByTanggalPembuatan($tanggalPembuatan){
      $pesanansurats = PesananSurat::where('created_at ', $tanggalPembuatan)
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(11);
      return $pesanansurats;
    }
    public function findPesananSuratById($id){
      $pesanansurat = PesananSurat::where('id', $id)->first();
      return $pesanansurat;
    }
    public function pesananDosenWali($dosen_id){
      $pesanansurats = PesananSurat::where('id', $dosen_id)
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(11);

      return $pesanansurats;
    }
    public function pesananKaprodi($dosen_id){
      $pesanansurats = PesananSurat::where()
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(11);
      return $pesanansurats;
    }
    public function pesananWDII($dosen_id){
      $pesanansurats = PesananSurat::where()
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(11);
      return $pesanansurats;
    }
    public function pesananWDI($dosen_id){
      $pesanansurats = PesananSurat::where()
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(11);
      return $pesanansurats;
    }
    public function pesananDekan(){
      $pesanansurats = PesananSurat::where()
                                    ->orderBy('created_at', 'DESC')
                                    ->paginate(11);
      return $pesanansurats;
    }
  }
?>
