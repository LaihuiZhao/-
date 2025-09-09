<?php
// 数据库配置
$servername = "localhost";
$username = "root";
$password = "root"; // 使用正确的数据库密码
$dbname = "chaoshi";

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " + $conn->connect_error);
}

// 设置字符集
$conn->set_charset("utf8mb4");