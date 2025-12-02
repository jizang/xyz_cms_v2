<?php
require_once 'includes/auth.php';
require_once 'db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $username  = trim($_POST['username']);
    $real_name = trim($_POST['real_name']);
    $password  = $_POST['password'];

    try {
        if ($id) {
            // ==========================
            // 更新模式 (Update)
            // ==========================
            
            // 1. 如果有輸入密碼，則更新密碼；若留空，則只更新姓名
            if (!empty($password)) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET real_name = :real_name, password = :password WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':password', $passwordHash);
            } else {
                $sql = "UPDATE users SET real_name = :real_name WHERE id = :id";
                $stmt = $pdo->prepare($sql);
            }
            
            $stmt->bindValue(':real_name', $real_name);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            // 如果是修改自己的資料，順便更新 Session 中的顯示名稱
            if ($id == $_SESSION['user_id']) {
                $_SESSION['real_name'] = $real_name;
            }

        } else {
            // ==========================
            // 新增模式 (Create)
            // ==========================
            
            if (empty($username) || empty($password)) {
                die("錯誤：新增帳號時，帳號與密碼為必填。 <a href='javascript:history.back()'>返回</a>");
            }

            // 檢查帳號是否重複
            $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $check->execute([$username]);
            if ($check->rowCount() > 0) {
                die("錯誤：此帳號已存在。 <a href='javascript:history.back()'>返回</a>");
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (username, password, real_name) VALUES (:username, :password, :real_name)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':username' => $username, 
                ':password' => $passwordHash, 
                ':real_name' => $real_name
            ]);
        }

        header('Location: users.php');
        exit;

    } catch (PDOException $e) {
        die("操作失敗: " . $e->getMessage());
    }
}
?>