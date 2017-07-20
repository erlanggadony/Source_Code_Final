<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Repositories\UserRepository;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data){
        return Validator::make($data, [
            'name' => 'required|max:255',
            'username' => 'required',
            // 'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'jabatan' => 'required'
        ]);
    }

    public function register(Request $request){

      $validator = validator($request->all());
      // dd($validator);
      if($validator->fails()){
        return redirect('/login')
        ->withErrors($validator)
        ->withInput([
          'username' => $request->username,
          'name' => $request->name,
          'jabatan' => $request->jabatan,
        ]);
      }
      // dd("not fail");
      $savedUser = User::create(array(
        'name' => $request->name,
        'username' => $request->username,
        'jabatan' => $request->jabatan,
        // 'email' => Input::get('email'),
        'password' => bcrypt($request->password),
        'jabatan' => $request->jabatan
      ));
      // dd($savedUser);
      return redirect('login')->with('success_message','Registrasi Berhasil');
    }

}
