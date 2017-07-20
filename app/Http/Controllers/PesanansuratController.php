<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repositories\PesanansuratRepository;
use App\Repositories\FormatsuratRepository;
use App\Repositories\MahasiswaRepository;
use App\Pesanansurat;
use App\Formatsurat;
use Illuminate\Support\Facades\Auth;
use App\Mahasiswa;
use App\User;
use App\Dosen;
use App\TU;
class PesanansuratController extends Controller
{
    //
    protected $pesanansuratRepo;
    protected $formatsuratRepo;
    protected $mahasiswaRepo;
    public function __construct(PesanansuratRepository $pesanansuratRepo, FormatsuratRepository $formatsuratRepo, MahasiswaRepository $mahasiswaRepo){
      // dd($pesanansuratRepo);
        $this->pesanansuratRepo = $pesanansuratRepo;
        $this->formatsuratRepo = $formatsuratRepo;
        $this->mahasiswaRepo = $mahasiswaRepo;
        //dd($this->orders->getAllActive());
    }
    
    public function tampilkanPesananDiPejabat(Request $request){
      // $pesanansurats = $this->pesanansuratRepo->findAllPesananSurat();

      $loggedInUser = Auth::user();
      $realUser = $this->getRealUser($loggedInUser);

      $results = [];

      //CEK USER DEKAN
      if($realUser->id == $realUser->fakultas->id_dekan){
        // dd("s");
        $tempPesananSurats = PesananSurat::where('count','=',4)->get();
        foreach ($tempPesananSurats as $key => $surat) {
          array_push($results,$surat);
        }
        return view('pejabat.home_pejabat', [
          'pesanansurats' => $results,
          'user' => $realUser
        ]);
      }

      //CEK USER WD1
      if($realUser->id == $realUser->fakultas->id_WD_I){
        $tempPesananSurats = PesananSurat::where('count','=',3)->get();
        foreach ($tempPesananSurats as $key => $surat) {
          array_push($results,$surat);
        }
      }

      //CEK USER WD2
      // dd($realUser->id.' - '.$realUser->fakultas);
      if($realUser->id == $realUser->fakultas->id_WD_II){
        // dd("s");
        $tempPesananSurats = PesananSurat::where('count','=',2)->get();
        // dd($tempPesananSurats);
        foreach ($tempPesananSurats as $key => $surat) {
          // dd('asd');
          array_push($results,$surat);
        }
      }

      // CEK KALO USER ADALAH KETUA JURUSAN
      if($realUser->id == $realUser->jurusan->dosen->id){
          $tempPesananSurats = PesananSurat::where('count','=',1)->get();
           foreach ($tempPesananSurats as $key => $surat) {
             if($realUser->jurusan->id == $surat->mahasiswa->jurusan->id){
               array_push($results,$surat);
             }
           }
       }

       foreach ($realUser->mahasiswas as $key => $mhs) {
         foreach ($mhs->pesanansurats as $key => $surat) {
           array_push($results,$surat);
          }
       }
      return view('pejabat.home_pejabat', [
        'pesanansurats' => $results,
        'user' => $realUser
      ]);    
    }

    public function persetujuanPesananDiPejabat(Request $request){
      $loggedInUser = Auth::user();
      $realUser = $this->getRealUser($loggedInUser);
      $results = [];
       foreach ($realUser->mahasiswas as $key => $mhs) {
         foreach ($mhs->pesanansurats as $key => $surat) {
           array_push($results,$surat);
          }
       }
      return view('pejabat.persetujuan_pejabat', [
        'pesanansurats' => $results,
        'user' => $realUser
      ]);    
    }

    public function proses(Request $request){
      $loggedInUser = Auth::user();
      $realUser = $this->getRealUser($loggedInUser);
      
      $results = $realUser->pesanansurats;
      
      return view('mahasiswa.proses_surat', [
        'pesanansurats' => $results,
        'user' => $realUser
      ]);
    }

    public function downloadLampiran(Request $request){
      $reqLink = $request->link;
      $getLink = explode("/", $reqLink);
      $link = $getLink[1] . "/" . $getLink[2] . "/" . $getLink[3];
      return redirect($link);
    }

    public function updateFormulir(Request $request){
      // dd($request);
      $jsonArray = json_decode($request->dataSurat);
      $surat = $this->pesanansuratRepo->findPesananSuratById($request->idPesanansurat);
      // dd($surat);
      if($surat->count == 0){
        // DOSEN WALI = 0
        // dd($jsonArray);
        $dosenWali = explode('|',$request->catatan);
        $jsonArray->persetujuanDosenWali = $dosenWali[0];
        $jsonArray->catatanDosenWali = $dosenWali[1];
        $surat->persetujuanDosenWali = true;
        
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $surat->tglDosenWali = $getTanggal->format("j F Y");
        // dd($jsonArray);
      }else if($surat->count == 1){
      // KAPRODI = 1
        $kaprodi = explode('|',$request->kaprodi);
        $jsonArray->persetujuanKaprodi = $kaprodi[0];
        $jsonArray->catatanKaprodi = $kaprodi[1];
        $surat->persetujuanKaprodi = true;

        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $surat->tglKaprodi = $getTanggal->format("j F Y");
      }else if($surat->count == 2){
        // WD2 = 2
        $wd2 = explode('|',$request->wd2);
        $jsonArray->persetujuanWDII = $wd2[0];
        $jsonArray->catatanWDII = $wd2[1];
        $surat->persetujuanWDII = true;

        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $surat->tglWDII = $getTanggal->format("j F Y");
      }else if($surat->count == 3){
        // WD1 = 3
        $wd1 = explode('|',$request->wd1);
        $jsonArray->persetujuanWDI = $wd1[0];
        $jsonArray->catatanWDI = $wd1[1];
        $surat->persetujuanWDI = true;

        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $surat->tglWDI = $getTanggal->format("j F Y");
      }else if($surat->count == 4){
        // DEKAN = 4
          $jsonArray->persetujuanDekan = $request->dekan;
          $surat->persetujuanDekan = true;
          
        $getLocal = getdate();
        $toString = implode(" ", $getLocal);
        $getDate = explode(" ",$toString);
        $arrTanggal = $getDate[6].'-'.$getDate[5].'-'.$getDate[3];
        $getTanggal = date_create($arrTanggal);
        $surat->tglDekan = $getTanggal->format("j F Y");  
      }else{
        dd("count lebih dari 4 "+$surat->count);
      }
      $dataSuratUpdated = json_encode($jsonArray);
      // dd($dataSuratUpdated);
      $surat->dataSurat = $dataSuratUpdated;
      $surat->count += 1;
      $surat->save();
      // dd($surat);
      return redirect('/home_pejabat');
    }

    public function tambahPersetujuan(Request $request){
      $loggedInUser = Auth::user();
      $realUser = $this->getRealUser($loggedInUser);
      // dd($request->idPesananSurat);
      // $surat = PesananSurat::find($request->idPesananSurat);
      // dd($surat);
      // $surat->count += 1;
      // dd($surat);
      // $surat->save();

      $dataSurat = $request->dataSurat;
      $json = json_decode($dataSurat);
      $link = $json->link; 
      // dd($link);
      $formatsurat_id = $request->idFormatSurat;
      // dd($formatsurat_id);
      return view('pejabat.tambah_persetujuan',[
        'dataSurat' => $dataSurat,
        'formatsurat_id' => $formatsurat_id,
        'user' => $realUser,
        'idPesananSurat' => $request->idPesananSurat,
        'link' => $link
      ]);
    }

    public function previewDosen(Request $request){
      // dd($request);
        $loggedInUser = Auth::user();
      // dd($loggedInUser);
        $idPesanansurat = $request->idPesananSurat;
        // dd($idPesanansurat);
        $realUser = Dosen::find($loggedInUser->ref);
        // dd($realUser);
        if($request->formatsurat_id == "9"){
          $dataSurat = $request->dataSurat;
          $json = json_decode($dataSurat);
          $nama = $json->nama;
          $npm = $json->npm;
          $prodi = $json->prodi;
          $fakultas = $json->fakultas;
          $alamat = $json->alamat;
          $cutiStudiKe = $json->cutiStudiKe;
          $alasanCutiStudi = $json->alasanCutiStudi;
          $dosenWali = $json->dosenWali;
          $semester = $json->semester;
          $thnAkademik = $json->thnAkademik;
          $formatsurat_id = $request->idFormatSurat;
          $mhs = Mahasiswa::where('npm',$json->npm)->first();
          // $dataSurat = $this->buatJSON($request);
          // dd($request->formatsurat_id);
          // dd("asd "+$request->idPesananSurat);
          // dd("asd");
          if($realUser->id == "5"){
              $persetujuanDekan = $request->persetujuan;
              $arrayJson = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
              return view('pejabat.preview_izin_cuti_studi', [
                'idPesanansurat' => $idPesanansurat,
                'nama' => $nama,
                'npm' => $npm,
                'prodi' => $prodi,
                'fakultas' => $fakultas,
                'alamat' => $alamat,
                'cutiStudiKe' => $cutiStudiKe,
                'alasanCutiStudi' => $alasanCutiStudi,
                'dosenWali' => $dosenWali,
                'semester' => $semester,
                'thnAkademik' => $thnAkademik,
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser,
                'mhs' => $mhs,
                'persetujuanDosenWali' => $arrayJson->persetujuanDosenWali,
                'catatanDosenWali' => $arrayJson->catatanDosenWali,
                'persetujuanKaprodi' => $arrayJson->persetujuanKaprodi,
                'catatanKaprodi' => $arrayJson->catatanKaprodi,
                'persetujuanWDII' => $arrayJson->persetujuanWDII,
                'catatanWDII' => $arrayJson->catatanWDII,
                'persetujuanWDI' => $arrayJson->persetujuanWDI,
                'catatanWDI' => $arrayJson->catatanWDI,
                'persetujuanDekan' => $persetujuanDekan
              ]);
          }
          else if($realUser->id == "4"){
            $surat = PesananSurat::find($idPesanansurat);
              // dd($surat);
              if($surat->count == 0){
                $persetujuanDosenWali = $request->persetujuan;
                $catatanDosenWali = $request->catatan;
                return view('pejabat.preview_izin_cuti_studi', [
                  'idPesanansurat' => $idPesanansurat,
                  'nama' => $nama,
                  'npm' => $npm,
                  'prodi' => $prodi,
                  'fakultas' => $fakultas,
                  'alamat' => $alamat,
                  'cutiStudiKe' => $cutiStudiKe,
                  'alasanCutiStudi' => $alasanCutiStudi,
                  'dosenWali' => $dosenWali,
                  'semester' => $semester,
                  'thnAkademik' => $thnAkademik,
                  'formatsurat_id' => $formatsurat_id,
                  'dataSurat' => $dataSurat,
                  'user' => $realUser,
                  'mhs' => $mhs,
                  'persetujuanDosenWali' => $persetujuanDosenWali,
                  'catatanDosenWali' => $catatanDosenWali,
                  'persetujuanKaprodi' => '-',
                  'catatanKaprodi' => '-',
                  'persetujuanWDII' => '-',
                  'catatanWDII' => '-',
                  'persetujuanWDI'  => '-',
                  'catatanWDI' => '-',
                  'persetujuanDekan' => '-'
                ]);
              }
              else{
                $persetujuanWDI = $request->persetujuan;
                $catatanWDI = $request->catatan;
                $arrayJson = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
                return view('pejabat.preview_izin_cuti_studi', [
                  'idPesanansurat' => $idPesanansurat,
                  'nama' => $nama,
                  'npm' => $npm,
                  'prodi' => $prodi,
                  'fakultas' => $fakultas,
                  'alamat' => $alamat,
                  'cutiStudiKe' => $cutiStudiKe,
                  'alasanCutiStudi' => $alasanCutiStudi,
                  'dosenWali' => $dosenWali,
                  'semester' => $semester,
                  'thnAkademik' => $thnAkademik,
                  'formatsurat_id' => $formatsurat_id,
                  'dataSurat' => $dataSurat,
                  'user' => $realUser,
                  'mhs' => $mhs,
                  'persetujuanDosenWali' => $arrayJson->persetujuanDosenWali,
                  'catatanDosenWali' => $arrayJson->catatanDosenWali,
                  'persetujuanKaprodi' => $arrayJson->persetujuanKaprodi,
                  'catatanKaprodi' => $arrayJson->catatanKaprodi,
                  'persetujuanWDII' => $arrayJson->persetujuanWDII,
                  'catatanWDII' => $arrayJson->catatanWDII,
                  'persetujuanWDI' => $persetujuanWDI,
                  'catatanWDI' => $catatanWDI,
                  'persetujuanDekan' => '-'

                ]);
              }
          }
          else if($realUser->id == "3"){
            $surat = PesananSurat::find($idPesanansurat);
              // dd($surat);
              if($surat->count == 0){
                $persetujuanDosenWali = $request->persetujuan;
                $catatanDosenWali = $request->catatan;
                return view('pejabat.preview_izin_cuti_studi', [
                  'idPesanansurat' => $idPesanansurat,
                  'nama' => $nama,
                  'npm' => $npm,
                  'prodi' => $prodi,
                  'fakultas' => $fakultas,
                  'alamat' => $alamat,
                  'cutiStudiKe' => $cutiStudiKe,
                  'alasanCutiStudi' => $alasanCutiStudi,
                  'dosenWali' => $dosenWali,
                  'semester' => $semester,
                  'thnAkademik' => $thnAkademik,
                  'formatsurat_id' => $formatsurat_id,
                  'dataSurat' => $dataSurat,
                  'user' => $realUser,
                  'mhs' => $mhs,
                  'persetujuanDosenWali' => $persetujuanDosenWali,
                  'catatanDosenWali' => $catatanDosenWali,
                  'persetujuanKaprodi' => '-',
                  'catatanKaprodi' => '-',
                  'persetujuanWDII' => '-',
                  'catatanWDII' => '-',
                  'persetujuanWDI'  => '-',
                  'catatanWDI' => '-',
                  'persetujuanDekan' => '-'
                ]);
              }
              else{
                $persetujuanWDII = $request->persetujuan;
                $catatanWDII = $request->catatan;
                $arrayJson = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
                // dd($arrayJson);
                return view('pejabat.preview_izin_cuti_studi', [
                  'idPesanansurat' => $idPesanansurat,
                  'nama' => $nama,
                  'npm' => $npm,
                  'prodi' => $prodi,
                  'fakultas' => $fakultas,
                  'alamat' => $alamat,
                  'cutiStudiKe' => $cutiStudiKe,
                  'alasanCutiStudi' => $alasanCutiStudi,
                  'dosenWali' => $dosenWali,
                  'semester' => $semester,
                  'thnAkademik' => $thnAkademik,
                  'formatsurat_id' => $formatsurat_id,
                  'dataSurat' => $dataSurat,
                  'user' => $realUser,
                  'mhs' => $mhs,
                  'persetujuanDosenWali' => $arrayJson->persetujuanDosenWali,
                  'catatanDosenWali' => $arrayJson->catatanDosenWali,
                  'persetujuanKaprodi' => $arrayJson->persetujuanKaprodi,
                  'catatanKaprodi' => $arrayJson->catatanKaprodi,
                  'persetujuanWDII' => $persetujuanWDII,
                  'catatanWDII' => $catatanWDII,
                  'persetujuanWDI'  => '-',
                  'catatanWDI' => '-',
                  'persetujuanDekan' => '-'

                ]);
              }
          }
          else if($realUser->id == "6"){
              $surat = PesananSurat::find($idPesanansurat);
              // dd($surat);
              if($surat->count == 0){
                $persetujuanDosenWali = $request->persetujuan;
                $catatanDosenWali = $request->catatan;
                return view('pejabat.preview_izin_cuti_studi', [
                  'idPesanansurat' => $idPesanansurat,
                  'nama' => $nama,
                  'npm' => $npm,
                  'prodi' => $prodi,
                  'fakultas' => $fakultas,
                  'alamat' => $alamat,
                  'cutiStudiKe' => $cutiStudiKe,
                  'alasanCutiStudi' => $alasanCutiStudi,
                  'dosenWali' => $dosenWali,
                  'semester' => $semester,
                  'thnAkademik' => $thnAkademik,
                  'formatsurat_id' => $formatsurat_id,
                  'dataSurat' => $dataSurat,
                  'user' => $realUser,
                  'mhs' => $mhs,
                  'persetujuanDosenWali' => $persetujuanDosenWali,
                  'catatanDosenWali' => $catatanDosenWali,
                  'persetujuanKaprodi' => '-',
                  'catatanKaprodi' => '-',
                  'persetujuanWDII' => '-',
                  'catatanWDII' => '-',
                  'persetujuanWDI'  => '-',
                  'catatanWDI' => '-',
                  'persetujuanDekan' => '-'
                ]);
              }else{
                // dd("asd");
                $persetujuanKaprodi = $request->persetujuan;
                $catatanKaprodi = $request->catatan;
                $jsonArray = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
                // dd($jsonArray);
                return view('pejabat.preview_izin_cuti_studi', [
                  'idPesanansurat' => $idPesanansurat,
                  'nama' => $nama,
                  'npm' => $npm,
                  'prodi' => $prodi,
                  'fakultas' => $fakultas,
                  'alamat' => $alamat,
                  'cutiStudiKe' => $cutiStudiKe,
                  'alasanCutiStudi' => $alasanCutiStudi,
                  'dosenWali' => $dosenWali,
                  'semester' => $semester,
                  'thnAkademik' => $thnAkademik,
                  'formatsurat_id' => $formatsurat_id,
                  'dataSurat' => $dataSurat,
                  'user' => $realUser,
                  'mhs' => $mhs,
                  'persetujuanDosenWali' => $jsonArray->persetujuanDosenWali,
                  'catatanDosenWali' => $jsonArray->catatanDosenWali,
                  'persetujuanKaprodi' => $persetujuanKaprodi,
                  'catatanKaprodi' => $catatanKaprodi,
                  'persetujuanWDII' => '-',
                  'catatanWDII' => '-',
                  'persetujuanWDI'  => '-',
                  'catatanWDI' => '-',
                  'persetujuanDekan' => '-'
                ]);
              }
          }
          else if($realUser->id == "8"){
              $surat = PesananSurat::find($idPesanansurat);
              // dd($surat);
              if($surat->count == 0){
                $persetujuanDosenWali = $request->persetujuan;
                $catatanDosenWali = $request->catatan;
                return view('pejabat.preview_izin_cuti_studi', [
                  'idPesanansurat' => $idPesanansurat,
                  'nama' => $nama,
                  'npm' => $npm,
                  'prodi' => $prodi,
                  'fakultas' => $fakultas,
                  'alamat' => $alamat,
                  'cutiStudiKe' => $cutiStudiKe,
                  'alasanCutiStudi' => $alasanCutiStudi,
                  'dosenWali' => $dosenWali,
                  'semester' => $semester,
                  'thnAkademik' => $thnAkademik,
                  'formatsurat_id' => $formatsurat_id,
                  'dataSurat' => $dataSurat,
                  'user' => $realUser,
                  'mhs' => $mhs,
                  'persetujuanDosenWali' => $persetujuanDosenWali,
                  'catatanDosenWali' => $catatanDosenWali,
                  'persetujuanKaprodi' => '-',
                  'catatanKaprodi' => '-',
                  'persetujuanWDII' => '-',
                  'catatanWDII' => '-',
                  'persetujuanWDI'  => '-',
                  'catatanWDI' => '-',
                  'persetujuanDekan' => '-'
                ]);
              }else{
                // dd("asd");
                $persetujuanKaprodi = $request->persetujuan;
                $catatanKaprodi = $request->catatan;
                $jsonArray = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
                // dd($jsonArray);
                return view('pejabat.preview_izin_cuti_studi', [
                  'idPesanansurat' => $idPesanansurat,
                  'nama' => $nama,
                  'npm' => $npm,
                  'prodi' => $prodi,
                  'fakultas' => $fakultas,
                  'alamat' => $alamat,
                  'cutiStudiKe' => $cutiStudiKe,
                  'alasanCutiStudi' => $alasanCutiStudi,
                  'dosenWali' => $dosenWali,
                  'semester' => $semester,
                  'thnAkademik' => $thnAkademik,
                  'formatsurat_id' => $formatsurat_id,
                  'dataSurat' => $dataSurat,
                  'user' => $realUser,
                  'mhs' => $mhs,
                  'persetujuanDosenWali' => $jsonArray->persetujuanDosenWali,
                  'catatanDosenWali' => $jsonArray->catatanDosenWali,
                  'persetujuanKaprodi' => $persetujuanKaprodi,
                  'catatanKaprodi' => $catatanKaprodi,
                  'persetujuanWDII' => '-',
                  'catatanWDII' => '-',
                  'persetujuanWDI'  => '-',
                  'catatanWDI' => '-',
                  'persetujuanDekan' => '-'
                ]);
              }
          }
          else if($realUser->id == "9"){
              $surat = PesananSurat::find($idPesanansurat);
              // dd($surat);
              if($surat->count == 0){
                $persetujuanDosenWali = $request->persetujuan;
                $catatanDosenWali = $request->catatan;
                return view('pejabat.preview_izin_cuti_studi', [
                  'idPesanansurat' => $idPesanansurat,
                  'nama' => $nama,
                  'npm' => $npm,
                  'prodi' => $prodi,
                  'fakultas' => $fakultas,
                  'alamat' => $alamat,
                  'cutiStudiKe' => $cutiStudiKe,
                  'alasanCutiStudi' => $alasanCutiStudi,
                  'dosenWali' => $dosenWali,
                  'semester' => $semester,
                  'thnAkademik' => $thnAkademik,
                  'formatsurat_id' => $formatsurat_id,
                  'dataSurat' => $dataSurat,
                  'user' => $realUser,
                  'mhs' => $mhs,
                  'persetujuanDosenWali' => $persetujuanDosenWali,
                  'catatanDosenWali' => $catatanDosenWali,
                  'persetujuanKaprodi' => '-',
                  'catatanKaprodi' => '-',
                  'persetujuanWDII' => '-',
                  'catatanWDII' => '-',
                  'persetujuanWDI'  => '-',
                  'catatanWDI' => '-',
                  'persetujuanDekan' => '-'
                ]);
              }else{
                // dd("asd");
                $persetujuanKaprodi = $request->persetujuan;
                $catatanKaprodi = $request->catatan;
                $jsonArray = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
                // dd($jsonArray);
                return view('pejabat.preview_izin_cuti_studi', [
                  'idPesanansurat' => $idPesanansurat,
                  'nama' => $nama,
                  'npm' => $npm,
                  'prodi' => $prodi,
                  'fakultas' => $fakultas,
                  'alamat' => $alamat,
                  'cutiStudiKe' => $cutiStudiKe,
                  'alasanCutiStudi' => $alasanCutiStudi,
                  'dosenWali' => $dosenWali,
                  'semester' => $semester,
                  'thnAkademik' => $thnAkademik,
                  'formatsurat_id' => $formatsurat_id,
                  'dataSurat' => $dataSurat,
                  'user' => $realUser,
                  'mhs' => $mhs,
                  'persetujuanDosenWali' => $jsonArray->persetujuanDosenWali,
                  'catatanDosenWali' => $jsonArray->catatanDosenWali,
                  'persetujuanKaprodi' => $persetujuanKaprodi,
                  'catatanKaprodi' => $catatanKaprodi,
                  'persetujuanWDII' => '-',
                  'catatanWDII' => '-',
                  'persetujuanWDI'  => '-',
                  'catatanWDI' => '-',
                  'persetujuanDekan' => '-'
                ]);
              }
          }
          else{
              $persetujuanDosenWali = $request->persetujuan;
              $catatanDosenWali = $request->catatan;
              return view('pejabat.preview_izin_cuti_studi', [
                'idPesanansurat' => $idPesanansurat,
                'nama' => $nama,
                'npm' => $npm,
                'prodi' => $prodi,
                'fakultas' => $fakultas,
                'alamat' => $alamat,
                'cutiStudiKe' => $cutiStudiKe,
                'alasanCutiStudi' => $alasanCutiStudi,
                'dosenWali' => $dosenWali,
                'semester' => $semester,
                'thnAkademik' => $thnAkademik,
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser,
                'mhs' => $mhs,
                'persetujuanDosenWali' => $persetujuanDosenWali,
                'catatanDosenWali' => $catatanDosenWali,
                'persetujuanKaprodi' => '-',
                'catatanKaprodi' => '-',
                'persetujuanWDII' => '-',
                'catatanWDII' => '-',
                'persetujuanWDI'  => '-',
                'catatanWDI' => '-',
                'persetujuanDekan' => '-'
              ]);
          }
          // dd($request);
        }
        else if($request->formatsurat_id == "10"){
          $dataSurat = $request->dataSurat;
          $json = json_decode($dataSurat);
          $nirm = $json->nirm;
          $nama = $json->nama;
          $npm = $json->npm;
          $alamat = $json->alamat;
          $noTelepon = $json->noTelepon;
          $namaOrtu = $json->namaOrtu;
          $dosenWali = $json->dosenWali;
          $semester = $json->semester;
          $formatsurat_id = $request->formatsurat_id;
          $mhs = Mahasiswa::where('npm',$json->npm)->first();
          if($realUser->id == "5"){
              $persetujuanDekan = $request->persetujuan;
              $catatanDekan = $request->catatan;
              $arrayJson = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $arrayJson->persetujuanDosenWali,
                'catatanDosenWali' => $arrayJson->catatanDosenWali,
                'persetujuanKaprodi' => $arrayJson->persetujuanKaprodi,
                'catatanKaprodi' => $arrayJson->catatanKaprodi,
                'persetujuanWDII' => $arrayJson->persetujuanWDII,
                'catatanWDII' => $arrayJson->catatanWDII,
                'persetujuanWDI' => $arrayJson->persetujuanWDI,
                'catatanWDI' => $arrayJson->catatanWDI,
                'persetujuanDekan' => $persetujuanDekan,
                'catatanDekan' => $catatanDekan,
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
              ]);
          }
          else if($realUser->id == "4"){
            $surat = PesananSurat::find($idPesanansurat);
            if($surat->count == 0){
              $persetujuanDosenWali = $request->persetujuan;
              $catatanDosenWali = $request->catatan;
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $persetujuanDosenWali,
                'catatanDosenWali' => $catatanDosenWali,
                'persetujuanKaprodi' => '-',
                'catatanKaprodi' => '-',
                'persetujuanWDII' => '-',
                'catatanWDII' => '-',
                'persetujuanWDI' => '-',
                'catatanWDI' => '-',
                'persetujuanDekan' => '-',
                'catatanDekan' => '-',
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
              ]);
            }else{
              $persetujuanWDI = $request->persetujuan;
              $catatanWDI = $request->catatan;
              $jsonArray = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $jsonArray->persetujuanDosenWali,
                'catatanDosenWali' => $jsonArray->catatanDosenWali,
                'persetujuanKaprodi' => $jsonArray->persetujuanKaprodi,
                'catatanKaprodi' => $jsonArray->catatanKaprodi,
                'persetujuanWDII' => $jsonArray->persetujuanWDII,
                'catatanWDII' => $jsonArray->catatanWDII,
                'persetujuanWDI' => $persetujuanWDI,
                'catatanWDI' => $catatanWDI,
                'persetujuanDekan' => '-',
                'catatanDekan' => '-',
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
              ]);
            }
          }
          else if($realUser->id == "3"){
            $surat = PesananSurat::find($idPesanansurat);
            if($surat->count == 0){
              $persetujuanDosenWali = $request->persetujuan;
              $catatanDosenWali = $request->catatan;
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $persetujuanDosenWali,
                'catatanDosenWali' => $catatanDosenWali,
                'persetujuanKaprodi' => '-',
                'catatanKaprodi' => '-',
                'persetujuanWDII' => '-',
                'catatanWDII' => '-',
                'persetujuanWDI' => '-',
                'catatanWDI' => '-',
                'persetujuanDekan' => '-',
                'catatanDekan' => '-',
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
              ]);
            }else{
              $persetujuanWDII = $request->persetujuan;
              $catatanWDII = $request->catatan;
              $jsonArray = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $jsonArray->persetujuanDosenWali,
                'catatanDosenWali' => $jsonArray->catatanDosenWali,
                'persetujuanKaprodi' => $jsonArray->persetujuanKaprodi,
                'catatanKaprodi' => $jsonArray->catatanKaprodi,
                'persetujuanWDII' => $persetujuanWDII,
                'catatanWDII' => $catatanWDII,
                'persetujuanWDI' => '-',
                'catatanWDI' => '-',
                'persetujuanDekan' => '-',
                'catatanDekan' => '-',
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
              ]);
            }
          }
          else if($realUser->id == "6"){
            $surat = PesananSurat::find($idPesanansurat);
            if($surat->count == 0){
              $persetujuanDosenWali = $request->persetujuan;
              $catatanDosenWali = $request->catatan;
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $persetujuanDosenWali,
                'catatanDosenWali' => $catatanDosenWali,
                'persetujuanKaprodi' => '-',
                'catatanKaprodi' => '-',
                'persetujuanWDII' => '-',
                'catatanWDII' => '-',
                'persetujuanWDI' => '-',
                'catatanWDI' => '-',
                'persetujuanDekan' => '-',
                'catatanDekan' => '-',
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
              ]);
            }else{
              $persetujuanKaprodi = $request->persetujuan;
              $catatanKaprodi = $request->catatan;
              $jsonArray = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $jsonArray->persetujuanDosenWali,
                'catatanDosenWali' => $jsonArray->catatanDosenWali,
                'persetujuanKaprodi' => $persetujuanKaprodi,
                'catatanKaprodi' => $catatanKaprodi,
                'persetujuanWDII' => '-',
                'catatanWDII' => '-',
                'persetujuanWDI' => '-',
                'catatanWDI' => '-',
                'persetujuanDekan' => '-',
                'catatanDekan' => '-',
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
              ]);
            }
          }
          else if($realUser->id == "8"){
            $surat = PesananSurat::find($idPesanansurat);
            if($surat->count == 0){
              $persetujuanDosenWali = $request->persetujuan;
              $catatanDosenWali = $request->catatan;
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $persetujuanDosenWali,
                'catatanDosenWali' => $catatanDosenWali,
                'persetujuanKaprodi' => '-',
                'catatanKaprodi' => '-',
                'persetujuanWDII' => '-',
                'catatanWDII' => '-',
                'persetujuanWDI' => '-',
                'catatanWDI' => '-',
                'persetujuanDekan' => '-',
                'catatanDekan' => '-',
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
              ]);
            }else{
              $persetujuanKaprodi = $request->persetujuan;
              $catatanKaprodi = $request->catatan;
              $jsonArray = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $jsonArray->persetujuanDosenWali,
                'catatanDosenWali' => $jsonArray->catatanDosenWali,
                'persetujuanKaprodi' => $persetujuanKaprodi,
                'catatanKaprodi' => $catatanKaprodi,
                'persetujuanWDII' => '-',
                'catatanWDII' => '-',
                'persetujuanWDI' => '-',
                'catatanWDI' => '-',
                'persetujuanDekan' => '-',
                'catatanDekan' => '-',
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
              ]);
            }
          }
          else if($realUser->id == "9"){
            $surat = PesananSurat::find($idPesanansurat);
            if($surat->count == 0){
              $persetujuanDosenWali = $request->persetujuan;
              $catatanDosenWali = $request->catatan;
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $persetujuanDosenWali,
                'catatanDosenWali' => $catatanDosenWali,
                'persetujuanKaprodi' => '-',
                'catatanKaprodi' => '-',
                'persetujuanWDII' => '-',
                'catatanWDII' => '-',
                'persetujuanWDI' => '-',
                'catatanWDI' => '-',
                'persetujuanDekan' => '-',
                'catatanDekan' => '-',
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
              ]);
            }else{
              $persetujuanKaprodi = $request->persetujuan;
              $catatanKaprodi = $request->catatan;
              $jsonArray = json_decode(PesananSurat::find($idPesanansurat)->dataSurat);
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $jsonArray->persetujuanDosenWali,
                'catatanDosenWali' => $jsonArray->catatanDosenWali,
                'persetujuanKaprodi' => $persetujuanKaprodi,
                'catatanKaprodi' => $catatanKaprodi,
                'persetujuanWDII' => '-',
                'catatanWDII' => '-',
                'persetujuanWDI' => '-',
                'catatanWDI' => '-',
                'persetujuanDekan' => '-',
                'catatanDekan' => '-',
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
              ]);
            }
          }
          else{
              $persetujuanDosenWali = $request->persetujuan;
              $catatanDosenWali = $request->catatan;
              return view('pejabat.preview_izin_pengunduran_diri', [
                'idPesanansurat' => $idPesanansurat,
                'nirm' => $nirm,
                'nama' => $nama,
                'npm' => $npm,
                'alamat' => $alamat,
                'noTelepon' => $noTelepon,
                'namaOrtu' => $namaOrtu,
                'semester' => $semester,
                'persetujuanDosenWali' => $persetujuanDosenWali,
                'catatanDosenWali' => $catatanDosenWali,
                'persetujuanKaprodi' => '-',
                'catatanKaprodi' => '-',
                'persetujuanWDII' => '-',
                'catatanWDII' => '-',
                'persetujuanWDI' => '-',
                'catatanWDI' => '-',
                'persetujuanDekan' => '-',
                'catatanDekan' => '-',
                'formatsurat_id' => $formatsurat_id,
                'dataSurat' => $dataSurat,
                'user' => $realUser
            ]);
          }   
      }
    }

    private function getRealUser($loggedInUser){
      $realUser="";
      if($loggedInUser->jabatan == User::JABATAN_MHS){
        // dd($loggedInUser);
        $realUser = Mahasiswa::find($loggedInUser->ref);
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
    }

    public function tampilkanPesananSurat(Request $request){
          $loggedInUser = Auth::user();
          // dd($loggedInUser);
          $realUser = $this->getRealUser($loggedInUser);

          $pesanansurats;
          if($request->kategori == "jenis_surat"){
            $pesanansurats = $this->pesanansuratRepo->findPesanansuratByJenisSurat($request->searchBox);
          }
          else if($request->kategori == "penerimaSurat"){
            $pesanansurats = $this->pesanansuratRepo->findPesananSuratByPenerimaSurat($request->searchBox);
          }
          else if($request->kategori == "pemohonSurat"){
            $pesanansurats = $this->pesanansuratRepo->findPesananSuratByPemohonSurat($request->searchBox);
          }
          else if($request->kategori == "tanggalPembuatan"){
            $pesanansurats = $this->pesanansuratRepo->findMahasiswaByTanggalPembuatan($request->searchBox);
          }
          else{
            $pesanansurats = $this->pesanansuratRepo->findAllPesananSurat();
          }
          // dd($pesanansurats);
          return view('TU.home_TU',[
              'pesanansurats' => $pesanansurats,
              'user' => $realUser
          ]);
  	}

    public function persetujuanPesananSurat(Request $request){
          $loggedInUser = Auth::user();
          // dd($loggedInUser);
          $realUser = $this->getRealUser($loggedInUser);

          $pesanansurats;
          if($request->kategori == "jenis_surat"){
            $pesanansurats = $this->pesanansuratRepo->findPesanansuratByJenisSurat($request->searchBox);
          }
          else if($request->kategori == "penerimaSurat"){
            $pesanansurats = $this->pesanansuratRepo->findPesananSuratByPenerimaSurat($request->searchBox);
          }
          else if($request->kategori == "pemohonSurat"){
            $pesanansurats = $this->pesanansuratRepo->findPesananSuratByPemohonSurat($request->searchBox);
          }
          else if($request->kategori == "tanggalPembuatan"){
            $pesanansurats = $this->pesanansuratRepo->findMahasiswaByTanggalPembuatan($request->searchBox);
          }
          else{
            $pesanansurats = $this->pesanansuratRepo->findPesananSurat();
          }
          // dd($pesanansurats);
          return view('TU.persetujuan_surat',[
              'pesanansurats' => $pesanansurats,
              'user' => $realUser
          ]);
  	}

    public function sendDataSurat(Request $request){
      $loggedInUser = Auth::user();
      // dd($loggedInUser);
      $realUser = $this->getRealUser($loggedInUser);
      
      if($request->idFormatSurat == "1"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        // dd($user);
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $penyediabeasiswa = $json->penyediabeasiswa;
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        return view('TU.proses_surat_keterangan_beasiswa', [
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'penyediabeasiswa' => $penyediabeasiswa,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "2"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $kota_lahir = $json->kota_lahir;
        $tglLahir = $json->tglLahir;
        $semester = $json->semester;
        $alamat = $json->alamat;
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        // dd($dataSurat);
        return view('TU.proses_surat_keterangan_mahasiswa_aktif', [
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'kota_lahir' => $kota_lahir,
            'tglLahir' => $tglLahir,
            'alamat' => $alamat,
            'semester' => $semester,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "3"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $nama = $json->nama;
        $tglLahir = $json->tglLahir;
        $kewarganegaraan = $json->kewarganegaraan;
        $organisasiTujuan = $json->organisasiTujuan;
        $thnAkademik = $json->thnAkademik;
        $negaraTujuan = $json->negaraTujuan;
        $tanggalKunjungan = date_create($json->tanggalKunjungan)->format("j F Y");
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        // $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_pembuatan_visa', [
            'nama' => $nama,
            'tglLahir' => $tglLahir,
            'kewarganegaraan' => $kewarganegaraan,
            'organisasiTujuan' => $organisasiTujuan,
            'thnAkademik' => $thnAkademik,
            'negaraTujuan' => $negaraTujuan,
            'tanggalKunjungan' => $tanggalKunjungan,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            // 'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "4"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $json->prodi;
        $matkul = $json->matkul;
        $topik = $json->topik;
        $organisasi = $json->organisasi;
        $alamatOrganisasi = $json->alamatOrganisasi;
        $keperluanKunjungan = $json->keperluanKunjungan;
        $kota = $json->kota;
        $kepada = $json->kepada;
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        // dd($request);
        return view('TU.proses_surat_izin_studi_lapangan_1org', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'matkul' => $matkul,
            'topik' => $topik,
            'organisasi' => $organisasi,
            'alamatOrganisasi' => $alamatOrganisasi,
            'keperluanKunjungan' => $keperluanKunjungan,
            'kota' => $kota,
            'kepada' => $kepada,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "5"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $json->prodi;
        $matkul = $json->matkul;
        $topik = $json->topik;
        $organisasi = $json->organisasi;
        $alamatOrganisasi = $json->alamatOrganisasi;
        $keperluanKunjungan = $json->keperluanKunjungan;
        $kota = $json->kota;
        $kepada = $json->kepada;
        $namaAnggota = $json->namaAnggota;
        $npmAnggota = $json->npmAnggota;
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_izin_studi_lapangan_2org', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'matkul' => $matkul,
            'topik' => $topik,
            'organisasi' => $organisasi,
            'alamatOrganisasi' => $alamatOrganisasi,
            'keperluanKunjungan' => $keperluanKunjungan,
            'kota' => $kota,
            'kepada' => $kepada,
            'namaAnggota' => $namaAnggota,
            'npmAnggota' => $npmAnggota,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "6"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $json->prodi;
        $matkul = $json->matkul;
        $topik = $json->topik;
        $organisasi = $json->organisasi;
        $alamatOrganisasi = $json->alamatOrganisasi;
        $keperluanKunjungan = $json->keperluanKunjungan;
        $kota = $json->kota;
        $kepada = $json->kepada;
        $namaAnggota1 = $json->namaAnggota1;
        $npmAnggota1 = $json->npmAnggota1;
        $namaAnggota2 = $json->namaAnggota2;
        $npmAnggota2 = $json->npmAnggota2;
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_izin_studi_lapangan_3org', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'matkul' => $matkul,
            'topik' => $topik,
            'organisasi' => $organisasi,
            'alamatOrganisasi' => $alamatOrganisasi,
            'keperluanKunjungan' => $keperluanKunjungan,
            'kota' => $kota,
            'kepada' => $kepada,
            'namaAnggota1' => $namaAnggota1,
            'npmAnggota1' => $npmAnggota1,
            'namaAnggota2' => $namaAnggota2,
            'npmAnggota2' => $npmAnggota2,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "7"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $json->prodi;
        $matkul = $json->matkul;
        $topik = $json->topik;
        $organisasi = $json->organisasi;
        $alamatOrganisasi = $json->alamatOrganisasi;
        $keperluanKunjungan = $json->keperluanKunjungan;
        $kota = $json->kota;
        $kepada = $json->kepada;
        $namaAnggota1 = $json->namaAnggota1;
        $npmAnggota1 = $json->npmAnggota1;
        $namaAnggota2 = $json->namaAnggota2;
        $npmAnggota2 = $json->npmAnggota2;
        $namaAnggota3 = $json->namaAnggota3;
        $npmAnggota3 = $json->npmAnggota3;
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_izin_studi_lapangan_4org', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'matkul' => $matkul,
            'topik' => $topik,
            'organisasi' => $organisasi,
            'alamatOrganisasi' => $alamatOrganisasi,
            'keperluanKunjungan' => $keperluanKunjungan,
            'kota' => $kota,
            'kepada' => $kepada,
            'namaAnggota1' => $namaAnggota1,
            'npmAnggota1' => $npmAnggota1,
            'namaAnggota2' => $namaAnggota2,
            'npmAnggota2' => $npmAnggota2,
            'namaAnggota3' => $namaAnggota3,
            'npmAnggota3' => $npmAnggota3,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "8"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $json->prodi;
        $matkul = $json->matkul;
        $topik = $json->topik;
        $organisasi = $json->organisasi;
        $alamatOrganisasi = $json->alamatOrganisasi;
        $keperluanKunjungan = $json->keperluanKunjungan;
        $kota = $json->kota;
        $kepada = $json->kepada;
        $namaAnggota1 = $json->namaAnggota1;
        $npmAnggota1 = $json->npmAnggota1;
        $namaAnggota2 = $json->namaAnggota2;
        $npmAnggota2 = $json->npmAnggota2;
        $namaAnggota3 = $json->namaAnggota3;
        $npmAnggota3 = $json->npmAnggota3;
        $namaAnggota4 = $json->namaAnggota4;
        $npmAnggota4 = $json->npmAnggota4;
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_izin_studi_lapangan_5org', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'matkul' => $matkul,
            'topik' => $topik,
            'organisasi' => $organisasi,
            'alamatOrganisasi' => $alamatOrganisasi,
            'keperluanKunjungan' => $keperluanKunjungan,
            'kota' => $kota,
            'kepada' => $kepada,
            'namaAnggota1' => $namaAnggota1,
            'npmAnggota1' => $npmAnggota1,
            'namaAnggota2' => $namaAnggota2,
            'npmAnggota2' => $npmAnggota2,
            'namaAnggota3' => $namaAnggota3,
            'npmAnggota3' => $npmAnggota3,
            'namaAnggota4' => $namaAnggota4,
            'npmAnggota4' => $npmAnggota4,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "9"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $nama = $json->nama;
        $npm = $json->npm;
        $prodi = $json->prodi;
        $fakultas = $json->fakultas;
        $alamat = $json->alamat;
        $cutiStudiKe = $json->cutiStudiKe;
        $alasanCutiStudi = $json->alasanCutiStudi;
        $dosenWali = $json->dosenWali;
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $persetujuanDosenWali = $json->persetujuanDosenWali;
        $catatanDosenWali = $json->catatanDosenWali;
        $persetujuanKaprodi = $json->persetujuanKaprodi;
        $catatanKaprodi = $json->catatanKaprodi;
        $persetujuanWDII = $json->persetujuanWDII;
        $catatanWDII = $json->catatanWDII;
        $persetujuanWDI = $json->persetujuanWDI;
        $catatanWDI = $json->catatanWDI;
        $persetujuanDekan = $json->persetujuanDekan;
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        $link = $json->link;
        // dd($link);
        return view('TU.proses_surat_izin_cuti_studi', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'fakultas' => $fakultas,
            'alamat' => $alamat,
            'cutiStudiKe' => $cutiStudiKe,
            'alasanCutiStudi' => $alasanCutiStudi,
            'dosenWali' => $dosenWali,
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'persetujuanDosenWali' => $persetujuanDosenWali,
            'catatanDosenWali' => $catatanDosenWali,
            'persetujuanKaprodi' => $persetujuanKaprodi,
            'catatanKaprodi' => $catatanKaprodi,
            'persetujuanWDII' => $persetujuanWDII,
            'catatanWDII' => $catatanWDII,
            'persetujuanWDI' => $persetujuanWDI,
            'catatanWDI' => $catatanWDI,
            'persetujuanDekan' => $persetujuanDekan,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan,
            'link' => $link
        ]);
      }
      else if($request->idFormatSurat == "10"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $nirm = $json->nirm;
        $nama = $json->nama;
        $npm = $json->npm;
        $alamat = $json->alamat;
        $noTelepon = $json->noTelepon;
        $namaOrtu = $json->namaOrtu;
        $dosenWali = $json->dosenWali;
        $semester = $json->semester;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        $persetujuanDosenWali = $json->persetujuanDosenWali;
        $catatanDosenWali = $json->catatanDosenWali;
        $persetujuanKaprodi = $json->persetujuanKaprodi;
        $catatanKaprodi = $json->catatanKaprodi;
        $persetujuanWDII = $json->persetujuanWDII;
        $catatanWDII = $json->catatanWDII;
        $persetujuanWDI = $json->persetujuanWDI;
        $catatanWDI = $json->catatanWDI;
        $persetujuanDekan = $json->persetujuanDekan;
        $formatsurat_id = $request->idFormatSurat;
        $link = $json->link;
        return view('TU.proses_surat_izin_pengunduran_diri', [
            'nirm' => $nirm,
            'nama' => $nama,
            'npm' => $npm,
            'alamat' => $alamat,
            'noTelepon' => $noTelepon,
            'namaOrtu' => $namaOrtu,
            'dosenWali' => $dosenWali,
            'semester' => $semester,
            'persetujuanDosenWali' => $persetujuanDosenWali,
            'catatanDosenWali' => $catatanDosenWali,
            'persetujuanKaprodi' => $persetujuanKaprodi,
            'catatanKaprodi' => $catatanKaprodi,
            'persetujuanWDII' => $persetujuanWDII,
            'catatanWDII' => $catatanWDII,
            'persetujuanWDI' => $persetujuanWDI,
            'catatanWDI' => $catatanWDI,
            'persetujuanDekan' => $persetujuanDekan,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan,
            'link' => $link
        ]);
      }
      else if($request->idFormatSurat == "11"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = $json->dosenWali;
        $alasan = $json->alasan;
        $kodeMK =$json->kodeMK;
        $matkul = $json->matkul;
        $sks = $json->sks;
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_perwakilan_perwalian_1mk', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK' => $kodeMK,
            'matkul' => $matkul,
            'sks' => $sks,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "12"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = $json->dosenWali;
        $alasan = $json->alasan;
        $kodeMK1 = $json->kodeMK1;
        $matkul1 = $json->matkul1;
        $sks1 = $json->sks1;
        $kodeMK2 = $json->kodeMK2;
        $matkul2 = $json->matkul2;
        $sks2 = $json->sks2;
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_perwakilan_perwalian_2mk', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "13"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = $json->dosenWali;
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
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_perwakilan_perwalian_3mk', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "14"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = $json->dosenWali;
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
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_perwakilan_perwalian_4mk', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "15"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = $json->dosenWali;
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
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_perwakilan_perwalian_5mk', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "16"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = $json->dosenWali;
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
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_perwakilan_perwalian_6mk', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'kodeMK6' => $kodeMK6,
            'matkul6' => $matkul6,
            'sks6' => $sks6,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "17"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = $json->dosenWali;
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
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_perwakilan_perwalian_7mk', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'kodeMK6' => $kodeMK6,
            'matkul6' => $matkul6,
            'sks6' => $sks6,
            'kodeMK7' => $kodeMK7,
            'matkul7' => $matkul7,
            'sks7' => $sks7,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "18"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = $json->dosenWali;
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
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_perwakilan_perwalian_8mk', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'kodeMK6' => $kodeMK6,
            'matkul6' => $matkul6,
            'sks6' => $sks6,
            'kodeMK7' => $kodeMK7,
            'matkul7' => $matkul7,
            'sks7' => $sks7,
            'kodeMK8' => $kodeMK8,
            'matkul8' => $matkul8,
            'sks8' => $sks8,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "19"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = $json->dosenWali;
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
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_perwakilan_perwalian_9mk', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'kodeMK6' => $kodeMK6,
            'matkul6' => $matkul6,
            'sks6' => $sks6,
            'kodeMK7' => $kodeMK7,
            'matkul7' => $matkul7,
            'sks7' => $sks7,
            'kodeMK8' => $kodeMK8,
            'matkul8' => $matkul8,
            'sks8' => $sks8,
            'kodeMK9' => $kodeMK9,
            'matkul9' => $matkul9,
            'sks9' => $sks9,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
      else if($request->idFormatSurat == "20"){
        $dataSurat = $request->prosesSurat;
        $json = json_decode($dataSurat);
        $semester = $json->semester;
        $thnAkademik = $json->thnAkademik;
        $nama = $json->nama;
        $prodi = $json->prodi;
        $npm = $json->npm;
        $namaWakil = $json->namaWakil;
        $prodiWakil = $json->prodiWakil;
        $npmWakil = $json->npmWakil;
        $dosenWali = $json->dosenWali;
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
        $formatsurat_id = $request->idFormatSurat;
        $pesananID = $this->pesanansuratRepo->findPesananSuratById($request->id);
        $pemesan = $pesananID->mahasiswa_id;
        $tanggal = $pesananID->created_at;
        $mhs = Mahasiswa::where('npm',$json->npm)->first();
        return view('TU.proses_surat_perwakilan_perwalian_10mk', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'kodeMK6' => $kodeMK6,
            'matkul6' => $matkul6,
            'sks6' => $sks6,
            'kodeMK7' => $kodeMK7,
            'matkul7' => $matkul7,
            'sks7' => $sks7,
            'kodeMK8' => $kodeMK8,
            'matkul8' => $matkul8,
            'sks8' => $sks8,
            'kodeMK9' => $kodeMK9,
            'matkul9' => $matkul9,
            'sks9' => $sks9,
            'kodeMK10' => $kodeMK10,
            'matkul10' => $matkul10,
            'sks10' => $sks10,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'mhs' => $mhs,
            'user' => $realUser,
            'tanggal' => $tanggal,
            'pemesan' => $pemesan
        ]);
      }
    }

    public function store(Request $request){
      $loggedInUser = Auth::user();
      // dd($request->idFormat);
      // dd("DI LINE 1452 PesananSuratController.php | "+$request->idPesanansurat);
      $realUser = $this->getRealUser($loggedInUser);
        if($request->idFormat == "1"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = $request->provider;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "2"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = '-';
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "3"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = $request->organisasiTujuan;
          // dd($request->organisasiTujuan);
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "4"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = $request->organisasi;
          // dd($request->organisasi);
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "5"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = $request->organisasi;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "6"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = $request->organisasi;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "7"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = $request->organisasi;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "8"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = $request->organisasi;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "9"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = $realUser->nama_mahasiswa;
          // dd($realUser);
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = false ;
          $pesanansurat->persetujuanKaprodi = false;
          $pesanansurat->persetujuanWDII = false;
          $pesanansurat->persetujuanWDI = false;
          $pesanansurat->persetujuanDekan = false;
          $pesanansurat->save();
        }
        else if($request->idFormat == "10"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = 'Rektor';
          // $pesanansurat->dataSurat = $request->data;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = false ;
          $pesanansurat->persetujuanKaprodi = false;
          $pesanansurat->persetujuanWDII = false;
          $pesanansurat->persetujuanWDI = false;
          $pesanansurat->persetujuanDekan = false;
          $pesanansurat->save();
        }
        else if($request->idFormat == "11"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = Mahasiswa::where('id',$realUser->id)->first()->dosen->nama_dosen;
          // DD($request);
          // $pesanansurat->dataSurat = $request->data;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "12"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = Mahasiswa::where('id',$realUser->id)->first()->dosen->nama_dosen;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "13"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = Mahasiswa::where('id',$realUser->id)->first()->dosen->nama_dosen;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "14"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = Mahasiswa::where('id',$realUser->id)->first()->dosen->nama_dosen;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "15"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = Mahasiswa::where('id',$realUser->id)->first()->dosen->nama_dosen;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "16"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = Mahasiswa::where('id',$realUser->id)->first()->dosen->nama_dosen;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "17"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = Mahasiswa::where('id',$realUser->id)->first()->dosen->nama_dosen;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "18"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = Mahasiswa::where('id',$realUser->id)->first()->dosen->nama_dosen;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "19"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = Mahasiswa::where('id',$realUser->id)->first()->dosen->nama_dosen;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        else if($request->idFormat == "20"){
          $pesanansurat = new PesananSurat;
          $pesanansurat->mahasiswa_id = $realUser->id;
          $pesanansurat->formatsurat_id = $request->idFormat;
          $pesanansurat->penerimaSurat = Mahasiswa::where('id',$realUser->id)->first()->dosen->nama_dosen;
          $pesanansurat->dataSurat = $request->dataSurat;
          $pesanansurat->persetujuanDosenWali = true ;
          $pesanansurat->persetujuanKaprodi = true;
          $pesanansurat->persetujuanWDII = true;
          $pesanansurat->persetujuanWDI = true;
          $pesanansurat->persetujuanDekan = true;
          $pesanansurat->save();
        }
        return redirect('/home_mahasiswa');
    }

    /**
    * Untuk meng-generate JSON dari data input
    */
    private function buatJSON($request){
      $obj = "";
      if($request->jenis_surat == "1"){
          $obj = [
            'nama' => $request->nama,
            'prodi' => $request->prodi,
            'npm' => $request->npm,
            'semester' => $request->semester,
            'thnAkademik' => $request->thnAkademik,
            'penyediabeasiswa' => $request->penyediabeasiswa,
          ];
      }
      else if($request->jenis_surat == "2"){
          $obj = [
            'nama' => $request->nama,
            'prodi' => $request->prodi,
            'npm' => $request->npm,
            'kota_lahir' => $request->kota_lahir,
            'tglLahir' => $request->tglLahir,
            'alamat' => $request->alamat,
            'semester' => $request->semester,
          ];
      }
      else if($request->jenis_surat == "3"){
          $obj = [
            'nama' => $request->nama,
            'tglLahir' => $request->tglLahir,
            'kewarganegaraan' => $request->kewarganegaraan,
            'organisasiTujuan' => $request->organisasiTujuan,
            'thnAkademik' => $request->thnAkademik,
            'negaraTujuan' => $request->negaraTujuan,
            'tanggalKunjungan' => $request->tanggalKunjungan,
            'npm'=> $request->npm,
            'angkatan' => $request->angkatan
          ];
      }
      else if($request->jenis_surat == "4"){
          $obj = [
            'nama' => $request->nama,
            'npm' => $request->npm,
            'prodi' => $request->prodi,
            'matkul' => $request->matkul,
            'topik' => $request->topik,
            'organisasi' => $request->organisasi,
            'alamatOrganisasi' => $request->alamatOrganisasi,
            'keperluanKunjungan' => $request->keperluanKunjungan,
            'kota' => $request->kota,
            'kepada' => $request->kepada
          ];
      }
      else if($request->jenis_surat == "5"){
        $obj = [
          'nama' => $request->nama,
          'npm' => $request->npm,
          'prodi' => $request->prodi,
          'matkul' => $request->matkul,
          'topik' => $request->topik,
          'organisasi' => $request->organisasi,
          'alamatOrganisasi' => $request->alamatOrganisasi,
          'keperluanKunjungan' => $request->keperluanKunjungan,
          'kota' => $request->kota,
          'kepada' => $request->kepada,
          'namaAnggota' => $request->namaAnggota,
          'npmAnggota' => $request->npmAnggota,
        ];
      }
      else if($request->jenis_surat == "6"){
        $obj = [
          'nama' => $request->nama,
          'npm' => $request->npm,
          'prodi' => $request->prodi,
          'matkul' => $request->matkul,
          'topik' => $request->topik,
          'organisasi' => $request->organisasi,
          'alamatOrganisasi' => $request->alamatOrganisasi,
          'keperluanKunjungan' => $request->keperluanKunjungan,
          'kota' => $request->kota,
          'kepada' => $request->kepada,
          'namaAnggota1' => $request->namaAnggota1,
          'npmAnggota1' => $request->npmAnggota1,
          'namaAnggota2' => $request->namaAnggota2,
          'npmAnggota2' => $request->npmAnggota2
        ];
      }
      else if($request->jenis_surat == "7"){
        $obj = [
          'nama' => $request->nama,
          'npm' => $request->npm,
          'prodi' => $request->prodi,
          'matkul' => $request->matkul,
          'topik' => $request->topik,
          'organisasi' => $request->organisasi,
          'alamatOrganisasi' => $request->alamatOrganisasi,
          'keperluanKunjungan' => $request->keperluanKunjungan,
          'kota' => $request->kota,
          'kepada' => $request->kepada,
          'namaAnggota1' => $request->namaAnggota1,
          'npmAnggota1' => $request->npmAnggota1,
          'namaAnggota2' => $request->namaAnggota2,
          'npmAnggota2' => $request->npmAnggota2,
          'namaAnggota3' => $request->namaAnggota3,
          'npmAnggota3' => $request->npmAnggota3
        ];
      }
      else if($request->jenis_surat == "8"){
        $obj = [
          'nama' => $request->nama,
          'npm' => $request->npm,
          'prodi' => $request->prodi,
          'matkul' => $request->matkul,
          'topik' => $request->topik,
          'organisasi' => $request->organisasi,
          'alamatOrganisasi' => $request->alamatOrganisasi,
          'keperluanKunjungan' => $request->keperluanKunjungan,
          'kota' => $request->kota,
          'kepada' => $request->kepada,
          'namaAnggota1' => $request->namaAnggota1,
          'npmAnggota1' => $request->npmAnggota1,
          'namaAnggota2' => $request->namaAnggota2,
          'npmAnggota2' => $request->npmAnggota2,
          'namaAnggota3' => $request->namaAnggota3,
          'npmAnggota3' => $request->npmAnggota3,
          'namaAnggota4' => $request->namaAnggota4,
          'npmAnggota4' => $request->npmAnggota4
        ];
      }
      else if($request->jenis_surat == "9"){
        //upload
        $npm = $request->npm;
        $lampiran = $request->file('lampiran_CutiStudi');
        $destination_path = ('lampiran/cuti_studi/');
        $filename = $lampiran->getClientOriginalName();
        $savedLampiran = ($npm . '_' .$filename);
        $lampiran->move($destination_path, $savedLampiran);

        $link = '127.0.0.1:8000/lampiran/cuti_studi/' . $savedLampiran;
        $obj = [
          'nama' => $request->nama,
          'npm' => $npm,
          'prodi' => $request->prodi,
          'fakultas' => $request->fakultas,
          'alamat' => $request->alamat,
          'cutiStudiKe' => $request->cutiStudiKe,
          'alasanCutiStudi' => $request->alasanCutiStudi,
          'dosenWali' => $request->dosenWali,
          'semester' => $request->semester,
          'thnAkademik' => $request->thnAkademik,
          'persetujuanDosenWali' => $request->persetujuanDosenWali,
          'catatanDosenWali' => $request->catatanDosenWali,
          'persetujuanKaprodi' => $request->persetujuanKaprodi,
          'catatanKaprodi' => $request->catatanKaprodi,
          'persetujuanWDII' => $request->persetujuanWDII,
          'catatanWDII' => $request->catatanWDII,
          'persetujuanWDI' => $request->persetujuanWDI,
          'catatanWDI' => $request->catatanWDI,
          'persetujuanDekan' => $request->persetujuanDekan,
          'link' => $link
        ];
      }
      else if($request->jenis_surat == "10"){
        //upload
        $npm = $request->npm;
        $lampiran = $request->file('lampiran_PengunduranDiri');
        $destination_path = ('lampiran/pengunduran_diri/');
        // dd($lampiran);
        $filename = $lampiran->getClientOriginalName();
        $savedLampiran = ($npm . '_' .$filename);
        $lampiran->move($destination_path, $savedLampiran);
        // dd($request);
        $link = '127.0.0.1:8000/lampiran/' . $savedLampiran;
        $obj = [
          'nirm' => $request->nirm,
          'nama' => $request->nama,
          'npm' => $npm,
          'alamat' => $request->alamat,
          'noTelepon' => $request->noTelepon,
          'namaOrtu' => $request->namaOrtu,
          'dosenWali' => $request->dosenWali,
          'semester' => $request->semester,
          'persetujuanDosenWali' => $request->persetujuanDosenWali,
          'catatanDosenWali' => $request->catatanDosenWali,
          'persetujuanKaprodi' => $request->persetujuanKaprodi,
          'catatanKaprodi' => $request->catatanKaprodi,
          'persetujuanWDII' => $request->persetujuanWDII,
          'catatanWDII' => $request->catatanWDII,
          'persetujuanWDI' => $request->persetujuanWDI,
          'catatanWDI' => $request->catatanWDI,
          'persetujuanDekan' => $request->persetujuanDekan,
          'catatanDekan' => $request->catatanDekan,
          'prodi' => $request->prodi,
          'link' => $link
        ];
      }
      else if($request->jenis_surat == "11"){
        $obj = [
          'semester' => $request->semester,
          'thnAkademik' => $request->thnAkademik,
          'nama' => $request->nama,
          'prodi' => $request->prodi,
          'npm' => $request->npm,
          'namaWakil' => $request->namaWakil,
          'prodiWakil' => $request->prodiWakil,
          'npmWakil' => $request->npmWakil,
          'dosenWali' => $request->dosenWali,
          'alasan' => $request->alasan,
          'kodeMK' => $request->kodeMK,
          'matkul' => $request->matkul,
          'sks' => $request->sks,
          'formatsurat_id' => $request->formatsurat_id,
          'dataSurat' => $request->dataSurat
        ];
      }
      else if($request->jenis_surat == "12"){
        $obj = [
          'semester' => $request->semester,
          'thnAkademik' => $request->thnAkademik,
          'nama' => $request->nama,
          'prodi' => $request->prodi,
          'npm' => $request->npm,
          'namaWakil' => $request->namaWakil,
          'prodiWakil' => $request->prodiWakil,
          'npmWakil' => $request->npmWakil,
          'dosenWali' => $request->dosenWali,
          'alasan' => $request->alasan,
          'kodeMK1' => $request->kodeMK1,
          'matkul1' => $request->matkul1,
          'sks1' => $request->sks1,
          'kodeMK2' => $request->kodeMK2,
          'matkul2' => $request->matkul2,
          'sks2' => $request->sks2,
          'formatsurat_id' => $request->formatsurat_id,
          'dataSurat' => $request->dataSurat
        ];
      }
      else if($request->jenis_surat == "13"){
        $obj = [
          'semester' => $request->semester,
          'thnAkademik' => $request->thnAkademik,
          'nama' => $request->nama,
          'prodi' => $request->prodi,
          'npm' => $request->npm,
          'namaWakil' => $request->namaWakil,
          'prodiWakil' => $request->prodiWakil,
          'npmWakil' => $request->npmWakil,
          'dosenWali' => $request->dosenWali,
          'alasan' => $request->alasan,
          'kodeMK1' => $request->kodeMK1,
          'matkul1' => $request->matkul1,
          'sks1' => $request->sks1,
          'kodeMK2' => $request->kodeMK2,
          'matkul2' => $request->matkul2,
          'sks2' => $request->sks2,
          'kodeMK3' => $request->kodeMK3,
          'matkul3' => $request->matkul3,
          'sks3' => $request->sks3,
          'formatsurat_id' => $request->formatsurat_id,
          'dataSurat' => $request->dataSurat
        ];
      }
      else if($request->jenis_surat == "14"){
        $obj = [
          'semester' => $request->semester,
          'thnAkademik' => $request->thnAkademik,
          'nama' => $request->nama,
          'prodi' => $request->prodi,
          'npm' => $request->npm,
          'namaWakil' => $request->namaWakil,
          'prodiWakil' => $request->prodiWakil,
          'npmWakil' => $request->npmWakil,
          'dosenWali' => $request->dosenWali,
          'alasan' => $request->alasan,
          'kodeMK1' => $request->kodeMK1,
          'matkul1' => $request->matkul1,
          'sks1' => $request->sks1,
          'kodeMK2' => $request->kodeMK2,
          'matkul2' => $request->matkul2,
          'sks2' => $request->sks2,
          'kodeMK3' => $request->kodeMK3,
          'matkul3' => $request->matkul3,
          'sks3' => $request->sks3,
          'kodeMK4' => $request->kodeMK4,
          'matkul4' => $request->matkul4,
          'sks4' => $request->sks4,
          'formatsurat_id' => $request->formatsurat_id,
          'dataSurat' => $request->dataSurat
        ];
      }
      else if($request->jenis_surat == "15"){
        $obj = [
          'semester' => $request->semester,
          'thnAkademik' => $request->thnAkademik,
          'nama' => $request->nama,
          'prodi' => $request->prodi,
          'npm' => $request->npm,
          'namaWakil' => $request->namaWakil,
          'prodiWakil' => $request->prodiWakil,
          'npmWakil' => $request->npmWakil,
          'dosenWali' => $request->dosenWali,
          'alasan' => $request->alasan,
          'kodeMK1' => $request->kodeMK1,
          'matkul1' => $request->matkul1,
          'sks1' => $request->sks1,
          'kodeMK2' => $request->kodeMK2,
          'matkul2' => $request->matkul2,
          'sks2' => $request->sks2,
          'kodeMK3' => $request->kodeMK3,
          'matkul3' => $request->matkul3,
          'sks3' => $request->sks3,
          'kodeMK4' => $request->kodeMK4,
          'matkul4' => $request->matkul4,
          'sks4' => $request->sks4,
          'kodeMK5' => $request->kodeMK5,
          'matkul5' => $request->matkul5,
          'sks5' => $request->sks5,
          'formatsurat_id' => $request->formatsurat_id,
          'dataSurat' => $request->dataSurat
        ];
      }
      else if($request->jenis_surat == "16"){
        $obj = [
          'semester' => $request->semester,
          'thnAkademik' => $request->thnAkademik,
          'nama' => $request->nama,
          'prodi' => $request->prodi,
          'npm' => $request->npm,
          'namaWakil' => $request->namaWakil,
          'prodiWakil' => $request->prodiWakil,
          'npmWakil' => $request->npmWakil,
          'dosenWali' => $request->dosenWali,
          'alasan' => $request->alasan,
          'kodeMK1' => $request->kodeMK1,
          'matkul1' => $request->matkul1,
          'sks1' => $request->sks1,
          'kodeMK2' => $request->kodeMK2,
          'matkul2' => $request->matkul2,
          'sks2' => $request->sks2,
          'kodeMK3' => $request->kodeMK3,
          'matkul3' => $request->matkul3,
          'sks3' => $request->sks3,
          'kodeMK4' => $request->kodeMK4,
          'matkul4' => $request->matkul4,
          'sks4' => $request->sks4,
          'kodeMK5' => $request->kodeMK5,
          'matkul5' => $request->matkul5,
          'sks5' => $request->sks5,
          'kodeMK6' => $request->kodeMK6,
          'matkul6' => $request->matkul6,
          'sks6' => $request->sks6,
          'formatsurat_id' => $request->formatsurat_id,
          'dataSurat' => $request->dataSurat
        ];
      }
      else if($request->jenis_surat == "17"){
        $obj = [
          'semester' => $request->semester,
          'thnAkademik' => $request->thnAkademik,
          'nama' => $request->nama,
          'prodi' => $request->prodi,
          'npm' => $request->npm,
          'namaWakil' => $request->namaWakil,
          'prodiWakil' => $request->prodiWakil,
          'npmWakil' => $request->npmWakil,
          'dosenWali' => $request->dosenWali,
          'alasan' => $request->alasan,
          'kodeMK1' => $request->kodeMK1,
          'matkul1' => $request->matkul1,
          'sks1' => $request->sks1,
          'kodeMK2' => $request->kodeMK2,
          'matkul2' => $request->matkul2,
          'sks2' => $request->sks2,
          'kodeMK3' => $request->kodeMK3,
          'matkul3' => $request->matkul3,
          'sks3' => $request->sks3,
          'kodeMK4' => $request->kodeMK4,
          'matkul4' => $request->matkul4,
          'sks4' => $request->sks4,
          'kodeMK5' => $request->kodeMK5,
          'matkul5' => $request->matkul5,
          'sks5' => $request->sks5,
          'kodeMK6' => $request->kodeMK6,
          'matkul6' => $request->matkul6,
          'sks6' => $request->sks6,
          'kodeMK7' => $request->kodeMK7,
          'matkul7' => $request->matkul7,
          'sks7' => $request->sks7,
          'formatsurat_id' => $request->formatsurat_id,
          'dataSurat' => $request->dataSurat
        ];
      }
      else if($request->jenis_surat == "18"){
        $obj = [
          'semester' => $request->semester,
          'thnAkademik' => $request->thnAkademik,
          'nama' => $request->nama,
          'prodi' => $request->prodi,
          'npm' => $request->npm,
          'namaWakil' => $request->namaWakil,
          'prodiWakil' => $request->prodiWakil,
          'npmWakil' => $request->npmWakil,
          'dosenWali' => $request->dosenWali,
          'alasan' => $request->alasan,
          'kodeMK1' => $request->kodeMK1,
          'matkul1' => $request->matkul1,
          'sks1' => $request->sks1,
          'kodeMK2' => $request->kodeMK2,
          'matkul2' => $request->matkul2,
          'sks2' => $request->sks2,
          'kodeMK3' => $request->kodeMK3,
          'matkul3' => $request->matkul3,
          'sks3' => $request->sks3,
          'kodeMK4' => $request->kodeMK4,
          'matkul4' => $request->matkul4,
          'sks4' => $request->sks4,
          'kodeMK5' => $request->kodeMK5,
          'matkul5' => $request->matkul5,
          'sks5' => $request->sks5,
          'kodeMK6' => $request->kodeMK6,
          'matkul6' => $request->matkul6,
          'sks6' => $request->sks6,
          'kodeMK7' => $request->kodeMK7,
          'matkul7' => $request->matkul7,
          'sks7' => $request->sks7,
          'kodeMK8' => $request->kodeMK8,
          'matkul8' => $request->matkul8,
          'sks8' => $request->sks8,
          'formatsurat_id' => $request->formatsurat_id,
          'dataSurat' => $request->dataSurat
        ];
      }
      else if($request->jenis_surat == "19"){
        $obj = [
          'semester' => $request->semester,
          'thnAkademik' => $request->thnAkademik,
          'nama' => $request->nama,
          'prodi' => $request->prodi,
          'npm' => $request->npm,
          'namaWakil' => $request->namaWakil,
          'prodiWakil' => $request->prodiWakil,
          'npmWakil' => $request->npmWakil,
          'dosenWali' => $request->dosenWali,
          'alasan' => $request->alasan,
          'kodeMK1' => $request->kodeMK1,
          'matkul1' => $request->matkul1,
          'sks1' => $request->sks1,
          'kodeMK2' => $request->kodeMK2,
          'matkul2' => $request->matkul2,
          'sks2' => $request->sks2,
          'kodeMK3' => $request->kodeMK3,
          'matkul3' => $request->matkul3,
          'sks3' => $request->sks3,
          'kodeMK4' => $request->kodeMK4,
          'matkul4' => $request->matkul4,
          'sks4' => $request->sks4,
          'kodeMK5' => $request->kodeMK5,
          'matkul5' => $request->matkul5,
          'sks5' => $request->sks5,
          'kodeMK6' => $request->kodeMK6,
          'matkul6' => $request->matkul6,
          'sks6' => $request->sks6,
          'kodeMK7' => $request->kodeMK7,
          'matkul7' => $request->matkul7,
          'sks7' => $request->sks7,
          'kodeMK8' => $request->kodeMK8,
          'matkul8' => $request->matkul8,
          'sks8' => $request->sks8,
          'kodeMK9' => $request->kodeMK9,
          'matkul9' => $request->matkul9,
          'sks9' => $request->sks9,
          'formatsurat_id' => $request->formatsurat_id,
          'dataSurat' => $request->dataSurat
        ];
      }
      else if($request->jenis_surat == "20"){
        $obj = [
          'semester' => $request->semester,
          'thnAkademik' => $request->thnAkademik,
          'nama' => $request->nama,
          'prodi' => $request->prodi,
          'npm' => $request->npm,
          'namaWakil' => $request->namaWakil,
          'prodiWakil' => $request->prodiWakil,
          'npmWakil' => $request->npmWakil,
          'dosenWali' => $request->dosenWali,
          'alasan' => $request->alasan,
          'kodeMK1' => $request->kodeMK1,
          'matkul1' => $request->matkul1,
          'sks1' => $request->sks1,
          'kodeMK2' => $request->kodeMK2,
          'matkul2' => $request->matkul2,
          'sks2' => $request->sks2,
          'kodeMK3' => $request->kodeMK3,
          'matkul3' => $request->matkul3,
          'sks3' => $request->sks3,
          'kodeMK4' => $request->kodeMK4,
          'matkul4' => $request->matkul4,
          'sks4' => $request->sks4,
          'kodeMK5' => $request->kodeMK5,
          'matkul5' => $request->matkul5,
          'sks5' => $request->sks5,
          'kodeMK6' => $request->kodeMK6,
          'matkul6' => $request->matkul6,
          'sks6' => $request->sks6,
          'kodeMK7' => $request->kodeMK7,
          'matkul7' => $request->matkul7,
          'sks7' => $request->sks7,
          'kodeMK8' => $request->kodeMK8,
          'matkul8' => $request->matkul8,
          'sks8' => $request->sks8,
          'kodeMK9' => $request->kodeMK9,
          'matkul9' => $request->matkul9,
          'sks9' => $request->sks9,
          'kodeMK10' => $request->kodeMK10,
          'matkul10' => $request->matkul10,
          'sks10' => $request->sks10,
          'formatsurat_id' => $request->formatsurat_id,
          'dataSurat' => $request->dataSurat
        ];
      }
      if($obj == ""){
        dd("Uncaught exception");
      }
      return json_encode($obj);
    }

    /**
    * Untuk menampilkan data yang telah diisikan pada formulir
    */
    public function tampilkanPreview(Request $request){
      $loggedInUser = Auth::user();
      // dd($loggedInUser);
      $realUser = $this->getRealUser($loggedInUser);
      $foto = $realUser->foto_mahasiswa;
      if($request->jenis_surat == "1"){
        // dd($request);
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        $penyediabeasiswa = $request->penyediabeasiswa;
        $formatsurat_id = $request->jenis_surat;
        // dd($penyediabeasiswa);
        $dataSurat = $this->buatJSON($request);
        // dd($dataSurat);
        return view('mahasiswa.preview_keterangan_beasiswa', [
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'penyediabeasiswa' => $penyediabeasiswa,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "2"){
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $kota_lahir = $request->kota_lahir;
        $tglLahir = $request->tglLahir;
        $semester = $request->semester;
        $alamat = $request->alamat;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        // dd($dataSurat);
        return view('mahasiswa.preview_keterangan_mahasiswa_aktif', [
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'kota_lahir' => $kota_lahir,
            'tglLahir' => $tglLahir,
            'alamat' => $alamat,
            'semester' => $semester,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "3"){
        $nama = $request->nama;
        $tglLahir = $request->tglLahir;
        $kewarganegaraan = $request->kewarganegaraan;
        $organisasiTujuan = $request->organisasiTujuan;
        $thnAkademik = $request->thnAkademik;
        $negaraTujuan = $request->negaraTujuan;
        $tanggalKunjungan = date_create($request->tanggalKunjungan)->format("j F Y");
        $formatsurat_id = $request->jenis_surat;
        $npm = $request->npm;
        // dd($request->angkatan);
        $dataSurat = $this->buatJSON($request);
        // dd($dataSurat);
        return view('mahasiswa.preview_pembuatan_visa', [
            'nama' => $nama,
            'tglLahir' => $tglLahir,
            'kewarganegaraan' => $kewarganegaraan,
            'organisasiTujuan' => $organisasiTujuan,
            'thnAkademik' => $thnAkademik,
            'negaraTujuan' => $negaraTujuan,
            'tanggalKunjungan' => $tanggalKunjungan,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "4"){
        $nama = $request->nama;
        $npm = $request->npm;
        $prodi = $request->prodi;
        $matkul = $request->matkul;
        $topik = $request->topik;
        $organisasi = $request->organisasi;
        $alamatOrganisasi = $request->alamatOrganisasi;
        $keperluanKunjungan = $request->keperluanKunjungan;
        $kota = $request->kota;
        $kepada = $request->kepada;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        // dd($request);
        return view('mahasiswa.preview_izin_studi_lapangan_1org', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'matkul' => $matkul,
            'topik' => $topik,
            'organisasi' => $organisasi,
            'alamatOrganisasi' => $alamatOrganisasi,
            'keperluanKunjungan' => $keperluanKunjungan,
            'kota' => $kota,
            'kepada' => $kepada,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "5"){
        $nama = $request->nama;
        $npm = $request->npm;
        $prodi = $request->prodi;
        $matkul = $request->matkul;
        $topik = $request->topik;
        $organisasi = $request->organisasi;
        $alamatOrganisasi = $request->alamatOrganisasi;
        $keperluanKunjungan = $request->keperluanKunjungan;
        $kota = $request->kota;
        $kepada = $request->kepada;
        $namaAnggota = $request->namaAnggota;
        $npmAnggota = $request->npmAnggota;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_izin_studi_lapangan_2org', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'matkul' => $matkul,
            'topik' => $topik,
            'organisasi' => $organisasi,
            'alamatOrganisasi' => $alamatOrganisasi,
            'keperluanKunjungan' => $keperluanKunjungan,
            'kota' => $kota,
            'kepada' => $kepada,
            'namaAnggota' => $namaAnggota,
            'npmAnggota' => $npmAnggota,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "6"){
        $nama = $request->nama;
        $npm = $request->npm;
        $prodi = $request->prodi;
        $matkul = $request->matkul;
        $topik = $request->topik;
        $organisasi = $request->organisasi;
        $alamatOrganisasi = $request->alamatOrganisasi;
        $keperluanKunjungan = $request->keperluanKunjungan;
        $kota = $request->kota;
        $kepada = $request->kepada;
        $namaAnggota1 = $request->namaAnggota1;
        $npmAnggota1 = $request->npmAnggota1;
        $namaAnggota2 = $request->namaAnggota2;
        $npmAnggota2 = $request->npmAnggota2;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_izin_studi_lapangan_3org', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'matkul' => $matkul,
            'topik' => $topik,
            'organisasi' => $organisasi,
            'alamatOrganisasi' => $alamatOrganisasi,
            'keperluanKunjungan' => $keperluanKunjungan,
            'kota' => $kota,
            'kepada' => $kepada,
            'namaAnggota1' => $namaAnggota1,
            'npmAnggota1' => $npmAnggota1,
            'namaAnggota2' => $namaAnggota2,
            'npmAnggota2' => $npmAnggota2,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "7"){
        $nama = $request->nama;
        $npm = $request->npm;
        $prodi = $request->prodi;
        $matkul = $request->matkul;
        $topik = $request->topik;
        $organisasi = $request->organisasi;
        $alamatOrganisasi = $request->alamatOrganisasi;
        $keperluanKunjungan = $request->keperluanKunjungan;
        $kota = $request->kota;
        $kepada = $request->kepada;
        $namaAnggota1 = $request->namaAnggota1;
        $npmAnggota1 = $request->npmAnggota1;
        $namaAnggota2 = $request->namaAnggota2;
        $npmAnggota2 = $request->npmAnggota2;
        $namaAnggota3 = $request->namaAnggota3;
        $npmAnggota3 = $request->npmAnggota3;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_izin_studi_lapangan_4org', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'matkul' => $matkul,
            'topik' => $topik,
            'organisasi' => $organisasi,
            'alamatOrganisasi' => $alamatOrganisasi,
            'keperluanKunjungan' => $keperluanKunjungan,
            'kota' => $kota,
            'kepada' => $kepada,
            'namaAnggota1' => $namaAnggota1,
            'npmAnggota1' => $npmAnggota1,
            'namaAnggota2' => $namaAnggota2,
            'npmAnggota2' => $npmAnggota2,
            'namaAnggota3' => $namaAnggota3,
            'npmAnggota3' => $npmAnggota3,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "8"){
        $nama = $request->nama;
        $npm = $request->npm;
        $prodi = $request->prodi;
        $matkul = $request->matkul;
        $topik = $request->topik;
        $organisasi = $request->organisasi;
        $alamatOrganisasi = $request->alamatOrganisasi;
        $keperluanKunjungan = $request->keperluanKunjungan;
        $kota = $request->kota;
        $kepada = $request->kepada;
        $namaAnggota1 = $request->namaAnggota1;
        $npmAnggota1 = $request->npmAnggota1;
        $namaAnggota2 = $request->namaAnggota2;
        $npmAnggota2 = $request->npmAnggota2;
        $namaAnggota3 = $request->namaAnggota3;
        $npmAnggota3 = $request->npmAnggota3;
        $namaAnggota4 = $request->namaAnggota4;
        $npmAnggota4 = $request->npmAnggota4;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_izin_studi_lapangan_5org', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'matkul' => $matkul,
            'topik' => $topik,
            'organisasi' => $organisasi,
            'alamatOrganisasi' => $alamatOrganisasi,
            'keperluanKunjungan' => $keperluanKunjungan,
            'kota' => $kota,
            'kepada' => $kepada,
            'namaAnggota1' => $namaAnggota1,
            'npmAnggota1' => $npmAnggota1,
            'namaAnggota2' => $namaAnggota2,
            'npmAnggota2' => $npmAnggota2,
            'namaAnggota3' => $namaAnggota3,
            'npmAnggota3' => $npmAnggota3,
            'namaAnggota4' => $namaAnggota4,
            'npmAnggota4' => $npmAnggota4,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "9"){
        $nama = $request->nama;
        $npm = $request->npm;
        $prodi = $request->prodi;
        $fakultas = $request->fakultas;
        $alamat = $request->alamat;
        $cutiStudiKe = $request->cutiStudiKe;
        $alasanCutiStudi = $request->alasanCutiStudi;
        $dosenWali = $request->dosenWali;
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        
        $persetujuanDosenWali = '-';
        $catatanDosenWali = '-';
        $persetujuanKaprodi = '-';
        $catatanKaprodi = '-';
        $persetujuanWDII = '-';
        $catatanWDII = '-';
        $persetujuanWDI = '-';
        $catatanWDI = '-';
        $persetujuanDekan = '-';
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        // dd($dataSurat);
        return view('mahasiswa.preview_izin_cuti_studi', [
            'nama' => $nama,
            'npm' => $npm,
            'prodi' => $prodi,
            'fakultas' => $fakultas,
            'alamat' => $alamat,
            'cutiStudiKe' => $cutiStudiKe,
            'alasanCutiStudi' => $alasanCutiStudi,
            'dosenWali' => $dosenWali,
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'persetujuanDosenWali' => $persetujuanDosenWali,
            'catatanDosenWali' => $catatanDosenWali,
            'persetujuanKaprodi' => $persetujuanKaprodi,
            'catatanKaprodi' => $catatanKaprodi,
            'persetujuanWDII' => $persetujuanWDII,
            'catatanWDII' => $catatanWDII,
            'persetujuanWDI' => $persetujuanWDI,
            'catatanWDI' => $catatanWDI,
            'persetujuanDekan' => $persetujuanDekan,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "10"){
        $nirm = $request->nirm;
        $nama = $request->nama;
        $npm = $request->npm;
        $alamat = $request->alamat;
        $noTelepon = $request->noTelepon;
        $namaOrtu = $request->namaOrtu;
        $dosenWali = $request->dosenWali;
        $semester = $request->semester;
        
        $persetujuanDosenWali = '-';
        $catatanDosenWali = '-';
        $persetujuanKaprodi = '-';
        $catatanKaprodi = '-';
        $persetujuanWDII = '-';
        $catatanWDII = '-';
        $persetujuanWDI = '-';
        $catatanWDI = '-';
        $persetujuanDekan = '-';
        $catatanDekan = '-';
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        $prodi = $request->prodi;
        // dd($dataSurat);
        return view('mahasiswa.preview_izin_pengunduran_diri', [
            'nirm' => $nirm,
            'nama' => $nama,
            'npm' => $npm,
            'alamat' => $alamat,
            'noTelepon' => $noTelepon,
            'namaOrtu' => $namaOrtu,
            'dosenWali' => $dosenWali,
            'semester' => $semester,
            'persetujuanDosenWali' => $persetujuanDosenWali,
            'catatanDosenWali' => $catatanDosenWali,
            'persetujuanKaprodi' => $persetujuanKaprodi,
            'catatanKaprodi' => $catatanKaprodi,
            'persetujuanWDII' => $persetujuanWDII,
            'catatanWDII' => $catatanWDII,
            'persetujuanWDI' => $persetujuanWDI,
            'catatanWDI' => $catatanWDI,
            'persetujuanDekan' => $persetujuanDekan,
            'catatanDekan' => $catatanDekan,
            'prodi' => $prodi,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "11"){
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $namaWakil = $request->namaWakil;
        $prodiWakil = $request->prodiWakil;
        $npmWakil = $request->npmWakil;
        $dosenWali = $request->dosenWali;
        $alasan = $request->alasan;
        $kodeMK = $request->kodeMK;
        $matkul = $request->matkul;
        $sks = $request->sks;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_perwakilan_perwalian_1matkul', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK' =>$kodeMK,
            'matkul' => $matkul,
            'sks' => $sks,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "12"){
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $namaWakil = $request->namaWakil;
        $prodiWakil = $request->prodiWakil;
        $npmWakil = $request->npmWakil;
        $dosenWali = $request->dosenWali;
        $alasan = $request->alasan;
        $kodeMK1 = $request->kodeMK1;
        $matkul1 = $request->matkul1;
        $sks1 = $request->sks1;
        $kodeMK2 = $request->kodeMK2;
        $matkul2 = $request->matkul2;
        $sks2 = $request->sks2;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_perwakilan_perwalian_2matkul', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "13"){
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $namaWakil = $request->namaWakil;
        $prodiWakil = $request->prodiWakil;
        $npmWakil = $request->npmWakil;
        $dosenWali = $request->dosenWali;
        $alasan = $request->alasan;
        $kodeMK1 = $request->kodeMK1;
        $matkul1 = $request->matkul1;
        $sks1 = $request->sks1;
        $kodeMK2 = $request->kodeMK2;
        $matkul2 = $request->matkul2;
        $sks2 = $request->sks2;
        $kodeMK3 = $request->kodeMK3;
        $matkul3 = $request->matkul3;
        $sks3 = $request->sks3;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_perwakilan_perwalian_3matkul', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "14"){
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $namaWakil = $request->namaWakil;
        $prodiWakil = $request->prodiWakil;
        $npmWakil = $request->npmWakil;
        $dosenWali = $request->dosenWali;
        $alasan = $request->alasan;
        $kodeMK1 = $request->kodeMK1;
        $matkul1 = $request->matkul1;
        $sks1 = $request->sks1;
        $kodeMK2 = $request->kodeMK2;
        $matkul2 = $request->matkul2;
        $sks2 = $request->sks2;
        $kodeMK3 = $request->kodeMK3;
        $matkul3 = $request->matkul3;
        $sks3 = $request->sks3;
        $kodeMK4 = $request->kodeMK4;
        $matkul4 = $request->matkul4;
        $sks4 = $request->sks4;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_perwakilan_perwalian_4matkul', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "15"){
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $namaWakil = $request->namaWakil;
        $prodiWakil = $request->prodiWakil;
        $npmWakil = $request->npmWakil;
        $dosenWali = $request->dosenWali;
        $alasan = $request->alasan;
        $kodeMK1 = $request->kodeMK1;
        $matkul1 = $request->matkul1;
        $sks1 = $request->sks1;
        $kodeMK2 = $request->kodeMK2;
        $matkul2 = $request->matkul2;
        $sks2 = $request->sks2;
        $kodeMK3 = $request->kodeMK3;
        $matkul3 = $request->matkul3;
        $sks3 = $request->sks3;
        $kodeMK4 = $request->kodeMK4;
        $matkul4 = $request->matkul4;
        $sks4 = $request->sks4;
        $kodeMK5 = $request->kodeMK5;
        $matkul5 = $request->matkul5;
        $sks5 = $request->sks5;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_perwakilan_perwalian_5matkul', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "16"){
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $namaWakil = $request->namaWakil;
        $prodiWakil = $request->prodiWakil;
        $npmWakil = $request->npmWakil;
        $dosenWali = $request->dosenWali;
        $alasan = $request->alasan;
        $kodeMK1 = $request->kodeMK1;
        $matkul1 = $request->matkul1;
        $sks1 = $request->sks1;
        $kodeMK2 = $request->kodeMK2;
        $matkul2 = $request->matkul2;
        $sks2 = $request->sks2;
        $kodeMK3 = $request->kodeMK3;
        $matkul3 = $request->matkul3;
        $sks3 = $request->sks3;
        $kodeMK4 = $request->kodeMK4;
        $matkul4 = $request->matkul4;
        $sks4 = $request->sks4;
        $kodeMK5 = $request->kodeMK5;
        $matkul5 = $request->matkul5;
        $sks5 = $request->sks5;
        $kodeMK6 = $request->kodeMK6;
        $matkul6 = $request->matkul6;
        $sks6 = $request->sks6;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_perwakilan_perwalian_6matkul', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'kodeMK6' => $kodeMK6,
            'matkul6' => $matkul6,
            'sks6' => $sks6,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "17"){
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $namaWakil = $request->namaWakil;
        $prodiWakil = $request->prodiWakil;
        $npmWakil = $request->npmWakil;
        $dosenWali = $request->dosenWali;
        $alasan = $request->alasan;
        $kodeMK1 = $request->kodeMK1;
        $matkul1 = $request->matkul1;
        $sks1 = $request->sks1;
        $kodeMK2 = $request->kodeMK2;
        $matkul2 = $request->matkul2;
        $sks2 = $request->sks2;
        $kodeMK3 = $request->kodeMK3;
        $matkul3 = $request->matkul3;
        $sks3 = $request->sks3;
        $kodeMK4 = $request->kodeMK4;
        $matkul4 = $request->matkul4;
        $sks4 = $request->sks4;
        $kodeMK5 = $request->kodeMK5;
        $matkul5 = $request->matkul5;
        $sks5 = $request->sks5;
        $kodeMK6 = $request->kodeMK6;
        $matkul6 = $request->matkul6;
        $sks6 = $request->sks6;
        $kodeMK7 = $request->kodeMK7;
        $matkul7 = $request->matkul7;
        $sks7 = $request->sks7;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_perwakilan_perwalian_7matkul', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'kodeMK6' => $kodeMK6,
            'matkul6' => $matkul6,
            'sks6' => $sks6,
            'kodeMK7' => $kodeMK7,
            'matkul7' => $matkul7,
            'sks7' => $sks7,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "18"){
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $namaWakil = $request->namaWakil;
        $prodiWakil = $request->prodiWakil;
        $npmWakil = $request->npmWakil;
        $dosenWali = $request->dosenWali;
        $alasan = $request->alasan;
        $kodeMK1 = $request->kodeMK1;
        $matkul1 = $request->matkul1;
        $sks1 = $request->sks1;
        $kodeMK2 = $request->kodeMK2;
        $matkul2 = $request->matkul2;
        $sks2 = $request->sks2;
        $kodeMK3 = $request->kodeMK3;
        $matkul3 = $request->matkul3;
        $sks3 = $request->sks3;
        $kodeMK4 = $request->kodeMK4;
        $matkul4 = $request->matkul4;
        $sks4 = $request->sks4;
        $kodeMK5 = $request->kodeMK5;
        $matkul5 = $request->matkul5;
        $sks5 = $request->sks5;
        $kodeMK6 = $request->kodeMK6;
        $matkul6 = $request->matkul6;
        $sks6 = $request->sks6;
        $kodeMK7 = $request->kodeMK7;
        $matkul7 = $request->matkul7;
        $sks7 = $request->sks7;
        $kodeMK8 = $request->kodeMK8;
        $matkul8 = $request->matkul8;
        $sks8 = $request->sks8;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_perwakilan_perwalian_8matkul', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'kodeMK6' => $kodeMK6,
            'matkul6' => $matkul6,
            'sks6' => $sks6,
            'kodeMK7' => $kodeMK7,
            'matkul7' => $matkul7,
            'sks7' => $sks7,
            'kodeMK8' => $kodeMK8,
            'matkul8' => $matkul8,
            'sks8' => $sks8,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "19"){
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $namaWakil = $request->namaWakil;
        $prodiWakil = $request->prodiWakil;
        $npmWakil = $request->npmWakil;
        $dosenWali = $request->dosenWali;
        $alasan = $request->alasan;
        $matkul1 = $request->matkul1;
        $kodeMK1 = $request->kodeMK1;
        $matkul1 = $request->matkul1;
        $sks1 = $request->sks1;
        $kodeMK2 = $request->kodeMK2;
        $matkul2 = $request->matkul2;
        $sks2 = $request->sks2;
        $kodeMK3 = $request->kodeMK3;
        $matkul3 = $request->matkul3;
        $sks3 = $request->sks3;
        $kodeMK4 = $request->kodeMK4;
        $matkul4 = $request->matkul4;
        $sks4 = $request->sks4;
        $kodeMK5 = $request->kodeMK5;
        $matkul5 = $request->matkul5;
        $sks5 = $request->sks5;
        $kodeMK6 = $request->kodeMK6;
        $matkul6 = $request->matkul6;
        $sks6 = $request->sks6;
        $kodeMK7 = $request->kodeMK7;
        $matkul7 = $request->matkul7;
        $sks7 = $request->sks7;
        $kodeMK8 = $request->kodeMK8;
        $matkul8 = $request->matkul8;
        $sks8 = $request->sks8;
        $kodeMK9 = $request->kodeMK9;
        $matkul9 = $request->matkul9;
        $sks9 = $request->sks9;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_perwakilan_perwalian_9matkul', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'kodeMK6' => $kodeMK6,
            'matkul6' => $matkul6,
            'sks6' => $sks6,
            'kodeMK7' => $kodeMK7,
            'matkul7' => $matkul7,
            'sks7' => $sks7,
            'kodeMK8' => $kodeMK8,
            'matkul8' => $matkul8,
            'sks8' => $sks8,
            'kodeMK9' => $kodeMK9,
            'matkul9' => $matkul9,
            'sks9' => $sks9,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
      else if($request->jenis_surat == "20"){
        $semester = $request->semester;
        $thnAkademik = $request->thnAkademik;
        $nama = $request->nama;
        $prodi = $request->prodi;
        $npm = $request->npm;
        $namaWakil = $request->namaWakil;
        $prodiWakil = $request->prodiWakil;
        $npmWakil = $request->npmWakil;
        $dosenWali = $request->dosenWali;
        $alasan = $request->alasan;
        $kodeMK1 = $request->kodeMK1;
        $matkul1 = $request->matkul1;
        $sks1 = $request->sks1;
        $kodeMK2 = $request->kodeMK2;
        $matkul2 = $request->matkul2;
        $sks2 = $request->sks2;
        $kodeMK3 = $request->kodeMK3;
        $matkul3 = $request->matkul3;
        $sks3 = $request->sks3;
        $kodeMK4 = $request->kodeMK4;
        $matkul4 = $request->matkul4;
        $sks4 = $request->sks4;
        $kodeMK5 = $request->kodeMK5;
        $matkul5 = $request->matkul5;
        $sks5 = $request->sks5;
        $kodeMK6 = $request->kodeMK6;
        $matkul6 = $request->matkul6;
        $sks6 = $request->sks6;
        $kodeMK7 = $request->kodeMK7;
        $matkul7 = $request->matkul7;
        $sks7 = $request->sks7;
        $kodeMK8 = $request->kodeMK8;
        $matkul8 = $request->matkul8;
        $sks8 = $request->sks8;
        $kodeMK9 = $request->kodeMK9;
        $matkul9 = $request->matkul9;
        $sks9 = $request->sks9;
        $kodeMK10 = $request->kodeMK10;
        $matkul10 = $request->matkul10;
        $sks10 = $request->sks10;
        $formatsurat_id = $request->jenis_surat;
        $dataSurat = $this->buatJSON($request);
        return view('mahasiswa.preview_perwakilan_perwalian_10matkul', [
            'semester' => $semester,
            'thnAkademik' => $thnAkademik,
            'nama' => $nama,
            'prodi' => $prodi,
            'npm' => $npm,
            'namaWakil' => $namaWakil,
            'prodiWakil' => $prodiWakil,
            'npmWakil' => $npmWakil,
            'dosenWali' => $dosenWali,
            'alasan' => $alasan,
            'kodeMK1' => $kodeMK1,
            'matkul1' => $matkul1,
            'sks1' => $sks1,
            'kodeMK2' => $kodeMK2,
            'matkul2' => $matkul2,
            'sks2' => $sks2,
            'kodeMK3' => $kodeMK3,
            'matkul3' => $matkul3,
            'sks3' => $sks3,
            'kodeMK4' => $kodeMK4,
            'matkul4' => $matkul4,
            'sks4' => $sks4,
            'kodeMK5' => $kodeMK5,
            'matkul5' => $matkul5,
            'sks5' => $sks5,
            'kodeMK6' => $kodeMK6,
            'matkul6' => $matkul6,
            'sks6' => $sks6,
            'kodeMK7' => $kodeMK7,
            'matkul7' => $matkul7,
            'sks7' => $sks7,
            'kodeMK8' => $kodeMK8,
            'matkul8' => $matkul8,
            'sks8' => $sks8,
            'kodeMK9' => $kodeMK9,
            'matkul9' => $matkul9,
            'sks9' => $sks9,
            'kodeMK10' => $kodeMK10,
            'matkul10' => $matkul10,
            'sks10' => $sks10,
            'formatsurat_id' => $formatsurat_id,
            'dataSurat' => $dataSurat,
            'user' => $realUser
        ]);
      }
    }
}