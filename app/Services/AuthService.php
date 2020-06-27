<?php
namespace App\Services;

class AuthService{

    public static function refreshToken($user){
        $expiration = new \Carbon\Carbon();
        $expiration->addHour(2);
        $user->api_token = sha1(str_random(32)) . '.' . sha1(str_random(32));
        $user->api_token_expiration = $expiration->format('Y-m-d H:i:s');
        $user->save();
        return $user;
    }

}
