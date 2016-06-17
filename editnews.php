<?php
require_once 'login.php';

$id = (int)$_GET['id'];
$result = $connection->query("SELECT * FROM news WHERE id='$id'");
$row = $result->fetch_array(MYSQLI_ASSOC);
$id = $row['id'];
$title = $row['title'];
$content = $row['content'];
$style = $row['style'];

if (isset($_POST['update']) && isset($_POST['id'])) {

    $id = (int)$_POST['id'];

    $title_new = $_POST['title'];
    $content_new = $_POST['content'];
    $style_new = $_POST['style'];

    if ($result = $connection->prepare("UPDATE news SET title = ?, content = ?, style = ? WHERE id = ?")) {
        $result->bind_param('sssi', $title_new, $content_new, $style_new, $id);
        if (!$result->execute()) {
            die('Insert error');
        }
    }
    header("Location: newsblog.php#news_id_$id");
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
    <h1>Редактирование новости</h1>
</div>

<div class="container">
    <form class="form-horizontal" method="post" action="editnews.php">
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label">Название</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title" required value="<?php echo $title; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="content" class="col-sm-2 control-label">Текст</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="content" id="content" cols="100" rows="15" wrap="soft" placeholder="Введите здесь вашу статью"><?php echo $content; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="style" class="col-sm-2 control-label">Тема оформления</label>
            <div class="col-sm-10">
                <label class="radio-inline"><input type="radio" name="style" id="style" value="default" <?php echo $style == "default" ? "checked" : ""; ?>>Default</label>
                <label class="radio-inline"><input type="radio" name="style" value="primary" <?php echo $style == "primary" ? "checked" : ""; ?>>Primary</label>
                <label class="radio-inline"><input type="radio" name="style" value="success" <?php echo $style == "success" ? "checked" : ""; ?>>Success</label>
                <label class="radio-inline"><input type="radio" name="style" value="info" <?php echo $style == "info" ? "checked" : "";  ?>>Info</label>
                <label class="radio-inline"><input type="radio" name="style" value="warning" <?php echo $style == "warning" ? "checked" : "";  ?>>Warning</label>
                <label class="radio-inline"><input type="radio" name="style" value="danger" <?php echo $style == "danger" ? "checked" : ""; ?>>Danger</label><br><br><br>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" name="update" class="btn btn-default" value="Обновить новость">
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