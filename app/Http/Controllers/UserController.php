<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

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

    public function store(Request $request){
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $model = User::create($data);
        return response()->json($model, 201);
    }
}
