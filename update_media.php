<?php
session_start();
$id = $_SESSION['id'];
$_SESSION['new'] = 1;

$pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin;charset=utf8', 'root', '');
$sql = "SELECT image FROM diplom_baza WHERE id =:id";
$statement = $pdo->prepare($sql);
$statement->execute(['id'=>$id]);
$image = $statement->fetch(PDO::FETCH_COLUMN);
$imageArray = explode('/', $image);
$image = implode('\\', $imageArray);
unlink(__DIR__ . '\\' . $image);

$name = explode('.', $_FILES['image']['name']);
$name = $name[1];
$image = 'img/demo/avatars/avatar-' . uniqid() . '.' . $name;
$tmp = $_FILES['image']['tmp_name'];

if ($name === 'png' || $name === 'jpg') {
    move_uploaded_file($tmp, __DIR__ . '/' . $image);
}
$sql = "UPDATE diplom_baza SET image =:image WHERE id =:id";
$statement = $pdo->prepare($sql);
$statement->execute(['id'=>$id, 'image'=>$image]);
header('Location:users.php');