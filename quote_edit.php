<?php 
require_once 'includes/auth.php';
require_once 'db/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$quote = null;

// 1. 撈取主報價單
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM quotes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $quote = $stmt->fetch();
}
if (!$quote) die("找不到該報價單。");

// 2. 撈取客戶列表
$customers = $pdo->query("SELECT id, name FROM customers ORDER BY name ASC")->fetchAll();

// 3. 撈取細項 (Items)
$stmtItems = $pdo->prepare("SELECT * FROM quote_items WHERE quote_id = ? ORDER BY sort_order ASC, id ASC");
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll();

// 4. 判斷是否為「編輯細項模式」
// 如果 URL 有傳入 item_id，代表使用者想編輯某一列，我們要把資料撈出來填入下方的表單
$editItem = null;
if (isset($_GET['item_id'])) {
    $item_id = (int)$_GET['item_id'];
    $stmtEdit = $pdo->prepare("SELECT * FROM quote_items WHERE id = ? AND quote_id = ?");
    $stmtEdit->execute([$item_id, $id]);
    $editItem = $stmtEdit->fetch();
}

include 'includes/header.php'; 
?>

<div class="container">
    
    <!-- 區塊 A: 主報價單資訊 -->
    <div class="card" style="margin-bottom: 30px; border-left: 5px solid #5D6D7E;">
        <h3 style="margin-top:0; color: #5D6D7E;">主報價單資訊</h3>
        <form action="quote_save.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $quote['id']; ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 2fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="font-size:0.85rem; color:#999;">單號</label>
                    <input type="text" name="quote_number" value="<?php echo htmlspecialchars($quote['quote_number']); ?>" required>
                </div>
                <div>
                    <label style="font-size:0.85rem; color:#999;">客戶</label>
                    <select name="customer_id" required>
                        <?php foreach($customers as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo ($c['id'] == $quote['customer_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.85rem; color:#999;">主題</label>
                    <input type="text" name="subject" value="<?php echo htmlspecialchars($quote['subject']); ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div>
                    <label style="font-size:0.85rem; color:#999;">狀態</label>
                    <select name="status">
                        <?php foreach(['Draft','Sent','Accepted','Rejected'] as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo ($s == $quote['status']) ? 'selected' : ''; ?>><?php echo $s; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.85rem; color:#999;">有效期限</label>
                    <input type="date" name="valid_until" value="<?php echo $quote['valid_until']; ?>">
                </div>
                <div style="text-align: right; padding-top: 24px;">
                    <button type="submit" class="btn btn-primary btn-sm">更新主檔資訊</button>
                    <a href="quotes.php" class="btn btn-secondary btn-sm">返回列表</a>
                </div>
            </div>
            
            <div class="form-group" style="margin-top:15px;">
                <label style="font-size:0.85rem; color:#999;">備註條款</label>
                <textarea name="details" rows="2"><?php echo htmlspecialchars($quote['details']); ?></textarea>
            </div>
        </form>
    </div>

    <!-- 區塊 B: 報價細項列表 -->
    <div class="card">
        <h3 style="margin-top:0; color: #5D6D7E;">報價細項</h3>
        
        <div class="table-responsive">
            <table style="width: 100%;">
                <thead style="background: #F8F9F9;">
                    <tr>
                        <th style="width: 40%;">項目描述</th>
                        <th style="width: 15%;">單價</th>
                        <th style="width: 10%;">數量</th>
                        <th style="width: 15%;">小計</th>
                        <th style="width: 20%; text-align:right;">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_amount = 0;
                    if (count($items) > 0): 
                        foreach ($items as $item): 
                            $total_amount += $item['subtotal'];
                    ?>
                        <tr style="<?php echo ($editItem && $editItem['id'] == $item['id']) ? 'background-color: #FEF9E7;' : ''; ?>">
                            <td><?php echo htmlspecialchars($item['description']); ?></td>
                            <td>$<?php echo number_format($item['unit_price']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td style="font-weight:bold; color:#2E86C1;">$<?php echo number_format($item['subtotal']); ?></td>
                            <td style="text-align:right;">
                                <!-- 編輯連結：點擊後會重新載入頁面，並將資料帶入下方的表單 -->
                                <a href="quote_edit.php?id=<?php echo $id; ?>&item_id=<?php echo $item['id']; ?>#item-form" 
                                   class="btn btn-secondary btn-sm" style="font-size: 0.75rem;">修改</a>
                                
                                <a href="quote_item_action.php?action=delete&item_id=<?php echo $item['id']; ?>&quote_id=<?php echo $id; ?>" 
                                   class="btn btn-danger btn-sm" style="font-size: 0.75rem;"
                                   onclick="return confirm('確定移除此項目？');">移除</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" style="text-align:center; color:#999; padding:20px;">尚無細項資料</td></tr>
                    <?php endif; ?>
                    
                    <!-- 總計列 -->
                    <tr style="border-top: 2px solid #BDC3C7; background: #FDFEFE;">
                        <td colspan="3" style="text-align:right; padding:15px; font-weight:bold;">總金額 Total:</td>
                        <td style="font-size: 1.2rem; font-weight:bold; color:#E74C3C;">
                            $<?php echo number_format($total_amount); ?>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr style="border: 0; border-top: 1px dashed #DDD; margin: 30px 0;">

        <!-- 區塊 C: 新增/編輯細項表單 -->
        <div id="item-form" style="background: #FAFAFA; padding: 20px; border-radius: 6px;">
            <h4 style="margin-top:0; color:#7F8C8D;">
                <?php echo $editItem ? '編輯項目' : '新增項目'; ?>
            </h4>
            
            <form action="quote_item_action.php" method="POST">
                <input type="hidden" name="quote_id" value="<?php echo $id; ?>">
                <!-- 如果是編輯模式，需傳送 item_id -->
                <?php if($editItem): ?>
                    <input type="hidden" name="item_id" value="<?php echo $editItem['id']; ?>">
                <?php endif; ?>

                <div style="display: grid; grid-template-columns: 3fr 1fr 1fr 1fr; gap: 15px; align-items: end;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>項目描述</label>
                        <input type="text" name="description" required placeholder="例如：首頁視覺設計" 
                               value="<?php echo $editItem ? htmlspecialchars($editItem['description']) : ''; ?>">
                    </div>
                    
                    <div class="form-group" style="margin-bottom:0;">
                        <label>單價</label>
                        <input type="number" name="unit_price" required min="0" step="1" placeholder="0"
                               value="<?php echo $editItem ? (float)$editItem['unit_price'] : ''; ?>">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label>數量</label>
                        <input type="number" name="quantity" required min="1" step="0.5" value="<?php echo $editItem ? (float)$editItem['quantity'] : '1'; ?>">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            <?php echo $editItem ? '保存修改' : '+ 加入項目'; ?>
                        </button>
                    </div>
                </div>
                
                <?php if($editItem): ?>
                    <div style="margin-top: 10px; text-align: right;">
                        <a href="quote_edit.php?id=<?php echo $id; ?>#item-form" style="color:#999; font-size:0.85rem; text-decoration:underline;">取消編輯，改為新增模式</a>
                    </div>
                <?php endif; ?>
            </form>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>