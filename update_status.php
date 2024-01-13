<?php
session_start();
require_once 'functions.php';

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

$pdo = getConnection();
$sql = "UPDATE diplom_baza SET status =:status WHERE id =:id";
$statement = $pdo->prepare($sql);
$statement->execute(['id'=>$id, 'status'=>$status]);
header('Location:users.php');