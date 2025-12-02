<?php
session_start();
require_once 'db/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        // 驗證密碼 Hash
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['real_name'] = $user['real_name'];
            header('Location: index.php');
            exit;
        } else {
            $error = '帳號或密碼錯誤';
        }
    } else {
        $error = '請輸入帳號與密碼';
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>登入 - XYZ CRM</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; height: 100vh; background-color: #F2F4F6; }
        .login-card { background: white; padding: 40px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .login-title { text-align: center; margin-bottom: 30px; color: #2C3E50; }
        .alert { color: #E74C3C; font-size: 0.9rem; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="login-title">XYZ System Login</h2>
        
        <?php if($error): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>帳號</label>
                <input type="text" name="username" required placeholder="admin">
            </div>
            <div class="form-group">
                <label>密碼</label>
                <input type="password" name="password" required placeholder="******">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">登入系統</button>
        </form>
    </div>
</body>
</html>