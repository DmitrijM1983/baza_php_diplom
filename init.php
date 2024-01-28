<?php
session_start();

require_once 'Classes/DataBase.php';
require_once 'Classes/Config.php';
require_once 'Classes/Validate.php';
require_once 'Classes/Input.php';
require_once 'Classes/Token.php';
require_once 'Classes/Session.php';
require_once 'Classes/User.php';
require_once 'Classes/Redirect.php';
require_once 'Classes/Cookie.php';

$GLOBALS['config'] =
    [
        'mysql' =>
            [
                'host' => '127.0.0.1',
                'dbname' => 'marlin',
                'charset' => 'utf8',
                'user' => 'root',
                'password' => ' '
            ],
        'session' =>
            [
                'token_name' => 'token',
                'user_session' => 'user'
            ],
        'cookie' =>
            [
                'cookie_name' => 'hash',
                'cookie_expiry' => 604800
            ]
    ];

if (Cookie::exists(Config::get('cookie.cookie_name')) && !Session::exists(Config::get('session.user_session'))) {
    $hash = Cookie::get(Config::get('cookie.cookie_name'));
    $checkHash = DataBase::getConnect()->get('user_cookie', ['hash' => $hash]);
    if ($checkHash) {
        $user = new User($checkHash[0]->user_id);
        $user->login();
    }
}
