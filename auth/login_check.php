<?php
session_start();
require_once('../components/header.php');
$post=sanitize($_POST);
// ログイン処理
if (!empty($_POST)) {
    // ログインフォームが送信された場合の処理

    // フォームの入力値を取得
    $mail = $post['mail'];
    $pass = $post['pass'];

    // データベース接続情報
    $dsn = 'mysql:dbname=migrations;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';

    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT * FROM users WHERE mail=?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $mail);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['pass'])) {
            $_SESSION['login'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['path']=$user['path'];
            
            // クッキー削除
            setcookie('mail', '', time() - 3600);
            setcookie('pass', '', time() - 3600);

            if ($_POST['save'] == 'on') {
                // クッキー設定
                setcookie('mail', $mail, time() + 60 * 60 * 24);
                setcookie('pass', $pass, time() + 60 * 60 * 24);
            }

            header('Location: ../Project/index.php');
                       
            exit();
        } else {
            echo 'メールアドレスもしくはパスワードが違います。';
        }
    } catch (PDOException $e) {
        echo 'エラーが発生しました: ' . $e->getMessage();
        exit();
    }
}
?>

