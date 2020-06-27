<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function userAuth(Request $request){
        return $request->user();
    }

    public function refreshToken(){

        $user = AuthService::refreshToken(Auth::user());

        return response()->json([
            'api_token' => $user->api_token,
            'api_token_expiration' => $user->api_token_expiration
        ], 200);
    }

    /*
     * Login Stateless
     * */
    public function login(Request $request){

        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required|min:6|max:16'
        ]);

        $data = $request->all();
        $user = User::where('email', $data['email'])->first();

        if (!$user || !\Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 400);
        }

        $user = AuthService::refreshToken($user);

        return response()->json([
            'api_token' => $user->api_token,
            'api_token_expiration' => $user->api_token_expiration
        ], 200);

    }

    public function store(Request $request){

        $this->validate($request, [
            'name'      => 'required|max:255',
            'email'     => 'required|email|max:255|unique:users',
            'password'  => 'required|min:6|max:16|confirmed'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $model = User::create($data);
        return response()->json($model, 201);
    }

    public function clients(Request $request){
        return response()->json(['ok' => true]);
    }
}
