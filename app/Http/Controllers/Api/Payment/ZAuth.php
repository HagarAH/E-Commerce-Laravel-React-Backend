<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ZAuth extends Controller
{
    public function getToken(Request $request){
        $curl = curl_init();
        $clientId = env('PIS_SECRETKEY');
        $baseUrl = "https://api.ziraatbank.com.gr/SandBox/oauth2/authorize";
        $responseType = "code";
        $scope = "openid";
        $redirectUri = url('http://localhost:5173/');
        $url = $baseUrl . "?client_id=" . $clientId . "&response_type=" . $responseType . "&scope=" . $scope . "&redirect_uri=" . $redirectUri;
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.ziraatbank.com.gr/SandBox/oauth2/authorize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => ,
            CURLOPT_HTTPHEADER => [
                "accept: text/html",
                "content-type: application/x-www-form-urlencoded"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }}
