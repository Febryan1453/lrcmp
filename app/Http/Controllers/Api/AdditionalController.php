<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdditionalController extends Controller
{
    public function getDoa()
    {
        $response = Http::get("https://doa-doa-api-ahmadramadhan.fly.dev/api");
        $data_doa = json_decode($response);

        return view('additional.doa',[
            'data_doa'  => $data_doa
        ]);
    }

    public function regis()
    {
        return view('additional.regis');
    }

    public function login()
    {
        return view('additional.login');
    }

    public function prosesLogin(Request $request)
    {
        $respon = Http::post("https://warungbangkrut14.herokuapp.com/api/login?email={$request->email}&password={$request->password}");
        // $respon = Http::post("https://warungbangkrut14.herokuapp.com/api/login", $request->all());
        $data = json_decode($respon);

        if($data->status == 0){
            return redirect()->back()->with('error',$data->message);
        }else{
            
            return view('additional.welcome',[
                'data' => $data
            ]);

        }
    }

    public function prosesRegis(Request $request)
    {
        $respon = Http::post("https://warungbangkrut14.herokuapp.com/api/registrasi", $request->all());

        $data = json_decode($respon);

        if($data->status == 0){
            return redirect('/registrasi')->with('error',$data->message);
        }else{
            return redirect('/login')->with('ok',$data->pesan);
        }
    }
}
