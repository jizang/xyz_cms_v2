<?php 
require_once 'includes/auth.php';
require_once 'db/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user = null;

// 撈取使用者資料
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch();
}

if (!$user) {
    die("找不到該使用者。 <a href='users.php'>返回列表</a>");
}

include 'includes/header.php'; 
?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h2 style="margin-top:0; margin-bottom: 24px;">編輯管理員資料</h2>
    
    <form action="user_save.php" method="POST">
        <!-- 傳送 hidden ID 以便後端識別是更新模式 -->
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <div class="form-group">
            <label>帳號 (不可修改)</label>
            <!--設定 readonly 屬性並加上背景色樣式-->
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly style="background-color: #eee; cursor: not-allowed;">
        </div>

        <div class="form-group">
            <label>真實姓名</label>
            <input type="text" name="real_name" value="<?php echo htmlspecialchars($user['real_name']); ?>">
        </div>

        <div class="form-group">
            <label>密碼變更</label>
            <input type="password" name="password" placeholder="若不修改密碼，請留空">
            <p style="font-size: 0.85rem; color: #95A5A6; margin-top: 5px;">
                提示：若欄位留空，將保留原本密碼。
            </p>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">更新帳號</button>
            <a href="users.php" class="btn btn-secondary" style="margin-left: 10px;">取消</a>
        </div>

    </form>
</div>

<?php include 'includes/footer.php'; ?>