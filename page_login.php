<?php

require_once 'init.php';

$rules = ['email' => ['required' => true, 'email' => true,], 'password' => ['required' => true]];

$err = '';
if (Input::exists()) {
//    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, $rules);

        if ($validation->passed()) {
            $user = new User;
            $remember = (Input::get('remember')) === 'on';
            $user->login(Input::get('email'), Input::get('password'), $remember);
        } else {
            foreach ($validation->errors() as $error) {
                $err .= $error . ', ';
            }
        }
//   }
}
$error = rtrim($err, ', ');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        Войти
    </title>
    <meta name="description" content="Login">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <!-- Call App Mode on ios devices -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <!-- base css -->
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
    <link id="mytheme" rel="stylesheet" media="screen, print" href="#">
    <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" media="screen, print" href="css/page-login-alt.css">
</head>
<body>
    <div class="blankpage-form-field">
        <div class="page-logo m-0 w-100 align-items-center justify-content-center rounded border-bottom-left-radius-0 border-bottom-right-radius-0 px-4">
            <a href="javascript:void(0)" class="page-logo-link press-scale-down d-flex align-items-center">
                <img src="img/logo.png" alt="SmartAdmin WebApp" aria-roledescription="logo">
                <span class="page-logo-text mr-1">Учебный проект</span>
                <i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
            </a>
        </div>
        <div class="card p-4 border-top-left-radius-0 border-top-right-radius-0">
            <?php if (Session::exists('success')): ?>
            <div class="alert alert-success">
                <?=Session::getFlash('success');?>
            </div>
            <?php endif;?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?=$error?>
                </div>
            <?php endif;?>
            <?php if (Session::exists('error')): ?>
                <div class="alert alert-danger">
                    <?=Session::getFlash('error')?>
                </div>
            <?php endif;?>
            <form action="" method="post">
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input name="email" type="text" id="email" class="form-control" placeholder="Эл. адрес" value="<?=Session::get('email') ?? ''?>">
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Пароль</label>
                    <input name="password" type="password" id="password" class="form-control" placeholder="" >
                </div>
                <input type="hidden" name="token" value="<?=Token::generate()?>">
                <div>
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Запомнить меня</label>
                </div>
                <button type="submit" class="btn btn-default float-right">Войти</button>
            </form>
        </div>
        <div class="blankpage-footer text-center">
            Нет аккаунта? <a href="page_register.php"><strong>Зарегистрироваться</strong>
        </div>
    </div>
    <video poster="img/backgrounds/clouds.png" id="bgvid" playsinline autoplay muted loop>
        <source src="media/video/cc.webm" type="video/webm">
        <source src="media/video/cc.mp4" type="video/mp4">
    </video>
    <script src="js/vendors.bundle.js"></script>
</body>
</html>

<?php

$_SESSION['email'] = '';
