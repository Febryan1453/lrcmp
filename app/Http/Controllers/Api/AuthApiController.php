<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FormatUserHelper;
use App\Helpers\MessageHelper;
use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:255','unique:users'],
            'email'     => ['required', 'string', 'email', 'max:255','unique:users'],
            'password'  => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        if($validasi->fails()){
            $valIndex = $validasi->errors()->all();
            return MessageHelper::error(false, $valIndex[0]);
        }

        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'blokir'    => 1,
            'password'  => Hash::make($request->password),
        ]);

        Peserta::create([
            'user_id'   => $user->id
        ]);

        $detail = FormatUserHelper::resultUser($user->id);

        $msg = "Registrasi Berhasil";
        return MessageHelper::resultAuth(true, $msg, $detail);
    }

    public function login(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'email'     => ['required','email'],
            'password'  => ['required'],
        ]);

        if($validasi->fails()){
            $valIndex = $validasi->errors()->all();
            return MessageHelper::error(false, $valIndex[0]);
        }
        
        $user = User::where('email', $request->email)->first();

        if($user){

            if(password_verify($request->password, $user->password)){

                $detail = FormatUserHelper::resultUser($user->id);

                $msg = "Selamat Datang, $user->name";
                return MessageHelper::resultAuth(true, $msg, $detail);
            }
        }

        return MessageHelper::error(false, "Pastikan email & password sudah benar !");
    }
    
}
