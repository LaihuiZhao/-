<?php
include 'config.php';
include 'auth.php';

$news_id = $_GET['id'] ?? null;
if (!$news_id) {
    header('Location: news.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $news_id);
$stmt->execute();
$news = $stmt->get_result()->fetch_assoc();

if (!$news) {
    header('Location: news.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>新闻详情</title>
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
            margin: 0 20px;
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

        .news-header {
            margin-bottom: 20px;
        }

        .news-title {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .news-date {
            color: #777;
            font-size: 14px;
        }

        .news-content {
            line-height: 1.6;
            color: #333;
        }

        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #f5f5f5;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #e0e0e0;
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
                <li><a href="logout.php">退出</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <h1>新闻详情</h1>
        
        <div class="news-header">
            <h2 class="news-title"><?php echo htmlspecialchars($news['title']); ?></h2>
            <p class="news-date"><?php echo $news['created_at']; ?></p>
        </div>

        <div class="news-content">
            <?php echo htmlspecialchars($news['content']); ?>
        </div>

        <a href="news.php" class="back-btn">返回新闻列表</a>
    </main>
</body>
</html>