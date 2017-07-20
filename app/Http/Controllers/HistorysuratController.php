<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PesanansuratRepository;
use App\Repositories\HistorysuratRepository;
use App\Repositories\JurusanRepository;
use App\Repositories\FormatsuratRepository;
use App\Mahasiswa;
use App\Formatsurat;
use App\Historysurat;
use Storage;
use Illuminate\Support\Facades\Auth;
use App\Dosen;
use App\User;
use App\TU;
class HistorysuratController extends Controller
{
    //
    protected $historysuratRepo;
    protected $pesananSuratRepo;
    protected $formatSuratRepo;
    protected $jurusanRepo;

    public function __construct(HistorysuratRepository $historysuratRepo, PesanansuratRepository $pesananSuratRepo, FormatsuratRepository $formatsuratRepo, JurusanRepository $jurusanRepo){
      // dd($formatsuratRepo);
        $this->historysuratRepo = $historysuratRepo;
        $this->pesananSuratRepo = $pesananSuratRepo;
        $this->formatsuratRepo = $formatsuratRepo;
        $this->jurusanRepo = $jurusanRepo;
        //dd($this->orders->getAllActive());
    } 

    public function tampilkanHistoryDiPejabat(Request $request){
         $loggedInUser = Auth::user();
      $realUser = $this->getRealUser($loggedInUser);

      $results = [];

      //CEK USER DEKAN
      if($realUser->id == $realUser->fakultas->id_dekan){
        // dd("s");
         $tempHistorysurats = Historysurat::where('formatsurat_id','=',9)->orWhere('formatsurat_id','=',10)->get();
         foreach ($tempHistorysurats as $key => $surat) {
           array_push($results,$surat);
          }
        
        // dd($);
        return view('pejabat.history_pejabat', [
          'historysurats' => $results,
          'user' => $realUser
        ]);
      }

      //CEK USER WD1
      if($realUser->id == $realUser->fakultas->id_WD_I){
        $tempHistorysurats = Historysurat::where('formatsurat_id','=',2)
                                             ->orWhere('formatsurat_id','=',11)
                                             ->orWhere('formatsurat_id','=',12)
                                             ->orWhere('formatsurat_id','=',13)
                                             ->orWhere('formatsurat_id','=',14)
                                             ->orWhere('formatsurat_id','=',15)
                                             ->orWhere('formatsurat_id','=',16)
                                             ->orWhere('formatsurat_id','=',17)
                                             ->orWhere('formatsurat_id','=',18)
                                             ->orWhere('formatsurat_id','=',19)
                                             ->orWhere('formatsurat_id','=',20)
                                             ->get();
        return view('pejabat.history_pejabat', [
          'historysurats' => $tempHistorysurats,
          'user' => $realUser
        ]);
      }

      //CEK USER WD3
      if($realUser->id == $realUser->fakultas->id_WD_III){
        $tempHistorysurats = Historysurat::where('formatsurat_id','=',1)
                                           ->orWhere('formatsurat_id','=',3)
                                           ->orWhere('formatsurat_id','=',4)
                                           ->orWhere('formatsurat_id','=',5)
                                           ->orWhere('formatsurat_id','=',6)
                                           ->orWhere('formatsurat_id','=',7)
                                           ->orWhere('formatsurat_id','=',8)
                                           ->get();
        
        foreach ($tempHistorysurats as $key => $surat) {
          array_push($results,$surat);
        }
      }

       foreach ($realUser->mahasiswas as $key => $mhs) {
         foreach ($mhs->historysurats as $key => $surat) {
           array_push($results,$surat);
          }
       }

      return view('pejabat.history_pejabat', [
          'historysurats' => $results,
          'user' => $realUser
        ]);
  	}

    public function tampilkanProfil(Request $request){
      $loggedInUser = Auth::user();
      $realUser = $this->getRealUser($loggedInUser);
      if($request->kategori_mahasiswa == "perihal"){
        $histories = Historysurat::where('perihal', $request->searchBox)->where('mahasiswa_id', $realUser->id)->get();
      }
      else if($request->kategori_mahasiswa == "penerimaSurat"){
        $histories = Historysurat::where('penerimaSurat', $request->searchBox)->where('mahasiswa_id', $realUser->id)->get();
      }
      else if($request->kategori_mahasiswa == "jenis_surat"){
        $histories = Historysurat::where('formatsurat_id', $request->searchBox)->where('mahasiswa_id', $realUser->id)->get();
      }
      else{
        $histories = $realUser->historysurats;
      }
      
      $foto = $realUser->foto_mahasiswa;
      return view('mahasiswa.home_mahasiswa',[
        'user' => $realUser,
        'historysurats' => $histories
      ]);
    }

    public function ubahStatusPengambilan(Request $request){
      $history = $this->historysuratRepo->findHistoryById($request->id);
      // dd($history);
      $history->pengambilan = true;
      $history->save();
      return redirect('/history_TU');
    }

    public function ubahStatusPenandatanganan(Request $request){
      $history = $this->historysuratRepo->findHistoryById($request->id);
      $history->penandatanganan = true;
      $history->save();
      return redirect('/history_pejabat');
    }

    private function getRealUser($loggedInUser){
      // dd($loggedInUser);
      $realUser='';
      // dd($realUser);
      if($loggedInUser->jabatan == User::JABATAN_MHS){
        $realUser = Mahasiswa::find($loggedInUser->ref);
        // dd($realUser);
        return $realUser;
      }else if($loggedInUser->jabatan == User::JABATAN_DOS){
        $realUser = Dosen::find($loggedInUser->ref);
        // dd($realUser);
        return $realUser;
      }else{ // TU
        $realUser = TU::find($loggedInUser->ref);
        // dd($loggedInUser->jabatan);
        return $realUser;
      }
      // dd($realUser);
    }

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function pilihHistorySurat(Request $request){
        $historysurats;
        if($request->kategori_history_surat == "no_surat"){
          $historysurats = $this->historysuratRepo->findHistorysuratByNomorSurat($request->searchBox);
        }
        else if($request->kategori_history_surat == "jenis_surat"){
          $historysurats = $this->historysuratRepo->findHistorysuratByJenisSurat($request->searchBox);
        }
        else if($request->kategori_history_surat == "perihal"){
          $historysurats = $this->historysuratRepo->findHistorysuratByPerihal($request->searchBox);
        }
        else if($request->kategori_history_surat == "penerimaSurat"){
          $historysurats = $this->historysuratRepo->findHistorysuratByPenerimaSurat($request->searchBox);
        }
        else if($request->kategori_history_surat == "pemohonSurat"){
          $historysurats = $this->historysuratRepo->findHistorysuratByPemohonSurat($request->searchBox);
        }
        else if($request->kategori_history_surat == "tanggalPembuatan"){
          $historysurats = $this->historysuratRepo->findHistorysuratByTanggalPembuatan($request->searchBox);
        }
        else{
          $historysurats = $this->historysuratRepo->findAllHistorySurat();
          
        }
        // dd($historysurats);
        $loggedInUser = Auth::user();
        $realUser = $this->getRealUser($loggedInUser);
        $foto = $realUser->foto_mahasiswa;
        return view('TU.history_TU', [
          'historysurats' => $historysurats,
          'user' => $realUser,
          'foto' => $foto
        ]);
	}

  public function tampilkanPDF(Request $request){
    $history = $this->historysuratRepo->findHistoryById($request->history_id);
    $link = $history->link_arsip_surat;
    // dd($history);
    return redirect($link); 
  }

  public function buatPDF(Request $request){
      if($request->idFormatSurat == "1"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $penyediabeasiswa = $json->penyediabeasiswa;
        $pemesan = $request->pemesan;
        
        //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        //concat data input dgn format surat
        $entry = '\mailentry{' . $noSurat . ',' . $nama . ',' . $prodi . ',' . $npm . ',' . $semester . ',' . $thnAkademik . ',' . $penyediabeasiswa . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_keterangan_beasiswa.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);

        foreach ($fileTemplate as $line_num => $line) {
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }

        //inject ke file baru lalu compile
        $file = fopen("arsip_surat/" . $npm . "_surat_keterangan_beasiswa.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_keterangan_beasiswa.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = $json->penyediabeasiswa;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_keterangan_beasiswa.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "2"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $kota_lahir = $json->kota_lahir;
        $tglLahir = date_create($json->tglLahir)->format("j F Y");
        $alamat = $json->alamat;
        $semester = $json->semester;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        //concat data input dgn format surat
        $entry = '\mailentry{' . $noSurat . ',' . $nama . ',' . $npm . ',' . $prodi . ',' . $kota_lahir . ',' . $tglLahir . ',' . $alamat . ',' . $semester . ',' . $tanggal . '}';
        // dd($entry);
        $fileTemplate = file('format_surat_latex/surat_keterangan_mahasiswa_aktif.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        //inject ke file baru  lalu compile
        $file = fopen("arsip_surat/" . $npm . "_surat_keterangan_mahasiswa_aktif.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_keterangan_mahasiswa_aktif.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = $nama;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_keterangan_mahasiswa_aktif.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "3"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $nama = $json->nama;
        $tglLahir = $json->tglLahir;
        $kewarganegaraan = $json->kewarganegaraan;
        $getYear = date_create($request->tanggal)->format("Y");
        $angkatan = $json->angkatan; 
        $tahunKe = intval($getYear)-intval($angkatan);
        $thnAkademik = $json->thnAkademik;
        $negaraTujuan = $json->negaraTujuan;
        $tanggalKunjungan = date_create($json->tanggalKunjungan)->format("j F Y");
        $organisasiTujuan = $json->organisasiTujuan;
        $pemesan = $request->pemesan;
        $npm = $json->npm;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        //concat data input dgn format surat
        $entry = '\mailentry{' . $nama . ',' . $tglLahir . ',' . $kewarganegaraan  . ',' . $tahunKe . ',' . $thnAkademik . ',' . $negaraTujuan . ',' . $tanggalKunjungan . ',' . $organisasiTujuan . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_pengantar_pembuatan_visa.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        //inject ke file baru  lalu compile
        $file = fopen("arsip_surat/" . $npm . "_surat_pengantar_pembuatan_visa.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_pengantar_pembuatan_visa.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = 'APPLICATION FOR VISA SCHENGEN';
        $historysurat->penerimaSurat = $organisasiTujuan;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_pengantar_pembuatan_visa.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "4"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $matkul = $json->matkul;
        $topik = $json->topik;
        $organisasi = $json->organisasi;
        $alamatOrganisasi = $json->alamatOrganisasi;
        $keperluanKunjungan = $json->keperluanKunjungan;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' .
          $noSurat . ',' . $nama . ',' . $npm . ',' . $prodi . ',' . $matkul . ',' . $topik . ',' . $organisasi . ',' . $alamatOrganisasi . ',' . $keperluanKunjungan . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_pengantar_studi_lapangan_1orang.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        $file = fopen("arsip_surat/" . $npm . "_surat_pengantar_studi_lapangan_1orang.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_pengantar_studi_lapangan_1orang.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = 'Permohonan ' . $keperluanKunjungan;
        $historysurat->penerimaSurat = $organisasi;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_pengantar_studi_lapangan_1orang.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "5"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $matkul = $json->matkul;
        $topik = $json->topik;
        $organisasi = $json->organisasi;
        $alamatOrganisasi = $json->alamatOrganisasi;
        $keperluanKunjungan = $json->keperluanKunjungan;
        $namaAnggota = $json->namaAnggota;
        $npmAnggota = $json->npmAnggota;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $noSurat . ',' . $nama . ',' . $npm . ',' . $prodi . ',' . $matkul . ',' . $topik . ',' . $organisasi . ',' . $alamatOrganisasi . ',' . $keperluanKunjungan . ',' . $namaAnggota . ',' . $npmAnggota . ',' . $tanggal .  '}';
        $fileTemplate = file('format_surat_latex/surat_pengantar_studi_lapangan_2orang.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_pengantar_studi_lapangan_2orang.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_pengantar_studi_lapangan_2orang.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = 'Permohonan ' . $keperluanKunjungan;
        $historysurat->penerimaSurat = $organisasi;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_pengantar_studi_lapangan_2orang.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "6"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $matkul = $json->matkul;
        $topik = $json->topik;
        $organisasi = $json->organisasi;
        $alamatOrganisasi = $json->alamatOrganisasi;
        $keperluanKunjungan = $json->keperluanKunjungan;
        $namaAnggota1 = $json->namaAnggota1;
        $npmAnggota1 = $json->npmAnggota1;
        $namaAnggota2 = $json->namaAnggota2;
        $npmAnggota2 = $json->npmAnggota2;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $noSurat . ',' . $nama . ',' . $npm . ',' . $prodi . ',' . $matkul . ',' . $topik . ',' . $organisasi . ',' . $alamatOrganisasi . ',' . $keperluanKunjungan . ',' . $namaAnggota1 . ',' . $npmAnggota1 . ',' . $namaAnggota2 . ',' . $npmAnggota2 . ',' . $tanggal .  '}';
        $fileTemplate = file('format_surat_latex/surat_pengantar_studi_lapangan_3orang.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_pengantar_studi_lapangan_3orang.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_pengantar_studi_lapangan_3orang.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = 'Permohonan ' . $keperluanKunjungan;
        $historysurat->penerimaSurat = $organisasi;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_pengantar_studi_lapangan_3orang.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "7"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $matkul = $json->matkul;
        $topik = $json->topik;
        $organisasi = $json->organisasi;
        $alamatOrganisasi = $json->alamatOrganisasi;
        $keperluanKunjungan = $json->keperluanKunjungan;
        $namaAnggota1 = $json->namaAnggota1;
        $npmAnggota1 = $json->npmAnggota1;
        $namaAnggota2 = $json->namaAnggota2;
        $npmAnggota2 = $json->npmAnggota2;
        $namaAnggota3 = $json->namaAnggota3;
        $npmAnggota3 = $json->npmAnggota3;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $noSurat . ',' . $nama . ',' . $npm . ',' . $prodi . ',' . $matkul . ',' . $topik . ',' . $organisasi . ',' . $alamatOrganisasi . ',' . $keperluanKunjungan . ',' . $namaAnggota1 . ',' . $npmAnggota1 . ',' . $namaAnggota2 . ',' . $npmAnggota2 . ',' . $namaAnggota3 . ',' . $npmAnggota3 . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_pengantar_studi_lapangan_4orang.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_pengantar_studi_lapangan_4orang.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_pengantar_studi_lapangan_4orang.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = 'Permohonan ' . $keperluanKunjungan;
        $historysurat->penerimaSurat = $organisasi;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_pengantar_studi_lapangan_4orang.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "8"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $matkul = $json->matkul;
        $topik = $json->topik;
        $organisasi = $json->organisasi;
        $alamatOrganisasi = $json->alamatOrganisasi;
        $keperluanKunjungan = $json->keperluanKunjungan;
        $namaAnggota1 = $json->namaAnggota1;
        $npmAnggota1 = $json->npmAnggota1;
        $namaAnggota2 = $json->namaAnggota2;
        $npmAnggota2 = $json->npmAnggota2;
        $namaAnggota3 = $json->namaAnggota3;
        $npmAnggota3 = $json->npmAnggota3;
        $namaAnggota4 = $json->namaAnggota4;
        $npmAnggota4 = $json->npmAnggota4;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $noSurat . ',' . $nama . ',' . $npm . ',' . $prodi . ',' . $matkul . ',' . $topik . ',' . $organisasi . ',' . $alamatOrganisasi . ',' . $keperluanKunjungan . ',' . $namaAnggota1 . ',' . $npmAnggota1 . ',' . $namaAnggota2 . ',' . $npmAnggota2 . ',' . $namaAnggota3 . ',' . $npmAnggota3 . ',' . $namaAnggota4 . ',' . $npmAnggota4 . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_pengantar_studi_lapangan_5orang.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_pengantar_studi_lapangan_5orang.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_pengantar_studi_lapangan_5orang.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = 'Permohonan ' . $keperluanKunjungan;
        $historysurat->penerimaSurat = $organisasi;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_pengantar_studi_lapangan_5orang.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "9"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        if($semester == 'Ganjil'){
          $nextSemester = 'Genap';
          $nextThnAkademik = $thnAkademik;
        }
        else if($semester == 'Genap'){
          $nextSemester = 'Ganjil';
          $getTahun = explode("/", $thnAkademik);
          $nextThnAkademik = ($getTahun[0] + 1) . '/' . ($getTahun[1] + 1);
        }
        // dd($semester,$thnAkademik,$nextThnAkademik);
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $noSurat . ',' . $nama . ',' . $npm . ',' . $tanggal . ',' . $semester . ',' . $thnAkademik . ',' .  $prodi . ',' . $nextSemester . ',' . $nextThnAkademik . '}';
        // dd($entry);
        $fileTemplate = file('format_surat_latex/surat_izin_cuti_studi.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_izin_cuti_studi.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_izin_cuti_studi.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = 'Surat Ijin Berhenti Studi Sementara';
        $historysurat->penerimaSurat = $nama;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_izin_cuti_studi.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "10"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        // dd($prodi);
        $semester = $json->semester;
        $pemesan = $request->pemesan;
        //tanggal mahasiswa mengisi formulir
        $getTanggalBuat = date_create($request->tanggal);
        $tanggalBuat = $getTanggalBuat->format("j F Y");
        
        //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $noSurat . ',' . $nama . ',' . $npm . ',' . $prodi . ',' . $tanggalBuat . ',' . $semester . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_pengunduran_diri.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_pengunduran_diri.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_pengunduran_diri.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = 'Pengunduran diri';
        $historysurat->penerimaSurat = 'Rektor';
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_pengunduran_diri.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "11"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = Dosen::where('id',$json->dosenWali)->first()->nama_dosen;
        $alasan = $json->alasan;
        $kodeMK = $json->kodeMK;
        $matkul = $json->matkul;
        $sks = $json->sks;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $semester . ',' . $thnAkademik . ',' . $nama . ',' . $prodi . ',' . $npm . ',' . $namaWakil . ',' . $prodiWakil . ',' . $npmWakil . ',' . $dosenWali . ',' . $alasan . ',' . $kodeMK . ',' . $matkul . ',' . $sks . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_perwakilan_perwalian_1mk.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_perwakilan_perwalian_1mk.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_perwakilan_perwalian_1mk.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = Mahasiswa::where('id',$pemesan)->first()->dosen->nama_dosen;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_perwakilan_perwalian_1mk.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "12"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = Dosen::where('id',$json->dosenWali)->first()->nama_dosen;
        $alasan = $json->alasan;
        $kodeMK1 = $json->kodeMK1;
        $matkul1 = $json->matkul1;
        $sks1 = $json->sks1;
        $kodeMK2 = $json->kodeMK2;
        $matkul2 = $json->matkul2;
        $sks2 = $json->sks2;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $semester . ',' . $thnAkademik  . ',' . $nama . ',' . $prodi . ',' . $npm . ',' . $namaWakil . ',' . $prodiWakil . ',' . $npmWakil . ',' . $dosenWali . ',' . $alasan . ',' . $kodeMK1 . ',' . $matkul1 . ',' . $sks1 . ',' . $kodeMK2 . ',' . $matkul2 . ',' . $sks2 . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_perwakilan_perwalian_2mk.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_perwakilan_perwalian_2mk.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_perwakilan_perwalian_2mk.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = Mahasiswa::where('id',$pemesan)->first()->dosen->nama_dosen;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_perwakilan_perwalian_2mk.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "13"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = Dosen::where('id',$json->dosenWali)->first()->nama_dosen;
        $alasan = $json->alasan;
        $kodeMK1 = $json->kodeMK1;
        $matkul1 = $json->matkul1;
        $sks1 = $json->sks1;
        $kodeMK2 = $json->kodeMK2;
        $matkul2 = $json->matkul2;
        $sks2 = $json->sks2;
        $kodeMK3 = $json->kodeMK3;
        $matkul3 = $json->matkul3;
        $sks3 = $json->sks3;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $semester . ',' .$thnAkademik . ','. $nama . ',' . $prodi . ',' . $npm . ',' . $namaWakil . ',' . $prodiWakil . ',' . $npmWakil . ',' . $dosenWali . ',' . $alasan . ',' . $kodeMK1 . ',' . $matkul1 . ',' . $sks1 . ',' . $kodeMK2 . ',' . $matkul2 . ',' . $sks2 . ',' . $kodeMK3 . ',' . $matkul3 . ','. $sks3 . ',' . $tanggal . '}';
        // dd($entry);
        $fileTemplate = file('format_surat_latex/surat_perwakilan_perwalian_3mk.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_perwakilan_perwalian_3mk.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_perwakilan_perwalian_3mk.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = Mahasiswa::where('id',$pemesan)->first()->dosen->nama_dosen;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_perwakilan_perwalian_3mk.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "14"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = Dosen::where('id',$json->dosenWali)->first()->nama_dosen;
        $alasan = $json->alasan;
        $kodeMK1 = $json->kodeMK1;
        $matkul1 = $json->matkul1;
        $sks1 = $json->sks1;
        $kodeMK2 = $json->kodeMK2;
        $matkul2 = $json->matkul2;
        $sks2 = $json->sks2;
        $kodeMK3 = $json->kodeMK3;
        $matkul3 = $json->matkul3;
        $sks3 = $json->sks3;
        $kodeMK4 = $json->kodeMK4;
        $matkul4 = $json->matkul4;
        $sks4 = $json->sks4;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $semester . ',' . $thnAkademik  . ',' . $nama . ',' . $prodi . ',' . $npm . ',' . $namaWakil . ',' . $prodiWakil . ',' . $npmWakil . ',' . $dosenWali . ',' . $alasan . ',' . $kodeMK1 . ',' . $matkul1 . ',' . $sks1 . ',' . $kodeMK2 . ',' . $matkul2 . ',' . $sks2 . ',' . $kodeMK3 . ',' . $matkul3 . ',' . $sks3 . ',' . $kodeMK4 . ',' . $matkul4 . ',' . $sks4 . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_perwakilan_perwalian_4mk.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_perwakilan_perwalian_4mk.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_perwakilan_perwalian_4mk.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = Mahasiswa::where('id',$pemesan)->first()->dosen->nama_dosen;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_perwakilan_perwalian_4mk.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "15"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = Dosen::where('id',$json->dosenWali)->first()->nama_dosen;
        $alasan = $json->alasan;
        $kodeMK1 = $json->kodeMK1;
        $matkul1 = $json->matkul1;
        $sks1 = $json->sks1;
        $kodeMK2 = $json->kodeMK2;
        $matkul2 = $json->matkul2;
        $sks2 = $json->sks2;
        $kodeMK3 = $json->kodeMK3;
        $matkul3 = $json->matkul3;
        $sks3 = $json->sks3;
        $kodeMK4 = $json->kodeMK4;
        $matkul4 = $json->matkul4;
        $sks4 = $json->sks4;
        $kodeMK5 = $json->kodeMK5;
        $matkul5 = $json->matkul5;
        $sks5 = $json->sks5;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $semester . ',' . $thnAkademik  . ',' . $nama . ',' . $prodi . ',' . $npm . ',' . $namaWakil . ',' . $prodiWakil . ',' . $npmWakil . ',' . $dosenWali . ',' . $alasan . ',' . $kodeMK1 . ',' . $matkul1 . ',' . $sks1 . ',' . $kodeMK2 . ',' . $matkul2 . ',' . $sks2 . ',' . $kodeMK3 . ',' . $matkul3 . ',' . $sks3 . ',' . $kodeMK4 . ',' . $matkul4 . ',' . $sks4 . ',' . $kodeMK5 . ',' . $matkul5 . ',' . $sks5 . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_perwakilan_perwalian_5mk.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_perwakilan_perwalian_5mk.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_perwakilan_perwalian_5mk.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = Mahasiswa::where('id',$pemesan)->first()->dosen->nama_dosen;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_perwakilan_perwalian_5mk.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "16"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = Dosen::where('id',$json->dosenWali)->first()->nama_dosen;
        $alasan = $json->alasan;
        $kodeMK1 = $json->kodeMK1;
        $matkul1 = $json->matkul1;
        $sks1 = $json->sks1;
        $kodeMK2 = $json->kodeMK2;
        $matkul2 = $json->matkul2;
        $sks2 = $json->sks2;
        $kodeMK3 = $json->kodeMK3;
        $matkul3 = $json->matkul3;
        $sks3 = $json->sks3;
        $kodeMK4 = $json->kodeMK4;
        $matkul4 = $json->matkul4;
        $sks4 = $json->sks4;
        $kodeMK5 = $json->kodeMK5;
        $matkul5 = $json->matkul5;
        $sks5 = $json->sks5;
        $kodeMK6 = $json->kodeMK6;
        $matkul6 = $json->matkul6;
        $sks6 = $json->sks6;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $semester . ',' . $thnAkademik . ',' . $nama . ',' . $prodi . ',' . $npm . ',' . $namaWakil . ',' . $prodiWakil . ',' . $npmWakil . ',' . $dosenWali . ',' . $alasan . ',' . $kodeMK1 . ',' . $matkul1 . ',' . $sks1 . ',' . $kodeMK2 . ',' . $matkul2 . ',' . $sks2 . ',' . $kodeMK3 . ',' . $matkul3 . ',' . $sks3 . ',' . $kodeMK4 . ',' . $matkul4 . ',' . $sks4 . ',' . $kodeMK5 . ',' . $matkul5 . ',' . $sks5 . ',' . $kodeMK6 . ',' . $matkul6 . ',' . $sks6 . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_perwakilan_perwalian_6mk.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_perwakilan_perwalian_6mk.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_perwakilan_perwalian_6mk.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = Mahasiswa::where('id',$pemesan)->first()->dosen->nama_dosen;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_perwakilan_perwalian_6mk.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "17"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = Dosen::where('id',$json->dosenWali)->first()->nama_dosen;
        $alasan = $json->alasan;
        $kodeMK1 = $json->kodeMK1;
        $matkul1 = $json->matkul1;
        $sks1 = $json->sks1;
        $kodeMK2 = $json->kodeMK2;
        $matkul2 = $json->matkul2;
        $sks2 = $json->sks2;
        $kodeMK3 = $json->kodeMK3;
        $matkul3 = $json->matkul3;
        $sks3 = $json->sks3;
        $kodeMK4 = $json->kodeMK4;
        $matkul4 = $json->matkul4;
        $sks4 = $json->sks4;
        $kodeMK5 = $json->kodeMK5;
        $matkul5 = $json->matkul5;
        $sks5 = $json->sks5;
        $kodeMK6 = $json->kodeMK6;
        $matkul6 = $json->matkul6;
        $sks6 = $json->sks6;
        $kodeMK7 = $json->kodeMK7;
        $matkul7 = $json->matkul7;
        $sks7 = $json->sks7;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $semester . ',' . $thnAkademik . ',' . $nama . ',' . $prodi . ',' . $npm . ',' . $namaWakil . ',' . $prodiWakil . ',' . $npmWakil . ',' . $dosenWali . ',' . $alasan . ',' . $kodeMK1 . ',' . $matkul1 . ',' . $sks1 . ',' . $kodeMK2 . ',' . $matkul2 . ',' . $sks2 . ',' . $kodeMK3 . ',' . $matkul3 . ',' . $sks3 . ',' . $kodeMK4 . ',' . $matkul4 . ',' . $sks4 . ',' . $kodeMK5 . ',' . $matkul5 . ',' . $sks5 . ',' . $kodeMK6 . ',' . $matkul6 . ',' . $sks6 . ',' . $kodeMK7 . ',' . $matkul7 . ',' . $sks7 . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_perwakilan_perwalian_7mk.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_perwakilan_perwalian_7mk.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_perwakilan_perwalian_7mk.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = Mahasiswa::where('id',$pemesan)->first()->dosen->nama_dosen;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_perwakilan_perwalian_7mk.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "18"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = Dosen::where('id',$json->dosenWali)->first()->nama_dosen;
        $alasan = $json->alasan;
        $kodeMK1 = $json->kodeMK1;
        $matkul1 = $json->matkul1;
        $sks1 = $json->sks1;
        $kodeMK2 = $json->kodeMK2;
        $matkul2 = $json->matkul2;
        $sks2 = $json->sks2;
        $kodeMK3 = $json->kodeMK3;
        $matkul3 = $json->matkul3;
        $sks3 = $json->sks3;
        $kodeMK4 = $json->kodeMK4;
        $matkul4 = $json->matkul4;
        $sks4 = $json->sks4;
        $kodeMK5 = $json->kodeMK5;
        $matkul5 = $json->matkul5;
        $sks5 = $json->sks5;
        $kodeMK6 = $json->kodeMK6;
        $matkul6 = $json->matkul6;
        $sks6 = $json->sks6;
        $kodeMK7 = $json->kodeMK7;
        $matkul7 = $json->matkul7;
        $sks7 = $json->sks7;
        $kodeMK8 = $json->kodeMK8;
        $matkul8 = $json->matkul8;
        $sks8 = $json->sks8;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $semester . ',' . $thnAkademik . ',' . $nama . ',' . $prodi . ',' . $npm . ',' . $namaWakil . ',' . $prodiWakil . ',' . $npmWakil . ',' . $dosenWali . ',' . $alasan . ',' . $kodeMK1 . ',' . $matkul1 . ',' . $sks1 . ',' . $kodeMK2 . ',' . $matkul2 . ',' . $sks2 . ',' . $kodeMK3 . ',' . $matkul3 . ',' . $sks3 . ',' . $kodeMK4 . ',' . $matkul4 . ',' . $sks4 . ',' . $kodeMK5 . ',' . $matkul5 . ',' . $sks5 . ',' . $kodeMK6 . ',' . $matkul6 . ',' . $sks6 . ',' . $kodeMK7 . ',' . $matkul7 . ',' . $sks7 . ',' . $kodeMK8 . ',' . $matkul8 . ',' . $sks8 . ',' . $tanggal .  '}';
        $fileTemplate = file('format_surat_latex/surat_perwakilan_perwalian_8mk.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_perwakilan_perwalian_8mk.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_perwakilan_perwalian_8mk.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = Mahasiswa::where('id',$pemesan)->first()->dosen->nama_dosen;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_perwakilan_perwalian_8mk.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "19"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = Dosen::where('id',$json->dosenWali)->first()->nama_dosen;
        $alasan = $json->alasan;
        $kodeMK1 = $json->kodeMK1;
        $matkul1 = $json->matkul1;
        $sks1 = $json->sks1;
        $kodeMK2 = $json->kodeMK2;
        $matkul2 = $json->matkul2;
        $sks2 = $json->sks2;
        $kodeMK3 = $json->kodeMK3;
        $matkul3 = $json->matkul3;
        $sks3 = $json->sks3;
        $kodeMK4 = $json->kodeMK4;
        $matkul4 = $json->matkul4;
        $sks4 = $json->sks4;
        $kodeMK5 = $json->kodeMK5;
        $matkul5 = $json->matkul5;
        $sks5 = $json->sks5;
        $kodeMK6 = $json->kodeMK6;
        $matkul6 = $json->matkul6;
        $sks6 = $json->sks6;
        $kodeMK7 = $json->kodeMK7;
        $matkul7 = $json->matkul7;
        $sks7 = $json->sks7;
        $kodeMK8 = $json->kodeMK8;
        $matkul8 = $json->matkul8;
        $sks8 = $json->sks8;
        $kodeMK9 = $json->kodeMK9;
        $matkul9 = $json->matkul9;
        $sks9 = $json->sks9;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $semester . ',' . $thnAkademik . ',' . $nama . ',' . $prodi . ',' . $npm . ',' . $namaWakil . ',' . $prodiWakil . ',' . $npmWakil . ',' . $dosenWali . ',' . $alasan . ',' . $kodeMK1 . ',' . $matkul1 . ',' . $sks1 . ',' . $kodeMK2 . ',' . $matkul2 . ',' . $sks2 . ',' . $kodeMK3 . ',' . $matkul3 . ',' . $sks3 . ',' . $kodeMK4 . ',' . $matkul4 . ',' . $sks4 . ',' . $kodeMK5 . ',' . $matkul5 . ',' . $sks5 . ',' . $kodeMK6 . ',' . $matkul6 . ',' . $sks6 . ',' . $kodeMK7 . ',' . $matkul7 . ',' . $sks7 . ',' . $kodeMK8 . ',' . $matkul8 . ',' . $sks8 . ',' . $kodeMK9 . ',' . $matkul9 . ',' . $sks9 . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_perwakilan_perwalian_9mk.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_perwakilan_perwalian_9mk.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_perwakilan_perwalian_9mk.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = Mahasiswa::where('id',$pemesan)->first()->dosen->nama_dosen;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_perwakilan_perwalian_9mk.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
      else if($request->idFormatSurat == "20"){
        $dataSurat = $request->data;
        $json = json_decode($dataSurat);
        $noSurat = $request->noSurat;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $this->jurusanRepo->findJurusanById($json->prodi)->nama_jurusan;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = Dosen::where('id',$json->dosenWali)->first()->nama_dosen;
        $alasan = $json->alasan;
        $kodeMK1 = $json->kodeMK1;
        $matkul1 = $json->matkul1;
        $sks1 = $json->sks1;
        $kodeMK2 = $json->kodeMK2;
        $matkul2 = $json->matkul2;
        $sks2 = $json->sks2;
        $kodeMK3 = $json->kodeMK3;
        $matkul3 = $json->matkul3;
        $sks3 = $json->sks3;
        $kodeMK4 = $json->kodeMK4;
        $matkul4 = $json->matkul4;
        $sks4 = $json->sks4;
        $kodeMK5 = $json->kodeMK5;
        $matkul5 = $json->matkul5;
        $sks5 = $json->sks5;
        $kodeMK6 = $json->kodeMK6;
        $matkul6 = $json->matkul6;
        $sks6 = $json->sks6;
        $kodeMK7 = $json->kodeMK7;
        $matkul7 = $json->matkul7;
        $sks7 = $json->sks7;
        $kodeMK8 = $json->kodeMK8;
        $matkul8 = $json->matkul8;
        $sks8 = $json->sks8;
        $kodeMK9 = $json->kodeMK9;
        $matkul9 = $json->matkul9;
        $sks9 = $json->sks9;
        $kodeMK10 = $json->kodeMK10;
        $matkul10 = $json->matkul10;
        $sks10 = $json->sks10;
        $pemesan = $request->pemesan;
         //tanggal surat dibuat
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $tanggal = $getTanggal->format("j F Y");

        $entry = '\mailentry{' . $semester . ',' . $thnAkademik . ',' . $nama . ',' . $prodi . ',' . $npm . ',' . $namaWakil . ',' . $prodiWakil . ',' . $npmWakil . ',' . $dosenWali . ',' . $alasan . ',' . $kodeMK1 . ',' . $matkul1 . ',' . $sks1 . ',' . $kodeMK2 . ',' . $matkul2 . ',' . $sks2 . ',' . $kodeMK3 . ',' . $matkul3 . ',' . $sks3 . ',' . $kodeMK4 . ',' . $matkul4 . ',' . $sks4 . ',' . $kodeMK5 . ',' . $matkul5 . ',' . $sks5 . ',' . $kodeMK6 . ',' . $matkul6 . ',' . $sks6 . ',' .$kodeMK7 . ',' . $matkul7 . ',' . $sks7 . ',' . $kodeMK8 . ',' . $matkul8 . ',' . $sks8 . ',' . $kodeMK9 . ',' . $matkul9 . ',' . $sks9 . ',' . $kodeMK10 . ',' . $matkul10 . ',' . $sks10 . ',' . $tanggal . '}';
        $fileTemplate = file('format_surat_latex/surat_perwakilan_perwalian_10mk.tex');
        $stringFormat = "";
        $baris = count($fileTemplate);
        // dd($baris);
        foreach ($fileTemplate as $line_num => $line) {
            // dd($line);
            $stringFormat .= $line;
            if($line_num == $baris-3){
                $stringFormat .= $entry;
            }
        }
        // dd($stringFormat);
        $file = fopen("arsip_surat/" . $npm . "_surat_perwakilan_perwalian_10mk.tex", "w");
        fwrite($file, $stringFormat);
        fclose($file);
        shell_exec('pdflatex -output-directory arsip_surat arsip_surat/' . $npm . '_surat_perwakilan_perwalian_10mk.tex');

        //store to db
        $historysurat = new Historysurat;
        $historysurat->no_surat = $noSurat;
        $historysurat->perihal = '-';
        $historysurat->penerimaSurat = Mahasiswa::where('id',$pemesan)->first()->dosen->nama_dosen;
        $historysurat->mahasiswa_id = $pemesan;
        $historysurat->formatsurat_id = $request->idFormatSurat;
        $historysurat->link_arsip_surat = 'arsip_surat/' . $npm . '_surat_perwakilan_perwalian_10mk.pdf';
        $historysurat->penandatanganan = false;
        $historysurat->pengambilan = false;
        $historysurat->save();
        return redirect('/history_TU');
      }
  }
}
