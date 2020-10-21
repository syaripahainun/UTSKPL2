<?php
 

namespace App\Http\Controllers;

use App\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\Factory as Auth;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function login(Request $request)
    {
        $email = $request->input("email");
        $password = $request->input("password");
        if ($users = Users::where("email", $email)->first()) {
            if (Hash::check($password, $users->password)) {
                $apiToken = base64_encode(Str::random(32));
     
                $users->update([
                    'api_token' => $apiToken
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Login Success!',
                    'data' => [
                        'user' => $users,
                        'api_token'=> $apiToken,
                    ],
                ], 201)
                ->header('Access-Control-Allow-Origin', '*');
            } else {
                return response()->json([
                        'success' => false,
                        'message' => 'Check Your Password!',
                        'data' => ['']
                    ], 201)
                ->header('Access-Control-Allow-Origin', '*');
            }
        } else {
            return response()->json([
                    'success' => false,
                    'message' => 'Check Your Email or Password!',
                    'data' => ['']
                ], 201)
            ->header('Access-Control-Allow-Origin', '*');
        }
    }

    /**
     * Short description here.
     *
     * @return void
     */
    public function logout(Request $request)
    {
        $users = Users::where('api_token')->first();
     
        if ($users) {
            $users-> api_token = null;
            $users->save();
        }

        return response()->json(['data' => 'User logged out.'], 200)
                         ->header('Access-Control-Allow-Origin', '*');
    }
    
    public function admin(Request $request)
    {
        return response('hai admin');
    }
}
