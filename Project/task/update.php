<?php

file_put_contents('received_data.txt', file_get_contents('php://input'));

// POSTリクエストから必要なデータを取得する
$data = $_POST;
file_put_contents('decoded_data.txt', var_export($data, true));

// 必要なデータが正しく受け取れているかを確認
if (isset($data['status']) && isset($data['order_num']) && isset($data['task_id'])) {
    $status = $data['status'];
    $order_num = $data['order_num'];
    $id = $data['task_id'];

    try {
        $dsn = 'mysql:dbname=migrations;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';

        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'UPDATE tasks SET status=?,order_num=? WHERE id=?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $status);
        $stmt->bindValue(2, $order_num);
        $stmt->bindValue(3, $id);
        $stmt->execute();

        // 更新が成功した場合のレスポンスを返す
        $response = array(
            'status' => 'success',
            'message' => 'ステータスを更新しました'
        );

        // JSONヘッダーを設定してレスポンスを返す
        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (PDOException $e) {
        // エラーハンドリング
        $response = array(
            'status' => 'error',
            'message' => 'エラーが発生しました：' . $e->getMessage()
        );

        // JSONヘッダーを設定してエラーレスポンスを返す
        http_response_code(500); // サーバーエラーのステータスコードを設定
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // 必要なデータが受け取れていない場合のエラーハンドリング
    $response = array(
        'status' => 'error',
        'message' => '必要なデータが受け取れませんでした'
    );

    // JSONヘッダーを設定してエラーレスポンスを返す
    http_response_code(400); // バッドリクエストのステータスコードを設定
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>

