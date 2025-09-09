<?php
include 'config.php';
include 'auth.php';
checkAuth();

// 检查是否为管理员
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// 获取所有留言
$messages = $conn->query("SELECT m.*, u.name AS user_name FROM messages m JOIN users u ON m.user_id = u.id ORDER BY m.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>管理员留言管理</title>
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
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header {
            margin-bottom: 20px;
        }

        nav ul {
            display: flex;
            list-style: none;
        }

        nav ul li {
            margin-right: 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
        }

        .message-table {
            width: 100%;
            border-collapse: collapse;
        }

        .message-table th, .message-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        .message-table th {
            background-color: #f5f5f5;
        }

        .message-table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .message-content {
            white-space: pre-line;
        }

        .message-actions {
            display: flex;
            gap: 10px;
        }

        .btn-reply, .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-reply {
            background-color: #2196F3;
            color: white;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

        .message-form {
            margin-top: 30px;
            display: none; /* 默认隐藏回复表单 */
        }

        .message-form.active {
            display: block;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">首页</a></li>
                <li><a href="admin.php">管理商品</a></li>
                <li><a href="admin_message.php">管理留言</a></li>
                <li><a href="logout.php">退出</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <h2>留言管理</h2>

        <table class="message-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>用户</th>
                    <th>留言内容</th>
                    <th>留言时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($message = $messages->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $message['id']; ?></td>
                        <td><?php echo htmlspecialchars($message['user_name']); ?></td>
                        <td class="message-content"><?php echo nl2br(htmlspecialchars($message['content'])); ?></td>
                        <td><?php echo $message['created_at']; ?></td>
                        <td>
                            <button class="btn-reply" onclick="showReplyForm(<?php echo $message['id']; ?>)">回复</button>
                            <button class="btn-delete">删除</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <script>
        function showReplyForm(messageId) {
            // 显示回复表单的逻辑
            alert('显示回复表单：留言ID=' + messageId);
            // 实际项目中，这里可以通过操作DOM来显示对应的回复表单
        }
    </script>
</body>
</html>