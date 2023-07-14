<?php session_start();
$_SESSION = array();
if (isset($_COOKIES[session_name()]) == true) {
    setcookie(session_name(), '', time() - 42000, '/');
}
session_destroy();
?>
<!DOCTYPE html>
<html>

<head>  <meta charset="UTF-8" />
    <title>中級　タスク管理アプリ</title>

    <link rel="stylesheet" href=../assets/task_management.css>
    <script src="../assets/task_management.js"></script>
</head>

<body>
    <h1>ログアウトしました。</h1>
    
    <div style="text-align: center;">
    <a class=CenterButton href="login.php">ログイン画面へ</a></div>

</body>

</html>