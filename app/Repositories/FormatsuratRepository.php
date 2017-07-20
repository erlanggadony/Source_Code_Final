<?php
  namespace App\Repositories;

  use App\Formatsurat;

  class FormatsuratRepository{

    public function tampilkanFormat(){
      $formatsurats = Formatsurat::all();
      return $formatsurats;
    }
    public function findAllFormatsurat(){
      $formatsurats = Formatsurat::orderBy('id', 'ASC')->paginate(16);
      return $formatsurats;
    }
    public function findFormatsuratByIdFormatSurat($idFormatSurat){
      $formatsurats = Formatsurat::where('idFormatSurat', $idFormatSurat)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $formatsurats;
    }
    public function findFormatsuratByJenisSurat($jenis_surat){
      $formatsurats = Formatsurat::where('jenis_surat', $jenis_surat)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $formatsurats;
    }
    public function findFormatsuratByKeteranganSurat($keterangan){
      $formatsurats = Formatsurat::where('keterangan', $keterangan)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $formatsurats;
    }
    public function findFormatsuratByLinkSurat($link_format_surat){
      $formatsurats = Formatsurat::where('link_format_surat', $link_format_surat)
                                  ->orderBy('id', 'ASC')
                                  ->paginate(16);
      return $formatsurats;
    }
    public function findById($id){
      $formatsurat = Formatsurat::where('id', $id)->first();
      return $formatsurat;
    }
  }

 ?>
