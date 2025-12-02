<?php
require_once 'db/db_connect.php';

// 設定要重設的密碼
$new_password = '123456';

// 1. 生成真實的 Hash (這會根據您伺服器的 PHP 版本產生正確的加密字串)
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

try {
    // 2. 更新資料庫中的 admin 密碼
    $stmt = $pdo->prepare("UPDATE users SET password = :pass WHERE username = 'admin'");
    $stmt->execute([':pass' => $password_hash]);

    echo "<h1>密碼重設成功！</h1>";
    echo "<p>帳號: <strong>admin</strong></p>";
    echo "<p>新密碼: <strong>123456</strong></p>";
    echo "<p>真實 Hash 值: " . $password_hash . "</p>";
    echo "<br><a href='login.php'>點此前往登入頁面</a>";

} catch (PDOException $e) {
    echo "錯誤: " . $e->getMessage();
}
?>