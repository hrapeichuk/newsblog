<?php
require_once 'login.php';

$user_exists = false;
$password_is_right = false;

if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $user_exists = $connection->query("SELECT COUNT(*) AS count FROM users WHERE login = '$login'")->fetch_object()->count;
    $password_database = $connection->query("SELECT * FROM users WHERE login = '$login'")->fetch_array(MYSQLI_ASSOC);

    $password_is_right = password_verify ($password , $password_database['password']);

    if ($user_exists && $password_is_right){
        session_start();
        $_SESSION['user_id'] = $connection->query("SELECT id FROM users WHERE login = '$login'")->fetch_array(MYSQLI_ASSOC);

        header("Location: newsblog.php");
    }
    else {
        $error = true;
    }
}
?>
<html>
<head>
    <title>Новостной блог</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>

<div style="margin-top: 150px;">
    <form class="form-horizontal" method="post" action="signin.php">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="login" class="col-sm-4 control-label">Фамилия, имя</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="login" name="login">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-4 control-label">Пароль</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="password" name="password">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> Оставаться в системе
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" class="btn btn-default">Войти</button>
                </div>
            </div>
        </form>
    </form>
    <?php if ($error): ?>
        <div class="alert alert-danger col-sm-offset-4 col-sm-5 text-center" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            Были введены неверные данные. Попробуйте ещё раз.
        </div>
    <?php endif; ?>
</div>

