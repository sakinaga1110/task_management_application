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
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>中級　タスク管理アプリ</title>
    <link rel="stylesheet" href=../assets/task_management.css>
    <script src="../assets/task_management.js"></script>
</head>

<body>
    <button class="container1" id="btn" onclick="openModal()">
        <?php
        echo$_SESSION['id'];
        echo '<img src="../assets/image/' . $_SESSION['path'] . '" class="icon">';
        echo $_SESSION['name'];
        echo "　";
        echo '▼';
        ?>
    </button>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <a href="../auth/logout.php"><button>ログアウト</button></a>
        </div>
    </div>

    <div class="create_Project">
        <h3>プロジェクト作成</h3>
        <form method="post" action="Project.php">
           

            <h3>プロジェクト名　※必須</h3>
            <input type="text" name="project_name" required>
            <br />
            <br />

            <details>
                <summary>詳細設定</summary>
                プロジェクト概要<br />
                <textarea type="text" name="description"></textarea>
                <br />
                プロジェクトカラー
                <br />
                <div class="container_radio">
                    <label>
                        <div class="item"><input type="radio" name="color" value="green" id="green"onclick="changeBackgroundColor('green')">
                            <div class="Project_Color_Green"></div>
                        </div>
                    </label>
                    <label>
                        <div class="item"><input type="radio" name="color" value="yellow" id="yellow"onclick="changeBackgroundColor('yellow')">
                            <div class="Project_Color_Yellow"></div>
                        </div>
                    </label>
                    <label>
                        <div class="item"><input type="radio" name="color" value="red" id="red"onclick="changeBackgroundColor('red')">
                            <div class="Project_Color_Red"></div>
                        </div>
                    </label>
                </div>
            </details>

            <br />
            <input type="submit" value="登録">
        </form>
        <input type="button" onclick="history.back()" value="戻る">
    </div>

</body>

</html>