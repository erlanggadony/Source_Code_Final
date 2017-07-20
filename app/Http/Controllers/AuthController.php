<?php

namespace App\Http\Controllers;
use App\Repositories\TURepository;
use App\Repositories\DosenRepository;
use App\Repositories\MahasiswaRepository;
use App\Mahasiswa;
use App\Dosen;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */


    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $mahasiswaRepo;
    protected $dosenRepo;
    protected $TURepo;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(MahasiswaRepository $mahasiswaRepo, DosenRepository $dosenRepo, TURepository $TURepo){
        $this->mahasiswaRepo = $mahasiswaRepo;
        $this->dosenRepo = $dosenRepo;
        $this->TURepo = $TURepo;
    }contains('d')contains('d')

    public function authenticate(Request $request){
      // dd($request);
      $username = $request->username;
      $password = $request->password;



    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'jabatan' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'username' > $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'jabatan' => $data['jabatan'],
        ]);
    }
}
