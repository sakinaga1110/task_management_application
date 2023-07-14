<?php
session_start();
require_once('../components/header.php');
try {
    $post = sanitize($_POST);
    $user_id = $_SESSION['id'];
    $project_name = $post['project_name'];
    $description = $post['description'];
    $color_type = $post['color'];


    $dsn = 'mysql:dbname=migrations;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'INSERT INTO projects (user_id,project_name,description,color_type, created_at) VALUES (?,?, ?, ?,NOW())';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $project_name, PDO::PARAM_STR);
    $stmt->bindValue(3, $description, PDO::PARAM_STR);
    $stmt->bindValue(4, $color_type, PDO::PARAM_STR);
    $stmt->execute();
    $_SESSION['project_name'] = $project_name;
    header('Location: ../Project/index.php');

} catch (Exception $e) {
    echo 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}
?>