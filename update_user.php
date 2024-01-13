<?php
session_start();
require_once 'functions.php';

$_SESSION['new'] = 1;

$id = $_SESSION['id'];
$username = $_POST['username'];
$jobTitle = $_POST['job_title'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$userArray =
    [
        'id' => $id,
        'username' => $username,
        'job_title' => $jobTitle,
        'phone' => $phone,
        'address' => $address
    ];

$pdo = getConnection();
$sql = "UPDATE `diplom_baza` SET `username`=:username, `job_title`=:job_title, `phone`=:phone, `address`=:address 
        WHERE id=:id";
$statement = $pdo->prepare($sql);
$statement->execute($userArray);

header('Location:users.php');