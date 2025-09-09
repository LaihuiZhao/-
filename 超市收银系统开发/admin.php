<?php
session_start();
include 'config.php';
include 'auth.php';
checkAuth();

// 检查是否为管理员
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// 删除商品
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "商品删除成功！";
    } else {
        $_SESSION['error'] = "删除失败，请重试。";
    }
    header('Location: admin.php');
    exit();
}

// 审核留言
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_message'])) {
    $message_id = $_POST['message_id'];
    $stmt = $conn->prepare("UPDATE messages SET status = 1 WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "留言审核通过！";
    } else {
        $_SESSION['error'] = "审核失败，请重试。";
    }
    header('Location: admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reject_message'])) {
    $message_id = $_POST['message_id'];
    $stmt = $conn->prepare("UPDATE messages SET status = 2 WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "留言拒绝成功！";
    } else {
        $_SESSION['error'] = "拒绝失败，请重试。";
    }
    header('Location: admin.php');
    exit();
}

// 发布新闻
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish_news'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO news (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);

    if ($stmt->execute()) {
        $_SESSION['success'] = "新闻发布成功！";
    } else {
        $_SESSION['error'] = "新闻发布失败，请重试。";
    }
    header('Location: admin.php');
    exit();
}

// 获取所有商品
$products = $conn->query("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id");

// 获取所有待审核留言
$pending_messages = $conn->query("SELECT m.*, u.name AS user_name FROM messages m JOIN users u ON m.user_id = u.id WHERE m.status = 0");
?>

<!DOCTYPE html>
<html>
<head>
    <title>管理页面</title>
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
            max-width: 1200px;
            margin: 0 auto;
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

        /* 管理表格样式 */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .admin-table th {
            background-color: #f5f5f5;
            text-align: left;
            padding: 15px;
            font-weight: 600;
            color: #333;
        }

        .admin-table td {
            padding: 15px;
            border-top: 1px solid #eee;
            vertical-align: middle;
        }

        /* 操作按钮样式 */
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }

        .approve-btn {
            background-color: #4CAF50;
            color: white;
        }

        .approve-btn:hover {
            background-color: #388E3C;
        }

        .reject-btn {
            background-color: #FFC107;
            color: white;
        }

        .reject-btn:hover {
            background-color: #FFA000;
        }

        .add-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            display: block;
            margin: 0 auto;
            text-decoration: none;
        }

        .add-btn:hover {
            background-color: #388E3C;
        }

        /* 新闻表单样式 */
        .news-form {
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

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
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
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">首页</a></li>
                <li><a href="admin.php">管理</a></li>
                <li><a href="logout.php">退出</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <h1>管理页面</h1>

        <!-- 添加新闻 -->
        <h2 style="margin-top: 50px;">发布新闻</h2>
        <form method="post" class="news-form">
            <div class="form-group">
                <label for="title">新闻标题:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">新闻内容:</label>
                <textarea id="content" name="content" rows="5" required></textarea>
            </div>
            <button type="submit" name="publish_news" class="submit-btn">发布新闻</button>
        </form>

        <!-- 管理商品 -->
        <h2 style="margin-top: 50px;">管理商品</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>分类</th>
                    <th>价格</th>
                    <th>标签</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['title']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                        <td>¥<?php echo $product['price']; ?></td>
                        <td><?php echo $product['tag'] ?? ''; ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" name="delete_product" class="delete-btn">删除</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="add_product.php" class="add-btn">添加新商品</a>

        <!-- 审核留言 -->
        <h2 style="margin-top: 50px;">待审核留言</h2>
        <?php if ($pending_messages->num_rows > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>用户</th>
                        <th>内容</th>
                        <th>时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($message = $pending_messages->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $message['id']; ?></td>
                            <td><?php echo htmlspecialchars($message['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($message['content']); ?></td>
                            <td><?php echo $message['created_at']; ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                    <button type="submit" name="approve_message" class="approve-btn">通过</button>
                                    <button type="submit" name="reject_message" class="reject-btn">拒绝</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; padding: 30px 0; color: #777;">暂无待审核留言。</p>
        <?php endif; ?>
    </main>
</body>
</html>