<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FormatUserHelper;
use App\Helpers\MessageHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MentorApiController extends Controller
{
    public function mentor()
    {
        $mentor = User::where('role',2)
                    ->get()
                    ->map(function ($mentor){
                        return FormatUserHelper::formatResultAuth($mentor);                        
                    });

        $msg = "Berhasil mendapatkan mentor";
        return MessageHelper::resultAuth(true, $msg, $mentor);
    }

    public function addMentor(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:255','unique:users'],
            'email'     => ['required', 'string', 'email', 'max:255','unique:users'],
            'password'  => ['required', 'string', 'min:4'],
            'images'    => ['required'],
        ]);

        if($validasi->fails()){
            $valIndex = $validasi->errors()->all();
            return MessageHelper::error(false, $valIndex[0]);
        }

        $mentor = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'blokir'    => 1,
            'role'      => 2,
            'password'  => Hash::make($request->password),
            'images'    => $request->file('images')->store('img-mentor'),
        ]);

        $detail = FormatUserHelper::resultUser($mentor->id);

        $msg = "Registrasi mentor baru berhasil";
        return MessageHelper::resultAuth(true, $msg, $detail);
    }

    public function updateMentor(Request $request, $username)
    {
        $mentor = User::where('username', $username)->first();

        if(!$mentor){
            return MessageHelper::error(false, "Username $username tidak ditemukan");
        }

        if($mentor->role !== 2){
            return MessageHelper::error(false, "Username $username bukan mentor");
        }

        $validasi = Validator::make($request->all(), [
            'name'      => ['required'],
            'username'  => ['required'],
            'email'     => ['required', 'email',],
            // 'password'  => ['required', 'min:4'],
            // 'images'    => ['required'],
        ]);

        if($validasi->fails()){
            $valIndex = $validasi->errors()->all();
            return MessageHelper::error(false, $valIndex[0]);
        }

        $mentor->update([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            // 'password'  => Hash::make($request->password),
            // 'images'    => $request->file('images')->store('img-mentor'),
        ]);
        
        $userMentor = FormatUserHelper::resultUser($mentor->id);

        $msg = "Update mentor berhasil";
        return MessageHelper::resultAuth(true, $msg, $userMentor);

    }
}
