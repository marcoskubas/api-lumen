<?php

namespace App\Http\Controllers;

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

        $expiration = new \Carbon\Carbon();
        $expiration->addHour(2);
        $user->api_token = sha1(str_random(32)) . '.' . sha1(str_random(32));
        $user->api_token_expiration = $expiration->format('Y-m-d H:i:s');
        $user->save();

        return response()->json([
            'api_token' => $user->api_token,
            'api_token_expiration' => $user->api_token_expiration
        ], 200);

    }

    public function store(Request $request){

        //dd($request->user());
        //dd(Auth::user()); //Testes Recuperação Usuário Logado

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
}
