<?php include 'includes/header.php'; ?>
require_once 'includes/auth.php'; // 加入這行進行權限保護
require_once 'db/db_connect.php';

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2 style="margin-top:0; margin-bottom: 24px;">新增客戶</h2>
    
    <form action="save_customer.php" method="POST">
        
        <div class="form-group">
            <label for="name">姓名 <span style="color:#E74C3C">*</span></label>
            <input type="text" id="name" name="name" required placeholder="請輸入姓名">
        </div>

        <div class="form-group">
            <label>性別</label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="Male"> 男</label>
                <label><input type="radio" name="gender" value="Female"> 女</label>
                <label><input type="radio" name="gender" value="Other"> 其他</label>
            </div>
        </div>

        <div class="form-group">
            <label for="company">公司名稱</label>
            <input type="text" id="company" name="company" placeholder="例如：XYZ Ltd.">
        </div>

        <div class="form-group">
            <label for="email">Email <span style="color:#E74C3C">*</span></label>
            <input type="email" id="email" name="email" required placeholder="name@example.com">
        </div>

        <div class="form-group">
            <label for="phone">聯絡電話</label>
            <input type="text" id="phone" name="phone" placeholder="09xx-xxx-xxx">
        </div>

        <div class="form-group">
            <label for="notes">備註</label>
            <textarea id="notes" name="notes" rows="4" placeholder="其他注意事項..."></textarea>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">儲存資料</button>
            <a href="index.php" class="btn btn-secondary" style="margin-left: 10px;">取消</a>
        </div>

    </form>
</div>

<?php include 'includes/footer.php'; ?>