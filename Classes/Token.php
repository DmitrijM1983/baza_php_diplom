<?php

class Token
{
    /**
     * @return string
     */
    public static function generate(): string
    {
        $name = Config::get('session.token_name');
        $value =  md5(uniqid());
        return Session::put($name, $value);
    }

    /**
     * @param $token
     * @return bool
     */
    public static function check($token): bool
    {
        $tokenName = Config::get('session.token_name');

        if (Session::exists($tokenName) && $token == Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}