<?php
session_start();
$_SESSION['new'] = 1;
$_SESSION['validation'] = [];

$id = $_SESSION['id'];
$email = $_POST['email'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];

$pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin;charset=utf8', 'root', '');
$sql = "SELECT id, email FROM diplom_baza";
$statement = $pdo->prepare($sql);
$statement->execute();
$users = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $user) {
    if ($user['id'] != $id && $user['email'] === $email) {
    $_SESSION['validation']['error'] = 'Данный email используется другим пользователем!';
    header('Location:security.php');
    exit();
    }
}

if ($password1 != $password2) {
    $_SESSION['validation']['error'] = 'Введенные пароли не совпадают!';
    header('Location:security.php');
    exit();
}

$password = password_hash($password1, PASSWORD_DEFAULT);

$sql = "UPDATE `diplom_baza` SET `email`=:email,`password`=:password WHERE id =:id";
$statement = $pdo->prepare($sql);
$statement->execute(['id'=>$id, 'email'=>$email, 'password'=>$password]);
header('Location:users.php');

