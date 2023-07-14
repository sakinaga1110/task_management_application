<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>中級　タスク管理アプリ</title>
    <link rel="stylesheet" href="../assets/task_management.css">
    <script src="../assets/task_management.js"></script>
</head>
<body>
    <h1>新規登録</h1>
<div class="LoginContainer">
    <div class="container3">
    <form method="post" action="confirm.php" enctype="multipart/form-data">
        名前　※必須<br/>
        <input type="text" name="name" required><br/>
        メールアドレス　※必須<br/>
        <input type="email" name="mail" required><br/>
        パスワードの設定　※必須<br/>
        <input type="password" name="pass" required><br/>
        パスワードの確認　※必須<br/>
        <input type="password" name="pass2" required><br/>
        写真<br/>
        <input type="file" name="path"><br/>
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="ＯＫ">
    </form>
    </div>
</div>
</body>

</html>
