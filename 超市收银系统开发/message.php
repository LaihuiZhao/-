<?php
session_start();
include 'config.php';
include 'auth.php';
checkAuth(); // 确保用户已登录

// 提交留言
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_message'])) {
    $user_id = $_SESSION['user_id'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO messages (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);

    if ($stmt->execute()) {
        $_SESSION['success'] = "留言提交成功！";
    } else {
        $_SESSION['error'] = "留言提交失败，请重试。";
    }
    header('Location: message.php');
    exit();
}

// 获取所有已通过审核的留言
$messages = $conn->query("SELECT m.*, u.name AS user_name FROM messages m JOIN users u ON m.user_id = u.id WHERE m.status = 1 ORDER BY m.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>留言页面</title>
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

        /* 留言表单样式 */
        .message-form {
            background-color: #f9f9f9;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            min-height: 120px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group textarea:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        .submit-btn {
            padding: 12px 25px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #388E3C;
        }

        /* 留言列表样式 */
        .messages-list {
            margin-top: 40px;
        }

        .message-item {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }

        .message-item:last-child {
            border-bottom: none;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .message-header h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .message-time {
            color: #777;
            font-size: 14px;
        }

        .message-content {
            margin-bottom: 10px;
            line-height: 1.6;
            color: #333;
        }

        /* 成功和错误消息样式 */
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }

        .success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }

        .error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
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
        <h1>留言</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="message success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- 留言表单 -->
        <form method="post" class="message-form">
            <div class="form-group">
                <label for="message">留言内容:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" name="submit_message" class="submit-btn">提交留言</button>
        </form>

        <!-- 留言列表 -->
        <div class="messages-list">
            <?php if ($messages->num_rows > 0): ?>
                <?php while ($message = $messages->fetch_assoc()): ?>
                    <div class="message-item">
                        <div class="message-header">
                            <h3><?php echo htmlspecialchars($message['user_name']); ?></h3>
                            <span class="message-time"><?php echo $message['created_at']; ?></span>
                        </div>
                        <div class="message-content">
                            <?php echo htmlspecialchars($message['content']); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; padding: 30px 0; color: #777;">暂无留言。</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>