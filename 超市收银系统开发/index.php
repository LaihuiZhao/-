<?php
session_start();
include 'config.php';
include 'auth.php';
checkAuth();
?>

<!DOCTYPE html>
<html>
<head>
    <title>首页</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        .container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .welcome-message {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .logout-btn, .enter-supermarket-btn, .admin-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
            text-decoration: none;
        }

        .logout-btn:hover, .enter-supermarket-btn:hover, .admin-btn:hover {
            background-color: #45a049;
        }

        /* 导航栏样式 */
        header {
            background-color: #fff;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border-radius: 8px;
        }

        nav ul {
            display: flex;
            list-style: none;
            justify-content: center;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">首页</a></li>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <li><a href="admin.php">管理</a></li>
                <?php else: ?>
                    <li><a href="products.php">商品列表</a></li>
                    <li><a href="cart.php">购物车</a></li>
                    <li><a href="message.php">留言</a></li>
                    <li><a href="news.php">新闻公示</a></li>
                <?php endif; ?>
                <li><a href="logout.php">退出</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>欢迎回来</h2>
        <p class="welcome-message">欢迎，<?php echo $_SESSION['user_name']; ?>！您已成功登录。</p>
        <a href="login.php?action=logout" class="logout-btn">退出登录</a>
        <?php if ($_SESSION['user_role'] === 'user'): ?>
            <a href="products.php" class="enter-supermarket-btn">进入超市系统</a>
        <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
            <a href="admin.php" class="admin-btn">进入管理系统</a>
        <?php endif; ?>
    </div>
</body>
</html>