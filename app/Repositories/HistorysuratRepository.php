<?php
  namespace App\Repositories;

  use App\Historysurat;

  class HistorysuratRepository{
    function findAllHistorySurat(){
      $historysurats = Historysurat::orderBy('created_at', 'DESC')->paginate(11);
      return $historysurats;
    }
    public function findHistorysuratByNomorSurat($no_surat){
      $historysurats = Historysurat::where('no_surat', $no_surat)
                                  ->orderBy('created_at', 'DESC')
                                  ->paginate(11);
      return $historysurats;
    }
    public function findHistorysuratByJenisSurat($jenis_surat){
      $historysurats = Historysurat::where('formatsurat_id', $jenis_surat)
                                  ->orderBy('created_at', 'DESC')
                                  ->paginate(11);
      return $historysurats;
    }
    public function findHistorysuratByPerihal($perihal){
      $historysurats = Historysurat::where('perihal', $perihal)
                                  ->orderBy('created_at', 'DESC')
                                  ->paginate(11);
      return $historysurats;
    }
    public function findHistorysuratByPemohonSurat($mahasiswa_id){
      $historysurats = Historysurat::where('mahasiswa_id', $mahasiswa_id)
                                  ->orderBy('created_at', 'DESC')
                                  ->paginate(11);
      return $historysurats;
    }
    public function findHistorysuratByPenerimaSurat($penerimaSurat){
      $historysurats = Historysurat::where('penerimaSurat', $penerimaSurat)
                                  ->orderBy('created_at', 'DESC')
                                  ->paginate(11);
      return $historysurats;
    }
    public function findHistoryByTanggalPembuatan($tanggalPembuatan){
      $historysurats = Historysurat::where('timestamps', $tanggalPembuatan)
                                  ->orderBy('created_at', 'DESC')
                                  ->paginate(11);
      return $historysurats;
    }

    public function findHistoryById($id){
      $historysurat = Historysurat::where('id', $id)->first();
      return $historysurat;
    }

    public function historyDosenWali($dosen_id){
      $historysurats = Historysurat::where()
                                    ->orderBy('timestamps', 'DESC')
                                    ->paginate(11);

      return $historysurats;
    }

    public function historyKaprodi($dosen_id){
      $historysurats = Historysurat::where()
                                    ->orderBy('timestamps', 'DESC')
                                    ->paginate(11);

      return $historysurats;
    }

    public function historyMhs($id){
      $historysurats = Historysurat::where('id', $id)
                                    ->orderBy('timestamps', 'DESC')
                                    ->paginate(11);
      return $historysurats;                                    
    }
    public function getHistoryDekanByNoSurat($no_surat){
      $historysurats = Historysurat::where('formatsurat_id','=',9)
                                        ->orWhere('formatsurat_id','=',10)
                                        ->where('no_surat', $no_surat)
                                        ->first();
      return $historysurats;
    }
  }

 ?>
