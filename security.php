<?php

require_once 'init.php';

$user = DataBase::getConnect()->get('users', ['id'=>$_GET['id']]);

if (!empty($_POST)) {
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        'email' =>
        [
            'required' => true,
            'email' => true,
            'min' => 8,
            'max' => 25
        ],
        'password' =>
            [
                'required' => true,
                'min' => 3
            ],
        'password_again' =>
            [
                'required' => true,
                'matches' => 'password'
            ]]);
    if (isset($_POST['email'])) {
        $checkEmail = $validation->checkUser('email', $_POST['email'], $_GET['id']);
    }

    if($validation->passed() && $checkEmail) {
        $params = [];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $params['email'] = $_POST['email'];
        $params['password'] = $password;
        $user = new User();
        $user->update($params, $_GET['id']);
        Session::setFlash('success', 'Profile updated successfully!');
        Redirect::to('users.php');
    } else {
        foreach ($validation->errors() as $error) {
            echo $error . '<br>';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Безопаность</title>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-brands.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
        <a class="navbar-brand d-flex align-items-center fw-500" href="users.php"><img alt="logo" class="d-inline-block align-top mr-2" src="img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Главная <span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="users.php">Назад</a>
                </li>
            </ul>
        </div>
    </nav>
    <main id="js-page-content" role="main" class="page-content mt-3">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-lock'></i> Безопасность
            </h1>

        </div>
        <form action="" method="post">
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Обновление эл. адреса и пароля</h2>
                            </div>
                            <div class="panel-content">
                                <!-- email -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Email</label>
                                    <input name="email" type="text" id="simpleinput" class="form-control" value="<?=$user[0]->email?>">
                                </div>

                                <!-- password -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Пароль</label>
                                    <input name="password" type="password" id="simpleinput" class="form-control">
                                </div>

                                <!-- password confirmation-->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Подтверждение пароля</label>
                                    <input name="password again" type="password" id="simpleinput" class="form-control">
                                </div>


                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button type="submit" class="btn btn-warning">Изменить</button>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script src="js/vendors.bundle.js"></script>
    <script src="js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

            $('input[type=radio][name=contactview]').change(function()
                {
                    if (this.value == 'grid')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
                        $('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
                        $('#js-contacts .js-expand-btn').addClass('d-none');
                        $('#js-contacts .card-body + .card-body').addClass('show');

                    }
                    else if (this.value == 'table')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
                        $('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
                        $('#js-contacts .js-expand-btn').removeClass('d-none');
                        $('#js-contacts .card-body + .card-body').removeClass('show');
                    }

                });

                //initialize filter
                initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
        });

    </script>
</body>
</html>