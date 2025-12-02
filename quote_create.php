<?php 
require_once 'includes/auth.php';
require_once 'db/db_connect.php';

$customers = $pdo->query("SELECT id, name FROM customers ORDER BY name ASC")->fetchAll();
$autoNum = 'Q-' . date('Ymd') . '-' . rand(100,999);

include 'includes/header.php'; 
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 style="margin-top:0; margin-bottom: 10px;">建立新報價單</h2>
    <p style="color:#95A5A6; margin-bottom: 24px;">第一步：請先建立報價單基本資訊，儲存後即可新增報價細項。</p>
    
    <form action="quote_save.php" method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>報價單號 <span style="color:#E74C3C">*</span></label>
                <input type="text" name="quote_number" value="<?php echo $autoNum; ?>" required>
            </div>
            <div class="form-group">
                <label>選擇客戶 <span style="color:#E74C3C">*</span></label>
                <select name="customer_id" required>
                    <option value="">-- 請選擇客戶 --</option>
                    <?php foreach($customers as $c): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>報價主題 <span style="color:#E74C3C">*</span></label>
            <input type="text" name="subject" required placeholder="例如：年度網站維護合約">
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>狀態</label>
                <select name="status">
                    <option value="Draft">Draft (草稿)</option>
                    <option value="Sent">Sent (已發送)</option>
                    <option value="Accepted">Accepted (已成交)</option>
                    <option value="Rejected">Rejected (已拒絕)</option>
                </select>
            </div>
            <div class="form-group">
                <label>有效期限</label>
                <input type="date" name="valid_until">
            </div>
        </div>
        <div class="form-group">
            <label>備註 / 條款</label>
            <textarea name="details" rows="3"></textarea>
        </div>
        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">下一步：新增細項</button>
            <a href="quotes.php" class="btn btn-secondary" style="margin-left: 10px;">取消</a>
        </div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>