<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;

class AuthController extends Controller
{
    //
    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['err'=> $validator->messages(), 'result' => null]);
        }
        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['err' => 'We cant find an account with this credentials.', 'result' => null], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['err' => 'Failed to login, please try again.', 'result' => null], 500);
        }
        // all good so return the token
        return response()->json(['err' => null, 'result'=> [ 'token' => $token ]]);
    }

    public function add_user(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $rules = [
            'username' => 'required|unique:users',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        
        if($validator->fails()) {
            return response()->json(['err'=> $validator->messages(), 'result' => null]);
        } else {
            User::create(['username' => $request->username, 'email' => $request->username, 'password' => \Hash::make($request->password)]);

            return response()->json(['err' => null, 'result'=> [ 'message' => 'Berhasil menambah user.' ]]);
        }  
    }

    public function get_all_user() {

        $output = [
            'err' => null,
            'result' => array(
                'users' => User::all()
            )
        ];
        
        return response()->json($output);
    }

    public function delete_user(Request $request) {
        User::where('id', $request->id_user)->delete();
        return response()->json(['err' => null, 'result'=> [ 'message' => 'Berhasil mendelete user.' ]]);
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);
        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json(['err' => null, 'result'=> "You have successfully logged out."]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['err' => 'Failed to logout, please try again.', 'result' => null], 500);
        }
    }
}
