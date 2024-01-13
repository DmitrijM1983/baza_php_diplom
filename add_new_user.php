<?php
session_start();

$name = explode('.', $_FILES['image']['name']);
$name = $name[1];
$imageName = 'img/demo/avatars/avatar-' . uniqid() . '.' . $name;
$tmp = $_FILES['image']['tmp_name'];

if ($name === 'png' || $name === 'jpg') {
    move_uploaded_file($tmp, __DIR__ . '/' . $imageName);
}

$username = $_POST['username'];
$jobTitle = $_POST['job_title'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
if ($_POST['status'] === 'Онлайн') {
    $status = 'online';
}
if ($_POST['status'] === 'Отошел') {
    $status = 'moved away';
}
if ($_POST['status'] === 'Не беспокоить') {
    $status = 'do not disturb';
}
$image = $imageName;
$role = 'user';
$vk = $_POST['vk'];
$telegram = $_POST['telegram'];
$instagram = $_POST['instagram'];
$userArray =
    [
    'username' => $username,
    'job_title' => $jobTitle,
    'phone' => $phone,
    'address' => $address,
    'email' => $email,
    'password' => $password,
    'status' => $status,
    'image' => $image,
    'role' => $role,
    'vk' => $vk,
    'telegram' => $telegram,
    'instagram' => $instagram
    ];

$pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin;charset=utf8', 'root', '');
$sql = "INSERT INTO `diplom_baza`(`username`, `job_title`, `status`, `image`, `phone`, `address`, `email`, `password`, 
        `role`, `vk`, `telegram`, `instagram`) VALUES (:username, :job_title, :status, :image, :phone, :address, 
         :email, :password, :role, :vk, :telegram, :instagram)";
$statement = $pdo->prepare($sql);
$statement->execute($userArray);
header('Location:users.php');