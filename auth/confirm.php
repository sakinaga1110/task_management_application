<?php
require_once('../components/header.php');

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>中級　タスク管理アプリ</title>
    <link rel="stylesheet" href="../assets/task_management.css">
    <link rel="stylesheet" href="../assets/task_management.js">
    <h1>プロジェクト一覧</h1>
</head>

<body>
    <?php
    $post=sanitize($_POST);
    $name = $post['name'];
    $mail = $post['mail'];
    $pass = $post['pass'];
    $pass2 = $post['pass2'];
    $path = $_FILES['path']['tmp_name'];
    $pathname = $_FILES['path']['name'];
    $pathsize = $_FILES['path']['size'];

    if ($name == '') {
        echo '名前が入力されていません。';
    } else {
        echo '名前：';
        echo $name;
        echo '<br/>';
    }

    if ($mail == '') {
        echo 'メールアドレスを入力してください。';
    } else {
        echo 'メールアドレス：';
        echo $mail;
        echo '<br/>';
    }

    if ($pass == '') {
        echo 'パスワードを入力してください。';
    }

    if ($pass != $pass2) {
        echo 'パスワードが一致しません。';
    }
    
    if($pathsize>0)
    {
        if($pathsize>1000000)
        {
            echo'画像サイズが大きすぎます';
        }
        else
        {
            move_uploaded_file($path,'../assets/image/'.$pathname);
            echo'<img src="../assets/image/'.$pathname.'">';
            echo'<br/>';
        }
    }
    if ($name == '' || $mail == '' || $pass != $pass2 || $pathsize > 1000000)

    {
        echo '<form>';
        echo '<input type="button" onclick="history.back()" value="戻る">';
        echo '</form>';
    } else {
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT); // パスワードをハッシュ化

        echo '<form method="post" action="complete.php" enctype="multipart/form-data">';
        echo '<input type="hidden" name="name" value="' . $name . '">';
        echo '<input type="hidden" name="mail" value="' . $mail . '">';
        echo '<input type="hidden" name="pass" value="' . $hashedPass . '">';
        echo '<input type="hidden" name="path" value="' . $pathname .'">';

        echo '<input type="submit" value="ＯＫ">';
        echo '</form>';
    }
   
    ?>

</body>

</html>
