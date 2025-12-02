<?php
require_once 'db/db_connect.php';
require_once 'includes/auth.php'; // 加入這行進行權限保護
require_once 'db/db_connect.php';

// 查詢所有客戶
$stmt = $pdo->query("SELECT * FROM customers ORDER BY created_at DESC");
$customers = $stmt->fetchAll();

// 性別顯示輔助函數
function displayGender($gender) {
    switch ($gender) {
        case 'Male': return '男';
        case 'Female': return '女';
        case 'Other': return '其他';
        default: return '-';
    }
}

include 'includes/header.php';
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">客戶列表</h2>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>姓名</th>
                    <th>性別</th>
                    <th>公司</th>
                    <th>Email</th>
                    <th>電話</th>
                    <th style="text-align:right;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($customers) > 0): ?>
                    <?php foreach ($customers as $client): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($client['name']); ?></strong></td>
                            <td><?php echo displayGender($client['gender']); ?></td>
                            <td><?php echo htmlspecialchars($client['company'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($client['email']); ?></td>
                            <td><?php echo htmlspecialchars($client['phone'] ?? ''); ?></td>
                            <td style="text-align:right;">
                                <a href="edit.php?id=<?php echo $client['id']; ?>" class="btn btn-secondary btn-sm" style="margin-right:5px;">編輯</a>
                                <a href="delete.php?id=<?php echo $client['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('確定要刪除這位客戶嗎？此動作無法復原。');">刪除</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 40px; color: #999;">
                            目前沒有客戶資料，請點擊上方新增。
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>