<?php
include 'config.php';
include 'auth.php';

// 获取所有新闻
$news = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>新闻公示</title>
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
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        /* 页面标题样式 */
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        /* 新闻列表样式 */
        .news-list {
            margin-top: 40px;
        }

        .news-item {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }

        .news-item:last-child {
            border-bottom: none;
        }

        .news-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .news-header h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .news-time {
            color: #777;
            font-size: 14px;
        }

        .news-content {
            margin-bottom: 10px;
            line-height: 1.6;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">首页</a></li>
                <li><a href="products.php">商品列表</a></li>
                <li><a href="cart.php">购物车</a></li>
                <li><a href="message.php">留言</a></li>
                <li><a href="news.php">新闻公示</a></li>
				<li><a href="logout.php">退出</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <h1>新闻公示</h1>
        
        <div class="news-list">
            <?php if ($news->num_rows > 0): ?>
                <?php while ($new = $news->fetch_assoc()): ?>
                    <div class="news-item">
                        <div class="news-header">
                            <h3><?php echo htmlspecialchars($new['title']); ?></h3>
                            <span class="news-time"><?php echo $new['created_at']; ?></span>
                        </div>
                        <div class="news-content">
                            <?php echo htmlspecialchars($new['content']); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; padding: 30px 0; color: #777;">暂无新闻。</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>