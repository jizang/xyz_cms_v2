<?php
require_once 'db/db_connect.php';
require_once 'includes/auth.php'; // 加入這行進行權限保護
require_once 'db/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM customers WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die("刪除失敗: " . $e->getMessage());
    }
}

header('Location: index.php');
exit;
?>