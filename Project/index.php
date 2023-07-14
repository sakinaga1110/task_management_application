<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
    echo 'ログインされていません。<br/>';
    echo '<a href="../auth/login.php">ログイン画面へ</a>';
    exit();
}
if (isset($_SESSION['last_activity'])) {
    // 最終アクティビティが一定時間経過している場合はログアウトとみなす
    $inactive_time = 60 * 60 * 2; // 非アクティブとみなす時間（秒）
    $current_time = time();
    $elapsed_time = $current_time - $_SESSION['last_activity'];

    if ($elapsed_time > $inactive_time) {
        // セッションを破棄してログアウト
        session_destroy();
        header('Location: ../auth/login.php');
        exit();
    } else {
        // 最終アクティビティの更新
        $_SESSION['last_activity'] = $current_time;
    }
} else {
    // 最終アクティビティが未設定の場合は設定する
    $_SESSION['last_activity'] = time();
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>中級　タスク管理アプリ</title>

    <link rel="stylesheet" href=../assets/task_management.css>
    <script src="../assets/task_management.js" defer></script>

</head>

<body>
<div class="container1" id="btn" onclick="openModal()">
        <?php
        echo $_SESSION['id'];
        echo '<img src="../assets/image/' . $_SESSION['path'] . '" class="icon">';
        echo $_SESSION['name'];
        echo "　";
        echo '▼';
        ?>
    </div>

    <div id="myModal" class="modal">
        
            <a href="../auth/logout.php"  class="modal-content">ログアウト</a>
    </div>
    <br/>
    <br />
    <h1>プロジェクト一覧</h1>
    <div class=container2>
      
        <div class="new_project" onclick="location.href='create.php'">新規作成</div>
        <br/>

        <?php
        $dsn = 'mysql:dbname=migrations;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';

        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT * FROM projects WHERE user_id=? ';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $_SESSION['id']);
        $stmt->execute();
        ?>
        <br />
        <?php
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<a class="all_project" href="show.php?id=' . $row['project_id'] . '">' . $row['project_name'] . '<br></a><br/>';
        }
        ?>
    </div>
</body>

</html>