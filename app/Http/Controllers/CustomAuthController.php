<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }
    public function register(){
        return view('auth.register');
    }

    public function custom_login(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);
        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials,$request->get('remember'))){
            return redirect()->intended('home')->withSuccess('Success');
        } else {
            echo "not in database";
        }
        return redirect('login')->withSuccess('login authentication failed');
    }

    public function registeration(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
        ]);
        $data = $request->all();
        $check = $this->create($data);

        return redirect("home")->withSuccess('You have signed-in');
    }

    private function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }

    public function logout(){
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }

}
