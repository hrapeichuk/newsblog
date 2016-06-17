<?php
require_once 'login.php';

session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']['id'];
    $login = $connection->query("SELECT * FROM users WHERE id = '$user_id'")->fetch_array(MYSQLI_ASSOC);
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $result = $connection->query("DELETE FROM news WHERE id='$id'");

        header('Location: newsblog.php');
}

if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['style'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $style = $_POST['style'];

    if ($stmt = $connection->prepare("INSERT INTO news (title, content, style) VALUES (?, ?, ?)")) {
        $stmt->bind_param('sss', $title, $content, $style);
        if (!$stmt->execute()) {
            die('Insert error');
        }
    }
    $news_id = $connection->insert_id;
    header("Location: newsblog.php#news_id_$news_id");
}

if (isset($_GET['exit'])){
    unset($_SESSION['user_id']);
    session_destroy();
    header('Location: newsblog.php');
}

$result = $connection->query("SELECT * FROM news ORDER BY id DESC LIMIT " . (($page - 1) * NEWS_ON_PAGE) . ", " . NEWS_ON_PAGE . ";");
$news_total_count = $connection->query("SELECT COUNT(*) AS count FROM news")->fetch_object()->count;
?>
<html>
<head>
    <title>Новостной блог</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php if ($user_id == NULL): ?>
        <div class="col-sm-1 col-sm-offset-9 text-center">
            <a class="btn btn-default" href="signin.php">Вход</a>
        </div>
<?php endif; ?>
<?php if ($user_id != NULL): ?>
        <div class="col-sm-3  text-left">
        Добро пожаловать, <?php echo $login['login']; ?>
        </div>
        <div class="col-sm-1 col-sm-offset-6 text-center">
            <a class="btn btn-default" href="newsblog.php?exit=1">Выход</a>
        </div>
<?php endif; ?>
        <div class="col-sm-1 text-center">
            <a class="btn btn-default" href="registration.php">Регистрация</a>
        </div>
<div class="text-center">
    <h1>Новостной блог</h1>
    <img src="large.jpg">
</div>

<div style="margin-top: 30px;" class="container">
    <?php for ($i = 0 ; $i < $result->num_rows ; ++$i): ?>
        <?php
        $result->data_seek($i);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $id = $row['id'];
        $title = $row['title'];
        $content = $row['content'];
        $style = $row ['style'];
        ?>
        <div class="panel panel-<?php echo $style; ?>" id="news_id_<?php echo $id; ?>">
            <div class="panel-heading">
                <a href="viewnews.php?action=view&id=<?php echo $id; ?>"><?php echo $title; ?></a>
            </div>
            <div class="panel-body">
                <?php echo nl2br($content); ?>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-1 col-sm-offset-9 text-center">
                        <a href="?action=delete&id=<?php echo $id; ?>">Удалить</a>
                    </div>
                    <div class="col-sm-2 text-center">
                        <a href="editnews.php?action=edit&id=<?php echo $id; ?>">Редактировать</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endfor; ?>

    <div class="row text-center">
        <div class="col-xs-12">
            <nav>
                <ul class="pagination">
                    <?php for ($i = 1 ; $i <= ceil($news_total_count / NEWS_ON_PAGE); $i++): ?>
                        <li class="<?php echo $page == $i ? "active" : "";  ?>"><a href="newsblog.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>

    <hr/>

    <form class="form-horizontal" method="post" action="newsblog.php">
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Название</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
        </div>
        <div class="form-group">
            <label for="content" class="col-sm-2 control-label">Текст</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="content" id="content" rows="15" wrap="soft" placeholder="Введите здесь вашу статью"></textarea><br>
            </div>
        </div>
        <div class="form-group">
            <label for="content" class="col-sm-2 control-label">Тема оформления</label>
            <div class="col-sm-10">
                <label class="radio-inline"><input type="radio" name="style" value="default" checked>Default</label>
                <label class="radio-inline"><input type="radio" name="style" value="primary">Primary</label>
                <label class="radio-inline"><input type="radio" name="style" value="success">Success</label>
                <label class="radio-inline"><input type="radio" name="style" value="info">Info</label>
                <label class="radio-inline"><input type="radio" name="style" value="warning">Warning</label>
                <label class="radio-inline"><input type="radio" name="style" value="danger">Danger</label><br><br><br>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">
                    <span aria-hidden="true"></span> Опубликовать статью
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
