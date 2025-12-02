<?php
require_once 'includes/auth.php';
require_once 'db/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM quotes WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        die("刪除失敗: " . $e->getMessage());
    }
}

header('Location: quotes.php');
exit;
?>