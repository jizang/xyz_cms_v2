<?php
require_once 'includes/auth.php';
require_once 'db/db_connect.php';

// 取得所有管理員
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 style="margin:0;">管理員帳號</h2>
        <a href="user_create.php" class="btn btn-primary btn-sm">+ 新增帳號</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>帳號 (Username)</th>
                    <th>真實姓名</th>
                    <th>建立時間</th>
                    <th style="text-align:right;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($u['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($u['real_name']); ?></td>
                        <td><?php echo $u['created_at']; ?></td>
                        <td style="text-align:right;">
                            <!-- 編輯按鈕 (所有人都可以編輯) -->
                            <a href="user_edit.php?id=<?php echo $u['id']; ?>" 
                               class="btn btn-secondary btn-sm" 
                               style="margin-right: 5px;">編輯</a>

                            <!-- 刪除按鈕 (不能刪除自己) -->
                            <?php if($u['id'] != $_SESSION['user_id']): ?>
                                <a href="user_delete.php?id=<?php echo $u['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('確定要刪除此帳號嗎？');">刪除</a>
                            <?php else: ?>
                                <span class="btn btn-sm" style="visibility: hidden;">刪除</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>