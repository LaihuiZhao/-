<?php
// logout.php
session_start();
session_unset();
session_destroy();
$_SESSION['success'] = "您已成功退出登录！";
header('Location: login.php'); // 退出后跳转到登录页
exit();