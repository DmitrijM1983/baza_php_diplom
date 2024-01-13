<?php
session_start();
require_once 'functions.php';
$email = $_POST['email'];
$password = $_POST['password'];

$_SESSION['validation'] = [];

$result = login($email);
setLoginMassage($result, $password);
getPage($result);


