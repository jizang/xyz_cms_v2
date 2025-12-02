<?php
session_start();

// 如果 session 中沒有 user_id，代表未登入
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>