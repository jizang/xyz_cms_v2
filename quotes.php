<?php
require_once 'includes/auth.php';
require_once 'db/db_connect.php';

// 關聯查詢：取出報價單並連結客戶姓名
$sql = "SELECT q.*, c.name as customer_name 
        FROM quotes q 
        JOIN customers c ON q.customer_id = c.id 
        ORDER BY q.created_at DESC";
$stmt = $pdo->query($sql);
$quotes = $stmt->fetchAll();

// 狀態標籤樣式輔助函數
function getStatusBadge($status) {
    $colors = [
        'Draft' => '#95A5A6',    // 灰
        'Sent' => '#3498DB',     // 藍
        'Accepted' => '#27AE60', // 綠
        'Rejected' => '#E74C3C'  // 紅
    ];
    $color = $colors[$status] ?? '#95A5A6';
    return "<span style='background:{$color}; color:white; padding:4px 8px; border-radius:4px; font-size:0.8rem;'>{$status}</span>";
}

include 'includes/header.php';
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">報價單列表</h2>
        <a href="quote_create.php" class="btn btn-primary btn-sm">+ 新增報價</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>單號</th>
                    <th>客戶名稱</th>
                    <th>主題</th>
                    <th>金額</th>
                    <th>狀態</th>
                    <th>有效期限</th>
                    <th style="text-align:right;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($quotes) > 0): ?>
                    <?php foreach ($quotes as $q): ?>
                        <tr>
                            <td style="font-family:monospace; color:#666;"><?php echo htmlspecialchars($q['quote_number']); ?></td>
                            <td><strong><?php echo htmlspecialchars($q['customer_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($q['subject']); ?></td>
                            <td>$<?php echo number_format($q['amount']); ?></td>
                            <td><?php echo getStatusBadge($q['status']); ?></td>
                            <td><?php echo $q['valid_until'] ? $q['valid_until'] : '-'; ?></td>
                            <td style="text-align:right;">
                                <a href="quote_edit.php?id=<?php echo $q['id']; ?>" class="btn btn-secondary btn-sm" style="margin-right:5px;">編輯</a>
                                <a href="quote_delete.php?id=<?php echo $q['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('確定要刪除這張報價單嗎？');">刪除</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center; padding:30px; color:#999;">目前沒有報價單</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>