<?php
require_once 'db/db_connect.php';
require_once 'includes/auth.php'; // 加入這行進行權限保護
require_once 'db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. 接收並過濾資料
    $id      = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $name    = trim($_POST['name']);
    $gender  = $_POST['gender'] ?? null;
    $company = trim($_POST['company']);
    $email   = trim($_POST['email']);
    $phone   = trim($_POST['phone']);
    $notes   = trim($_POST['notes']);

    // 簡單驗證
    if (empty($name) || empty($email)) {
        die("錯誤：姓名與 Email 為必填欄位。 <a href='javascript:history.back()'>返回</a>");
    }

    try {
        if ($id) {
            // --- 更新模式 (Update) ---
            $sql = "UPDATE customers SET 
                    name = :name, 
                    gender = :gender, 
                    company = :company, 
                    email = :email, 
                    phone = :phone, 
                    notes = :notes 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        } else {
            // --- 新增模式 (Create) ---
            $sql = "INSERT INTO customers (name, gender, company, email, phone, notes) 
                    VALUES (:name, :gender, :company, :email, :phone, :notes)";
            $stmt = $pdo->prepare($sql);
        }

        // 綁定參數並執行
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':gender', $gender);
        $stmt->bindValue(':company', $company);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':notes', $notes);
        
        $stmt->execute();

        // 成功後導回列表
        header('Location: index.php');
        exit;

    } catch (PDOException $e) {
        die("資料儲存失敗: " . $e->getMessage());
    }
} else {
    // 非 POST 請求導回首頁
    header('Location: index.php');
    exit;
}
?>