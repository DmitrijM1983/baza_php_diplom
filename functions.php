<?php

session_start();

function checkEmail(string $email): bool
{
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * @param string $email
 * @return array|bool
 */
function checkUserByEmail(string $email): array|bool
{
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin;charset=utf8','root','');
    $sql = "SELECT * FROM diplom_baza WHERE email= :email";
    $statement = $pdo->prepare($sql);
    $statement->execute(['email'=>$email]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * @param string $key
 * @param string $massage
 * @return void
 */
function setMassage(string $key, string $massage): void
{
    global $result;
    if ($result) {
        $_SESSION['validation'][$key] = $massage;
    }
}

/**
 * @param string $page
 * @return void
 */
function getLocation(string $page): void
{
    global $result;
    if ($result) {
        header('Location: page_' . $page . '.php');
        exit();
    }
}

/**
 * @param string $email
 * @param string $password
 * @return bool
 */
function register(string $email, string $password): bool
{
    $role = 'user';
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin;charset=utf8','root','');
    $sql = "INSERT INTO `diplom_baza`(`email`, `password`, `role`) VALUES (:email, :password, :role)";
    $statement = $pdo->prepare($sql);
    return $statement->execute(['email'=>$email, 'password'=>$password, 'role'=>$role]);
}


/**
 * @param string $email
 * @return array|bool
 */
function login(string $email): array|bool
{
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=marlin;charset=utf8','root','');
    $sql = "SELECT * FROM diplom_baza WHERE email= :email";
    $statement = $pdo->prepare($sql);
    $statement->execute(['email'=>$email]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * @param array|bool $result
 * @param string $password
 * @return void
 */
function setLoginMassage(array|bool $result, string $password): void
{
    if ($result === false) {
        $_SESSION['validation']['danger'] = 'Пользователь с данной эл. почтой не найден!';
        header('Location:page_login.php');
        exit();
    }
    if (!password_verify($password, $result['password'])) {
        $_SESSION['validation']['danger'] = 'Введен не верный пароль!';
        header('Location:page_login.php');
        exit();
    }
}

/**
 * @param array $result
 * @return void
 */
function getPage(array $result): void
{
    if ($result) {
        $_SESSION['user'] =
            [
                'id' => $result['id'],
                'email' => $result['email'],
                'role' => $result['role']
            ];
        header('Location:users.php');
    }
}

/**
 * @return array|bool
 */
function getUsers(): array|bool
{
    $pdo = new PDO('mysql:host=127.0.0.1; dbname=marlin; charset=utf8', 'root', '');
    $sql = "SELECT * FROM diplom_baza";
    $statemant = $pdo->prepare($sql);
    $statemant->execute();
    return $statemant->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param $user
 * @return bool
 */
function isEquival($user): bool
{
    return $user['email'] ===  $_SESSION['user']['email'];
}

/**
 * @return bool
 */
function isAdmin(): bool
{
    if ($_SESSION['user']['role'] === 'admin') {
        return true;
    } else {
        return false;
    }
}
