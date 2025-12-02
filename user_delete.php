<?php
require_once 'includes/auth.php';
require_once 'db/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 防止刪除自己
if ($id == $_SESSION['user_id']) {
    die("無法刪除自己。 <a href='users.php'>返回</a>");
}

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

header('Location: users.php');
exit;
?>