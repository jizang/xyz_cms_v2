<?php 
require_once 'db/db_connect.php';
require_once 'includes/auth.php'; // 加入這行進行權限保護
require_once 'db/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$customer = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $customer = $stmt->fetch();
}

if (!$customer) {
    die("找不到該客戶資料。 <a href='index.php'>返回列表</a>");
}

include 'includes/header.php'; 
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2 style="margin-top:0; margin-bottom: 24px;">編輯客戶資料</h2>
    
    <form action="save_customer.php" method="POST">
        <!-- 隱藏欄位傳送 ID -->
        <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">

        <div class="form-group">
            <label for="name">姓名 <span style="color:#E74C3C">*</span></label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo htmlspecialchars($customer['name']); ?>">
        </div>

        <div class="form-group">
            <label>性別</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="gender" value="Male" 
                    <?php echo ($customer['gender'] == 'Male') ? 'checked' : ''; ?>> 男
                </label>
                <label>
                    <input type="radio" name="gender" value="Female" 
                    <?php echo ($customer['gender'] == 'Female') ? 'checked' : ''; ?>> 女
                </label>
                <label>
                    <input type="radio" name="gender" value="Other" 
                    <?php echo ($customer['gender'] == 'Other') ? 'checked' : ''; ?>> 其他
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="company">公司名稱</label>
            <input type="text" id="company" name="company" 
                   value="<?php echo htmlspecialchars($customer['company'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="email">Email <span style="color:#E74C3C">*</span></label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo htmlspecialchars($customer['email']); ?>">
        </div>

        <div class="form-group">
            <label for="phone">聯絡電話</label>
            <input type="text" id="phone" name="phone" 
                   value="<?php echo htmlspecialchars($customer['phone'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="notes">備註</label>
            <textarea id="notes" name="notes" rows="4"><?php echo htmlspecialchars($customer['notes'] ?? ''); ?></textarea>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">更新資料</button>
            <a href="index.php" class="btn btn-secondary" style="margin-left: 10px;">取消</a>
        </div>

    </form>
</div>

<?php include 'includes/footer.php'; ?>