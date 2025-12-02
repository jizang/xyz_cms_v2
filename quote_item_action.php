<?php
require_once 'includes/auth.php';
require_once 'db/db_connect.php';

// 輔助函數：重新計算並更新主報價單總金額
function updateQuoteTotal($pdo, $quote_id) {
    // 1. 計算所有細項總和
    $stmt = $pdo->prepare("SELECT SUM(subtotal) FROM quote_items WHERE quote_id = ?");
    $stmt->execute([$quote_id]);
    $total = $stmt->fetchColumn(); // 若無細項會回傳 null

    $total = $total ? $total : 0;

    // 2. 更新主表
    $update = $pdo->prepare("UPDATE quotes SET amount = ? WHERE id = ?");
    $update->execute([$total, $quote_id]);
}

// --- 接收刪除請求 ---
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $item_id = (int)$_GET['item_id'];
    $quote_id = (int)$_GET['quote_id']; // 傳回來以便導向

    if ($item_id > 0) {
        $stmt = $pdo->prepare("DELETE FROM quote_items WHERE id = ?");
        $stmt->execute([$item_id]);
        
        // 更新總金額
        updateQuoteTotal($pdo, $quote_id);
    }
    header("Location: quote_edit.php?id=$quote_id");
    exit;
}

// --- 接收儲存請求 (新增或修改細項) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quote_id    = (int)$_POST['quote_id'];
    $item_id     = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
    $description = trim($_POST['description']);
    $quantity    = (float)$_POST['quantity'];
    $unit_price  = (float)$_POST['unit_price'];
    
    // 計算小計
    $subtotal = $quantity * $unit_price;

    if ($quote_id > 0 && !empty($description)) {
        if ($item_id) {
            // Update Item
            $sql = "UPDATE quote_items SET description=?, quantity=?, unit_price=?, subtotal=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$description, $quantity, $unit_price, $subtotal, $item_id]);
        } else {
            // Insert Item
            $sql = "INSERT INTO quote_items (quote_id, description, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$quote_id, $description, $quantity, $unit_price, $subtotal]);
        }
        
        // 更新總金額
        updateQuoteTotal($pdo, $quote_id);
    }

    header("Location: quote_edit.php?id=$quote_id");
    exit;
}
?>