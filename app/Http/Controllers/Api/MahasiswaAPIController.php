<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mahasiswa;
use App\Repositories\MahasiswaRepository;

class MahasiswaAPIController extends Controller{

    protected $mahasiswaRepo;

    public function __construct(MahasiswaRepository $mahasiswaRepo){
        $this->mahasiswaRepo = $mahasiswaRepo;
    }

    public function tampilkanFoto(Request $request){
        $mhs = $this->mahasiswaRepo->findMahasiswaById($request->id);

        $link = $mhs->foto_mahasiswa;
        $foto = file($link);
        return response()->json([
          'foto' => $foto
        ]);
    }
}