<?php
require_once 'login.php';

session_start();

$user_id = $_SESSION['user_id']['id'];
$user_data = $connection->query("SELECT * FROM users WHERE id = '$user_id'")->fetch_array(MYSQLI_ASSOC);

$news_statistics = $connection->query("SELECT COUNT(*) AS count FROM news WHERE user_id = '$user_id'")->fetch_object()->count;
$comments_statistics = $connection->query("SELECT COUNT(*) AS count FROM comments WHERE user_id = '$user_id'")->fetch_object()->count;
?>
<html>
<head>
    <title>Новостной блог</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<div class="container">
    <div class="row" style="margin-top: 30px; margin-bottom: 50px; margin-left: -40px;">
        <div class="col-sm-3 text-center">
            <a class="btn btn-default" href="newsblog.php">Вернуться к списку новостей</a>
        </div>
    </div>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="media">
            <div class="media-left">
                <a href="#">
                    <img style="margin-left: 30px; margin-top: 20px;" class="media-object" src="photo_profile.jpg" alt="">
                </a>
            </div>
            <div class="media-body">
                <h3 style="margin-left: 60px; margin-top: 25px;" class="media-heading"><?php echo $user_data['login']; ?></h3>
                <h5 style="margin-left: 60px; margin-top: 25px; font-style: italic" class="media-heading"><?php echo $user_data['email']; ?></h5>
                <h5 style="margin-left: 60px; margin-top: 100px; font-style: italic" class="media-heading">Количество опубликованных статей: <?php echo $news_statistics; ?></h5>
                <h5 style="margin-left: 60px; margin-top: 25px; font-style: italic" class="media-heading">Количество оставленных комментариев: <?php echo $comments_statistics; ?></h5>
                <p><em></em></p>
            </div>
        </div>
        <div class="col-sm-3 col-sm-offset-9 text-center">
            <a href="editdata.php">Редактировать профиль</a>
        </div>
    </div>
</div>
</div>