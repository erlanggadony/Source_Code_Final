<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Repositories\UserRepository;
use AuthenticateUsers;
use Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller{
  protected $usersRepo;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    // protected $redirectToMahasiswa = '/home_mahasiswa';
    // protected $redirectToPejabat = '/home_pejabat';
    // protected $redirectToTU = '/home_TU';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $usersRepo){
        // $this->middleware('guest', ['except' => 'logout']);
        $this->usersRepo = $usersRepo;
    }

    public function showLoginForm(Request $request){
      return view('mahasiswa.login');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('error_message',"Anda Telah Logout");
    }

    public function login(Request $request){
      // dd("login");
        $rules = array(
          'username' => 'required',  //memastikan username diisi
          'password' => 'required|min:6' //memastikan password diisi dan min 8 karakter
        );
        $validator = validator($request->all(), $rules);
        // dd($validator);
        if($validator->fails()){
          return redirect('/login')
          ->withErrors($validator)
          ->withInput([
            'username' => $request->username,
          ])->with('error_message',"Gagal Login");
        }
        else{
          $userData = array(
            'username' => $request->username,
            'password' => $request->password
          );

          /** Cek variabel $userData.
          *   Kalo $userData berisi data User yang valid(yang ada di table),
          *   maka boolean true kondisi 1, selainnya false kondisi 2
          */
          if(Auth::guard()->attempt($userData)){ // ref.A

            /**
            * Ambil user yang sudah terauthentikasi pada statement
            * Auth::guard()->attempt($userData) pada ref.A
            */
            $authUser = Auth::user();

            /**
            * Untuk setiap jabatan yang berbeda, kembalikan ke halaman yang spesifik
            */
            if($authUser->jabatan == User::JABATAN_DOS){
              // dd($authUser->jabatan);
              return redirect('/home_pejabat');
            }else if($authUser->jabatan == User::JABATAN_TU){
              // dd($authUser->jabatan+" HAPUS DieDump INI !: LoginController.login :: line 97");
              return redirect('/home_TU');
            }else{ // $authUser->jabatan == User::JABATAN_MHS
              // dd($authUser->jabatan);
              return redirect('/home_mahasiswa');
            }

          }
          else{
            // dd("GAGILS AUTH GUARD");
            return redirect('/login')->withInput([
              'username' => $request->username,
            ])->with('error_message','Login Gagal');
          }
        }
    }

    // protected function validator(array $data){
    //     return Validator::make($data, [
    //         'name' => 'required',
    //         'username' => 'required'
    //         'email' => 'required',
    //         'password' => 'required',
    //         'jabatan' => 'required',
    //     ]);
    // }
}
