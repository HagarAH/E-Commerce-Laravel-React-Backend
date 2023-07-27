<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        $data=$request->validated();
        if(!Auth::attempt($data)){
           return response([
               'message'=> 'Provided email or password is incorrect'
           ]);
        }
        $user=Auth::user();
        $token = $user->createToken('main')->plainTextToken;


        $curl = curl_init();

        $clientId = env('PIS_SECRETKEY');
        $baseUrl = "https://api.ziraatbank.com.gr/SandBox/oauth2/authorize";
        $responseType = "code";
        $scope = "openid";
        $redirectUri = url('http://localhost:5173/getToken');
        $state = Str::random(40);

        // Store state in session or other secure storage
        session(['oauth_state' => $state]);

        $url = $baseUrl . "?client_id=" . $clientId . "&response_type=" . $responseType . "&scope=" . $scope . "&redirect_uri=" . $redirectUri . "&state=" . $state;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => ["accept: text/html"],
            CURLOPT_SSL_VERIFYPEER => false

        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response(['error' => "cURL Error #:" . $err], 500);
        } else {
            return response(compact( 'user','token','response'), 200);
        }

    }

    public function logout(Request $request){
        /** @var User $user */
        $user= $request->user();
        $user->currentAccessToken()->delete();
        return response('',204);

    }

    public function signup(SignupRequest $request){
        $data=$request->validated();
        $user= User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password']),
        ]);
        $token= $user->createToken('main')->plainTextToken;

        return response([
            'user'=>$user,
            'token'=>$token,
        ]);
    }
}
