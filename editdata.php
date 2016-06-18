<?php
require_once 'login.php';

session_start();
$user_id = $_SESSION['user_id']['id'];

if (isset($_POST['update']) && $_POST['login']) {
    $login = $_POST['login'];

    if ($result = $connection->prepare("UPDATE users SET login = ? WHERE id = ?")) {
        $result->bind_param('si', $login, $user_id);
        if (!$result->execute()) {
            die('Update error');
        }
    }
    header("Location: profile.php#user_id_$user_id");
}

if (isset($_POST['update']) && isset($_POST['password']) && isset($_POST['passwordConfirm'])) {
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];

    if ($password == $passwordConfirm) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        if ($result = $connection->prepare("UPDATE users SET password = ? WHERE id = ?")) {
            $result->bind_param('si', $password, $user_id);
            if (!$result->execute()) {
                die('Update error');
            }
        }
        header("Location: profile.php#user_id_$user_id");
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
<div style="margin-top: 100px;">
    <form class="form-horizontal" method="post" action="editdata.php">
        <div class="form-group">
            <h4 style="margin-left: 550px; margin-bottom: 50px;" >Заполните те поля, которые Вы хотите обновить</h4>
            <label for="login" class="col-sm-4 control-label">Фамилия, имя</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="login" name="login">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-4 control-label">Пароль</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="password" name="password">
            </div>
        </div>
        <div class="form-group">
            <label for="passwordConfirm" class="col-sm-4 control-label">Подтверждение пароля</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="passwordConfirm" name="passwordConfirm">
            </div>
        </div>
        <div style="margin-top: 25px;" class="form-group">
            <div class="col-sm-offset-4 col-sm-2">
                <input type="submit" name="update" class="btn btn-default" value="Обновить данные">
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
</div>

