<?php
session_start();
session_regenerate_id(true);


header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $task_id = $_POST['id'];
} else {
    // タスクIDが指定されていない場合はエラーメッセージを返す
    $response = [
        'status' => 'error',
        'message' => 'タスクIDが指定されていません。'
    ];
    echo json_encode($response);
    exit();
}

try {
    $dsn = 'mysql:dbname=migrations;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';

    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'DELETE FROM tasks WHERE id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $task_id);
    $stmt->execute();

    // タスク削除後にプロジェクト詳細画面にリダイレクト
    $response = [
        'status' => 'success',
        'message' => 'タスクの削除に成功しました。'
    ];
    echo json_encode($response);
} catch (PDOException $e) {
    // エラーハンドリング
    $response = [
        'status' => 'error',
        'message' => 'エラーが発生しました：' . $e->getMessage()
    ];
    echo json_encode($response);
}
?>