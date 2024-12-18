<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiHandler;
use Illuminate\Http\Request;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use App\Models\User;
use Hash;
class AuthController extends Controller
{
    
    use ApiHandler;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $rules = [
            "email" => "required",

            "password" => "required",

        ];


        $validator = Validator::make($request->all(), $rules);


        if($validator->fails()) {

            $code = $this->returnCodeAccordingToInput($validator);

            return $this->returnValidationError( $validator, $code);
    
        }

        $credentials = $request->only(['email', 'password']);


        $token= Auth::guard('api')->attempt($credentials);

        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } else {


            $user = Auth::guard('api')->user();

            $user->user_token = $token;
    
            return response()->json([
    
                "user" => $user
            ]);
        }


      
    }



    public function register(Request $request) {


        $rules = [
            "email" => "required",

            "password" => "required",

        ];


        $validator = Validator::make($request->all(), $rules);


        if($validator->fails()) {

            $code = $this->returnCodeAccordingToInput($validator);

            return $this->returnValidationError($code, $validator);
    
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);



        if($user) {
            // $credentials = $request->only(['email', 'password']);


            // $token= Auth::guard('api')->attempt($credentials);

            // $user = Auth::guard('api')->user();

            // $user->user_token = $token;
            return $this->login($request);
             //$user;
        }

        return response()->json([
            "msg" => "smth went wrong"
        ]);



    }


    public function logout(Request $request) {


        try{

            JWTAuth::invalidate($request->token);
            return response()->json(["msg" => "loggedout"]);
        } catch(\Exception $e) {

            return \response()->json(["msg" => "smth went wrong"]);
        }
    }



    public function refresh(Request $request) {

        $new_token = JWTAuth::refresh($request->token);

        if($new_token) {

            return response()->json(["msg" => $new_token]);
        }

        return response()->json(["msg" => "error"]);
    }

}
