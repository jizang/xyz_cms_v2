<?php 
require_once 'includes/auth.php';
include 'includes/header.php'; 
?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h2 style="margin-top:0; margin-bottom: 24px;">新增管理員</h2>
    
    <form action="user_save.php" method="POST">
        
        <div class="form-group">
            <label>帳號 (Username) <span style="color:#E74C3C">*</span></label>
            <input type="text" name="username" required placeholder="例如: admin2">
        </div>

        <div class="form-group">
            <label>真實姓名</label>
            <input type="text" name="real_name" placeholder="例如: 李經理">
        </div>

        <div class="form-group">
            <label>密碼 <span style="color:#E74C3C">*</span></label>
            <input type="password" name="password" required placeholder="設定登入密碼">
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">建立帳號</button>
            <a href="users.php" class="btn btn-secondary" style="margin-left: 10px;">取消</a>
        </div>

    </form>
</div>

<?php include 'includes/footer.php'; ?>