<?php
session_start();
require_once 'functions.php';
$id = $_GET['id'];

$pdo = getConnection();
$sql = "SELECT image FROM diplom_baza WHERE id =:id";
$statement = $pdo->prepare($sql);
$statement->execute(['id' => $id]);
$result = $statement->fetch(PDO::FETCH_COLUMN);
unlink(__DIR__ . '/' . $result);

$sql = "DELETE FROM diplom_baza WHERE id =:id";
$statement = $pdo->prepare($sql);
$statement->execute(['id' => $id]);

if (isAdmin()) {
    header('Location:users.php');
} else {
    header('Location:page_register.php');
}

