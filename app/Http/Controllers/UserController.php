<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $this->validate($request,[
            'email'=> 'required|unique:users|email',
            'password'=>'required|min:8',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $hashPassword = Hash::make($password);

        User::create([
            'email'=> $email,
            'password'=>$hashPassword
        ]);

        return response()->json(['message'=>'berhasil dibuat']);


    }

    public function login(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required|min:8'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email',$email)->first();
            if(!$user){
                return response()->json(['message'=>'email atau password yang anda masukkan salah']);
            }
        $checkPassword = Hash::check($password,$user->password);
            if(!$checkPassword){
                return response()->json(['message'=>'email atau password yang anda masukkan salah']);
            }
        $generateToken = bin2hex(random_bytes(40));
        $user->update([
            'token'=> $generateToken,

        ]);
        return response()->json($user);

        
    }
}
