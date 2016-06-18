<?php
require_once 'login.php';
session_start();

$user_id = 0;

$news_id = (int)$_GET['id'];
$result = $connection->query("SELECT * FROM news WHERE id='$news_id'");
$row = $result->fetch_array(MYSQLI_ASSOC);
$title = $row['title'];
$content = $row['content'];
$style = $row['style'];

if (isset($_SESSION['user_id'])) {
$user_id = $_SESSION['user_id']['id']; }

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])  && isset($_GET['news_id'])) {
    $comment_id = (int)$_GET['id'];
    $news_id = (int)$_GET['news_id'];

    $result = $connection->query("DELETE FROM comments WHERE id='$comment_id'");
    header("Location: viewnews.php?action=view&id=".$news_id);
}
$result_comments = $connection->query("SELECT * FROM comments WHERE news_id='$news_id' ORDER BY id DESC");

if (isset($_GET['action']) && $_GET['action'] == 'comment' && isset($_POST['author']) && isset($_POST['message'])) {
    $author = $_POST['author'];
    $message = $_POST['message'];

    if ($result_comments = $connection->prepare("INSERT INTO comments (user_id, news_id, author, message) VALUES (?, ?, ?, ?)")) {
        $result_comments->bind_param('iiss', $user_id, $news_id, $author, $message);
        if (!$result_comments->execute()) {
            die('Insert error');
        }
    }
    header("Location: viewnews.php?action=view&id=".$news_id);
}
?>
<html>
<head>
    <title>Новостной блог</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>

<div style="margin-bottom: 25px;" class="text-center">
    <h1>Новостной блог</h1>
</div>
<div class="row">
    <div style="margin-left: 15px;" class="col-sm-3 text-center">
        <a class="btn btn-default" href="newsblog.php#news_id_<?php echo $news_id; ?>">Вернуться назад</a>
    </div>
</div>
<div class="container" style="margin-top: 40px;">
    <div class="panel panel-<?php echo $style; ?>" id="news_id_<?php echo $news_id; ?>">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $title; ?></h3>
        </div>
        <div class="panel-body">
            <?php echo nl2br($content); ?>
        </div>
    </div>

    <?php for ($i = 0 ; $i < $result_comments->num_rows ; ++$i): ?>
        <?php
        $result_comments->data_seek($i);
        $row = $result_comments->fetch_array(MYSQLI_ASSOC);
        $id_comment = $row['id'];
        $author = $row['author'];
        $message = $row['message'];
        ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img class="media-object" src="photo.jpg" alt="">
                        </a>
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading"><?php echo $author; ?></h5>
                        <p><em><?php echo $message; ?></em></p>
                    </div>
                </div>
                <div class="col-sm-3 col-sm-offset-9 text-center">
                    <a href="viewnews.php?action=delete&id=<?php echo $id_comment; ?>&news_id=<?php echo $news_id; ?>">Удалить комментарий</a>
                </div>
            </div>
        </div>
    <?php endfor; ?>
    <hr/>
    <form class="form-horizontal" method="post" action="viewnews.php?action=comment&id=<?php echo $news_id; ?>">
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Имя пользователя</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="author" name="author" required>
            </div>
        </div>
        <div class="form-group">
            <label for="content" class="col-sm-2 control-label">Комментарий</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="message" id="message" rows="5" wrap="soft" placeholder="Введите здесь ваш комментарий"></textarea><br>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">
                    <span aria-hidden="true"></span> Опубликовать комментарий
                </button>
            </div>
        </div>
    </form>
</div>
<footer style="margin-top: 50px;" class="text-center">
    <small>
        Новостной блог<br>Copyright &copy Храпейчук Виталина
    </small>
</footer>
</body>
</html>