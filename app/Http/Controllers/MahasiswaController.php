<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\MahasiswaRepository;
use Illuminate\Support\Facades\Auth;
use App\Mahasiswa;
use App\User;
use App\Dosen;
use App\TU;
class MahasiswaController extends Controller
{
    //
    protected $mahasiswaRepo;

    public function __construct(MahasiswaRepository $mahasiswaRepo){
      // dd($formatsuratRepo);
        $this->mahasiswaRepo = $mahasiswaRepo;
    }

    public function tampilkanFoto(Request $request){
      $mhs = $this->mahasiswaRepo->findMahasiswaById($request->mahasiswa_id);
      $link = $mhs->foto_mahasiswa;
      return redirect($link);
    }

    public function tambahDataMahasiswa(){
      $loggedInUser = Auth::user();
      // dd($loggedInUser);
      $realUser = $this->getRealUser($loggedInUser);
      return view('TU.tambah_data_mahasiswa', [
        'user' => $realUser
      ]);
    }

    public function kategoriSurat(){
      $loggedInUser = Auth::user();
      // dd($loggedInUser);
      $realUser = $this->getRealUser($loggedInUser);
      return view('mahasiswa.pilih_kategori_surat', [
        'user' => $realUser
      ]);
    }

    public function setting(){
      $loggedInUser = Auth::user();
      // dd($loggedInUser);
      $realUser = $this->getRealUser($loggedInUser);
      return view('tu.setting_semester_thnAjaran', [
        'user' => $realUser
      ]);
    }

    public function updateSemester(Request $request){
      $mahasiswas = $this->mahasiswaRepo->findAllMhs();
      foreach ($mahasiswas as $mhs) {
        $mhs->semester = $request->semester;
        $mhs->thnAkademik = $request->thnAkademik;
        $mhs->save();
      }
      return redirect('/data_mahasiswa');
    }

    public function tampilkanSeluruhSurat(Request $request){
      $loggedInUser = Auth::user();
      // dd($loggedInUser);
      $realUser = $this->getRealUser($loggedInUser);
      // dd($realUser);
      return view('mahasiswa.home_mahasiswa',[
        'user' => $realUser
      ]);
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

    public function pilihMahasiswa(Request $request){
        //$confirmation = Confirmation::where(['id' => 2])->first();

        //dd($confirmation->order->tickets);
        //dd($confirmation);
        // //--
        $mahasiswas;
        if($request->kategori_mahasiswa == "nirm"){
          $mahasiswas = $this->mahasiswaRepo->findMahasiswaByNIRM($request->searchBoxMhs);
        }
        else if($request->kategori_mahasiswa == "npm"){
          $mahasiswas = $this->mahasiswaRepo->findMahasiswaByNPM($request->searchBoxMhs);
        }
        else if($request->kategori_mahasiswa == "nama_mahasiswa"){
          $mahasiswas = $this->mahasiswaRepo->findMahasiswaByNama($request->searchBoxMhs);
        }
        else if($request->kategori_mahasiswa == "prodi"){
          $mahasiswas = $this->mahasiswaRepo->findMahasiswaByProdi($request->searchBoxMhs);
        }
        else if($request->kategori_mahasiswa == "angkatan"){
          $mahasiswas = $this->mahasiswaRepo->findMahasiswaByAngkatan($request->searchBoxMhs);
        }
        else{
          $mahasiswas = $this->mahasiswaRepo->findAllMahasiswa();
        }
        // dd($mahasiswas);
        $loggedInUser = Auth::user();
        $realUser = $this->getRealUser($loggedInUser);
        return view('TU.data_mahasiswa',[
        // dd($formatsurats);
            'mahasiswas' => $mahasiswas,
            'user' => $realUser
        ]);
  	}

    /**
    * Delete the selected data
    */
    public function destroy(Request $request){
        //
        // dd($request->deleteID);
        $mahasiswa = $this->getModel($request->deleteID);
        $mahasiswa->delete();
        return redirect('/data_mahasiswa')->with('success_message', 'Mahasiswa <b>#' . $request->id . '</b> berhasil dihapus.');;
    }

    /**
    * Get mahasiswa model by Id
    * @return Mahasiswa
    */
    private function getModel($id){
        $model = $this->mahasiswaRepo->findById($id);
        if($model === null){
            abort(404);
        }
        return $model;
    }

    public function uploadMahasiswa(Request $request){
      // dd($request);
      $dataMhs = $request->file('uploadDataMhs');
      $mhs = file($dataMhs);
      // dd($mhs);
      $baris = '';
      foreach ($mhs as $line_num => $line) {
        $baris .= $line;

        $data = explode(",", $baris);  //ubah pemisahnya dengan yg ada di sql
        // dd($data);

        $mahasiswa = new Mahasiswa;

        $mahasiswa->nirm = $data[0];
        $mahasiswa->npm = $data[1];
        $mahasiswa->nama_mahasiswa = $data[2];
        $mahasiswa->jurusan_id = $data[3];
        $mahasiswa->fakultas_id = $data[4];
        $mahasiswa->angkatan = $data[5];
        $mahasiswa->kota_lahir = $data[6];
        $mahasiswa->tanggal_lahir = $data[7];
        $mahasiswa->foto_mahasiswa = $data[8];
        $mahasiswa->dosen_id = $data[9];
        $mahasiswa->username = $data[10];
        $mahasiswa->save();

        $user = new User;
        $user->name = $data[2];
        $user->username = $data[10];
        $user->password = $data[11];
        $user->save();
      }
      return redirect('/data_mahasiswa')->with('success_message', 'Data mahasiswa telah di upload');
    }
}
