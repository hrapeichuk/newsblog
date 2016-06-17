<?php
require_once 'login.php';

$user_exists = false;

if (isset($_POST['login']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordConfirm'])) {
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];

    $user_exists = $connection->query("SELECT COUNT(*) AS count FROM users WHERE login = '$login' OR email = '$email'")->fetch_object()->count;

    if ($password == $passwordConfirm && !$user_exists) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        if ($stmt = $connection->prepare("INSERT INTO users (login, email, password) VALUES (?, ?, ?)")) {
            $stmt->bind_param('sss', $login, $email, $password);
            if (!$stmt->execute()) {
                die('Insert error');
            }
        }
        header("Location: newsblog.php");
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
    <form class="form-horizontal" method="post" action="registration.php">
        <div class="form-group">
            <label for="login" class="col-sm-4 control-label">Фамилия, имя</label>
            <div class="col-sm-5">
                <input type="login" class="form-control" id="login" name="login" placeholder="Иванов Иван" required>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-4 control-label">Электронная почта</label>
            <div class="col-sm-5">
                <input type="email" class="form-control" id="email" name="email" placeholder="ivanov.ivan@gmail.com" required>
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-4 control-label">Пароль</label>
            <div class="col-sm-5">
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
        </div>
        <div class="form-group">
            <label for="passwordConfirm" class="col-sm-4 control-label">Подтверждение пароля</label>
            <div class="col-sm-5">
                <input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm" required>
            </div>
        </div>
        <div style="margin-top: 25px;" class="form-group">
            <div class="col-sm-offset-4 col-sm-2">
                <button type="submit" class="btn btn-default">Зарегистрироваться</button>
            </div>
        </div>
    </form>
    <?php if ($password != $passwordConfirm): ?>
        <div class="alert alert-danger col-sm-offset-4 col-sm-5 text-center" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            Подтверждение пароля не произошло. Попробуйте ещё раз.
        </div>
    <?php endif; ?>
    <?php if ($user_exists): ?>
        <div class="alert alert-danger col-sm-offset-4 col-sm-5 text-center" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            Пользователь с такими данными уже существует. Попробуйте ещё раз.
        </div>
    <?php endif; ?>
</div>

