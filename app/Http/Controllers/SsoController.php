<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SsoController extends Controller
{
    public function callback(Request $request)
    {

        $SSO_HOST = "https://sso.kolakakab.go.id";
        $SSO_CLIENT_ID = "9cfb66e9-f4a9-4527-a87b-884ee5103a83";
        $SSO_CLIENT_SECRET = "lt7YT5lxBwKOIzVBXeRVLfDSXL22ZkPMgzCpxTLE";

        $client = new \GuzzleHttp\Client(['verify' => false]);
        try {
            $response = $client->request('POST', $SSO_HOST . "/oauth/token", [
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'form_params' =>
                [
                    "grant_type" => "authorization_code",
                    "client_id" => $SSO_CLIENT_ID,
                    "client_secret" => $SSO_CLIENT_SECRET,
                    "redirect_uri" => "https://sdi-testing.pttati.co.id" . "/callback",
                    "code" => $request->code
                ]
            ]);
            $respon = json_decode($response->getBody()->getContents(), true);
            dd($respon);
        } catch (\Throwable $th) {
            return $th->getMessage() . '<br>' . $SSO_CLIENT_ID . '<hr>' . var_dump($request->all());
        }
        /*
 session : 
 1. token_type
 2. expires_in
 3. access_token
 4. refresh_token
 */

        $access_token = $respon['access_token'];
        try {
            $response = $client->request('GET', "https://sso.kolakakab.go.id" . "/api/user", [
                'headers' => [
                    'Accept' => 'application/json',
                    "Authorization" => "Bearer " . $access_token
                ],
                'form_params' => null
            ]);
            $userArray = json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        try {
            $username = $userArray['username'];
        } catch (\Throwable $th) {
            return redirect("login")->withErrors("Failed to get login information! try again!");
        }
        $user = User::where("username", $username)->first();
        if (!$user) {
            return 'username tidak ada';
        }
        Auth::login($user);

        // Redirect based on role
        if ($user->hasRole('administrator')) {
            return redirect()->route('d_administrator');
        } elseif ($user->hasRole('walidata') || $user->hasRole('pembina') || $user->hasRole('walidatapendukung')) {
            return redirect()->route('d_walidata');
        } elseif ($user->hasRole('produsen')) {
            return redirect()->route('d_produsen');
        }

        return redirect('/');
    }
}
