<?php

namespace App\Http\Controllers;

use App\Notifications\AccountCreated;
use App\Services\AuthService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

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

    public function verificationAccount(Request $request, $token){
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = true;
        $user->verification_token = null;
        $user->save();
        $redirect = $request->get('redirect');
        return redirect()->to($redirect);
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
            'password'  => 'required|min:6|max:16|confirmed',
            'redirect'  => 'required|url'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['verification_token'] = md5(str_random(16));
        $user = User::create($data);
        $redirect = url('/api/verification-account/' . $user->verification_token . '?redirect=' . $request->input('redirect'));
        Notification::send($user, new AccountCreated($user, $redirect));
        return response()->json($user, 201);
    }

    public function clients(Request $request){
        return response()->json(['ok' => true]);
    }
}
