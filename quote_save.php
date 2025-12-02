<?php
require_once 'includes/auth.php';
require_once 'db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    
    // 接收主檔資料
    $quote_number = trim($_POST['quote_number']);
    $customer_id  = (int)$_POST['customer_id'];
    $subject      = trim($_POST['subject']);
    $status       = $_POST['status'];
    $valid_until  = !empty($_POST['valid_until']) ? $_POST['valid_until'] : null;
    $details      = trim($_POST['details']); // 這裡改為備註條款

    if (empty($quote_number) || empty($subject) || $customer_id <= 0) {
        die("錯誤：單號、客戶與主題為必填。 <a href='javascript:history.back()'>返回</a>");
    }

    try {
        if ($id) {
            // Update Master
            $sql = "UPDATE quotes SET 
                    quote_number = ?, customer_id = ?, subject = ?, 
                    status = ?, valid_until = ?, details = ? 
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$quote_number, $customer_id, $subject, $status, $valid_until, $details, $id]);
            
            // 導回編輯頁面以便繼續操作細項
            header("Location: quote_edit.php?id=$id"); 
        } else {
            // Insert Master (初始金額為 0)
            $sql = "INSERT INTO quotes 
                    (quote_number, customer_id, subject, amount, status, valid_until, details) 
                    VALUES (?, ?, ?, 0, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$quote_number, $customer_id, $subject, $status, $valid_until, $details]);
            
            // 取得新產生的 ID
            $new_id = $pdo->lastInsertId();
            
            // 導向編輯頁面讓使用者新增細項
            header("Location: quote_edit.php?id=$new_id");
        }
        exit;

    } catch (PDOException $e) {
        die("儲存失敗: " . $e->getMessage());
    }
}
?>