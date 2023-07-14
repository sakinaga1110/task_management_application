<?php
require_once('../components/header.php');
$post = sanitize($_POST);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>中級　タスク管理アプリ</title>
    <link rel="stylesheet" href="../assets/task_management.css">
    <script src="../assets/task_management.js"></script>
</head>


<body>
    <?php try {
        $name = $post['name'];
        $mail = $post['mail'];
        $pass = $post['pass'];
        $path = $post['path'];
       
        $dsn = 'mysql:dbname=migrations;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'INSERT INTO users (name, mail, pass, path, created_at) VALUES (?, ?, ?, ?, NOW())';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $name, PDO::PARAM_STR);
        $stmt->bindValue(2, $mail, PDO::PARAM_STR);
        $stmt->bindValue(3, $pass, PDO::PARAM_STR);
        $stmt->bindValue(4, $path, PDO::PARAM_STR);
        $stmt->execute();

        $dbh = null;
        echo $name . 'さんを追加しました。<br/>';
    } catch (Exception $e) {
        echo '<h1>ただいま障害により大変ご迷惑をお掛けしております。</h1> <a href="login.php">ログイン画面に戻る</a>
        </body>';
        exit();
    }
    ?>

    <a href="login.php">ログイン画面に戻る</a>
</body>

</html>