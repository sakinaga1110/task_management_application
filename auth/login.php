<?php
#require_once('../components/header.php');
#$post=sanitize($_POST);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>中級　タスク管理アプリ</title>
    <link rel="stylesheet" href="../assets/task_management.css">
    <link rel="stylesheet" href="../assets/task_management.js">
</head>

<body>
    <h1> ログイン</h1>
    <div class="LoginContainer">

        <a href="register.php" class="CreateAccount">
            未登録の方はこちら
        </a>

        <form method="post" action="login_check.php">
            <br /> <br />
            メールアドレス
            <input type="email" name="mail" required value="<?php echo $_COOKIE['mail'] ?? ''; ?>">
            <br />
            パスワード
            <input type="password" name="pass" required value="<?php echo $_COOKIE['pass'] ?? ''; ?>"><br /><br />

            <label>
                <div class="label">
                    <input type="checkbox" name="save" value="on">次回入力を省略する
                </div>
            </label><br/><br/>
            
            <button type="submit">ログイン</button><br />
        </form>
    </div>
</body>

</html>