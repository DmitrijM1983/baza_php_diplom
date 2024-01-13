<?php
session_start();
$_SESSION['new'] = 1;

$id = $_SESSION['id'];
if ($_POST['status'] === 'Онлайн') {
    $status = 'online';
}
if ($_POST['status'] === 'Отошел') {
    $status = 'moved away';
}
if ($_POST['status'] === 'Не беспокоить') {
    $status = 'do not disturb';
}

$pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin;charset=utf8', 'root', '');
$sql = "UPDATE diplom_baza SET status =:status WHERE id =:id";
$statement = $pdo->prepare($sql);
$statement->execute(['id'=>$id, 'status'=>$status]);
header('Location:users.php');