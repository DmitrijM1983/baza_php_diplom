<?php

require_once 'init.php';

$user = new User;
$user->find($_GET['id']);
$image = $user->getData()->image;

if ($image != null) {
    unlink($image);
}
DataBase::getConnect()->delete('users', ['id'=>$_GET['id']]);

if ($_SESSION['user'] === $_GET['id']) {
    Redirect::to('index.php');
    exit;
} else {
    Session::setFlash('success', 'User deleted successfully!');
    Redirect::to('users.php');
}
