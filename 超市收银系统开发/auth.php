<?php
// auth.php
function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "请先登录！";
        header('Location: login.php');
        exit();
    }
}