<?php

session_start();
require_once 'functions.php';

$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$_SESSION['email'] = $email;
$_SESSION['validation'] = [];

$result = checkEmail($email);
setMassage('email_error','Введите корректную почту!');
getLocation('register');

$result = checkUserByEmail($email);
setMassage('email_error', 'Пользователь с данной почтой уже зарегистрирован!');
getLocation('register');

$result = register($email, $password);
setMassage('success', 'Вы успешно зарегистрировались! Введите email и пароль для входа!');
getLocation('login');

