<?php
session_start();

$response = []; // レスポンスデータを格納する配列

$data = json_decode(file_get_contents('php://input'), true);

$title = $data['title'];
$task_description = $data['task_description'];

$project_id = $_SESSION['project_id'];
$order_num = $data['order_num'] ?? null; // 追加：order_numのデフォルト値をnullに設定
$status = $data['status'] ?? null; // 追加：statusのデフォルト値をnullに設定

try {
    $dsn = 'mysql:dbname=migrations;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'INSERT INTO tasks (project_id, title, task_description, order_num, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())';
    $stmt = $dbh->prepare($sql);

    $stmt->bindValue(1, $project_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $title, PDO::PARAM_STR);
    $stmt->bindValue(3, $task_description, PDO::PARAM_STR);
    $stmt->bindValue(4, $order_num, PDO::PARAM_INT);
    $stmt->bindValue(5, $status, PDO::PARAM_STR);
    $stmt->execute();
    $lastInsertedId = $dbh->lastInsertId();
    $orderNum = $order_num !== null ? $order_num : $lastInsertedId;

    $dbh = null;

    $response = [
        'status' => 'success',
        'message' => 'タスクの登録に成功しました',
        'taskId' => $lastInsertedId,
        'orderNum' => $orderNum
    ];
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'タスクの登録に失敗しました：' . $e->getMessage();
    // 必要に応じてエラーの詳細情報を返すこともできます
    // $response['error'] = $e->getMessage();
}

// レスポンスの設定
header('Content-Type: application/json');
echo json_encode($response);
?>
