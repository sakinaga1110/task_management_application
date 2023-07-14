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

// プロジェクトIDを取得する
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
} else {
    // プロジェクトIDが指定されていない場合はエラーメッセージを表示して終了
    echo 'プロジェクトIDが指定されていません。<br/>';
    exit();
}


$dsn = 'mysql:dbname=migrations;host=localhost;charset=utf8';
$user = 'root';
$password = '';

$dbh = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = 'SELECT * FROM projects WHERE user_id=? AND project_id=?';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(1, $_SESSION['id']);
$stmt->bindValue(2, $project_id);
$stmt->execute();

if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $project_name = $row['project_name'];
    $color_type = $row['color_type'];
    $description = $row['description'];
} else {
    // プロジェクトが見つからなかった場合のエラーメッセージを表示して終了
    echo '指定されたプロジェクトが見つかりません。<br/>';
    exit();
}
function getMaxOrderNum($status)
{
    global $dbh;
    $sql = 'SELECT MAX(order_num) AS max_order_num FROM tasks WHERE status = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $status);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['max_order_num'] ?? 0; // もしレコードが存在しない場合は0を返す
}

$sql = 'SELECT * FROM tasks WHERE project_id = ? ORDER BY order_num ASC';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(1, $project_id);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
$_SESSION['project_id'] = $project_id
    ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />

    <link rel="stylesheet" href="../assets/task_management.css">
    <script src="../assets/task_management.js" defer></script>

    <title>プロジェクト詳細</title>
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
    <div class="ProjectName">
        プロジェクト名:
        <?php echo $project_name; ?><br />
        プロジェクト詳細:
        <?php echo $description; ?><br />
    </div>
    <br/>
    <br/>
    <div class="progress" style="background-color: <?php echo $color_type; ?>">
        <div class="container">
            <h2>未対応</h2>
            <div id="button-container">
                <div class="taskButton" onclick="toggleForm(event)">タスク追加</div>
                <form id="taskForm" style="display: none;">
                    タイトル <input type="text" name="title"><br />
                    タスク詳細 <br />
                    <textarea name="task_description"></textarea><br>
                    <input type="hidden" name="order_num" value="<?php echo (getMaxOrderNum('not_started') + 1); ?>">
                    <input type="hidden" name="status" value="not_started">
                    <div class="store-button">登録</div>
                </form>
            </div>
            <div class="drop_target" id="not_started">
                <div class="task-container">
                    <?php foreach ($tasks as $task): ?>
                        <?php if ($task['status'] === 'not_started'): ?>
                            <div class="task-wrapper" draggable="true" data-task-id="<?php echo $task['id']; ?>"
                                data-order-num="<?php echo $task['order_num']; ?>">

                                <div class="dragged_item">
                                    <h3>
                                        タイトル:
                                        <?php echo $task['title']; ?> <br />
                                        詳細:
                                        <?php echo $task['task_description']; ?> <br />
                                    </h3>
                                    <button class="delete-button" data-task-id="<?php echo $task['id']; ?>">削除</button>
                                </div>
                            </div>

                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="container">
            <h2>対応中</h2>
            <div class="drop_target" id="in_progress">
                <div class="task-container">
                    <?php foreach ($tasks as $task): ?>
                        <?php if ($task['status'] === 'in_progress'): ?>
                            <div class="task-wrapper" draggable="true" data-task-id="<?php echo $task['id']; ?>"
                                data-order-num="<?php echo $task['order_num']; ?>">

                                <div class="dragged_item">
                                    <h3>
                                        タイトル:
                                        <?php echo $task['title']; ?> <br />
                                        詳細:
                                        <?php echo $task['task_description']; ?> <br />
                                    </h3>
                                    <button class="delete-button" data-task-id="<?php echo $task['id']; ?>">削除</button>
                                </div>
                            </div>

                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="container">


            <h2>完了</h2>
            <div class="drop_target" id="completed">
                <div class="task-container">
                    <?php foreach ($tasks as $task): ?>
                        <?php if ($task['status'] === 'completed'): ?>
                            <div class="task-wrapper" draggable="true" data-task-id="<?php echo $task['id']; ?>"
                                data-order-num="<?php echo $task['order_num']; ?>">

                                <div class="dragged_item">
                                    <h3>
                                        タイトル:
                                        <?php echo $task['title']; ?> <br />
                                        詳細:
                                        <?php echo $task['task_description']; ?> <br />
                                    </h3>
                                    <button class="delete-button" data-task-id="<?php echo $task['id']; ?>">削除</button>
                                </div>

                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
    <a class="to_index" href="index.php">戻る</a>
</body>


</html>