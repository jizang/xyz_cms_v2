<?php
// 確保 session 啟動 (如果該頁面還沒啟動過)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XYZ 客戶管理系統</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container">
        <h1>XYZ Management</h1>
        <nav>
            <?php if(isset($_SESSION['user_id'])): ?>
                <!-- 登入後顯示的選單 -->
                <span style="margin-right: 15px; color: #7F8C8D; font-size: 0.9rem;">
                    Hi, <?php echo htmlspecialchars($_SESSION['real_name']); ?>
                </span>
                <a href="index.php" class="btn btn-secondary btn-sm">客戶列表</a>
                <!-- 新增這一行 -->
                <a href="quotes.php" class="btn btn-secondary btn-sm">報價管理</a> 
                <a href="users.php" class="btn btn-secondary btn-sm">帳號管理</a>
                <a href="logout.php" class="btn btn-sm" style="color:#E74C3C; border:1px solid #E74C3C; background:transparent;">登出</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container">