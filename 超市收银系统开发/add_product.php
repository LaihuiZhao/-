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

$error = '';

// 添加新商品
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $tag = $_POST['tag'] ?? '';
    $picture_url = $_FILES['picture_url'] ?? null;

    // 验证文件类型和大小
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if ($picture_url && !in_array($picture_url['type'], $allowed_types)) {
        $error = "请上传有效的图片文件（JPEG, PNG, GIF）。";
    }
    if ($picture_url && $picture_url['size'] > 2 * 1024 * 1024) {
        $error = "图片文件大小不能超过 2MB。";
    }

    if (empty($error)) {
        $target_dir = __DIR__ . '/../2023/Images/'; // 使用相对路径
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $relative_path = '';
        if ($picture_url && $picture_url['error'] === UPLOAD_ERR_OK) {
            $target_file = $target_dir . basename($picture_url['name']);
            if (move_uploaded_file($picture_url['tmp_name'], $target_file)) {
                // 存储图片的相对路径到数据库
                $relative_path = "/2023/Images/" . basename($picture_url['name']);
            } else {
                $error = "图片上传失败，请重试。";
            }
        } else {
            // 使用默认图片的相对路径
            $relative_path = '/2023/Images/default.jpg';
        }

        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO products (title, description, price, picture_url, category_id, tag) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdss", $title, $description, $price, $relative_path, $category_id, $tag);

            if ($stmt->execute()) {
                $_SESSION['success'] = "商品添加成功！";
                header('Location: admin.php');
                exit();
            } else {
                error_log("Database error: " . $conn->error);
                $error = "商品添加失败，请重试。";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>添加商品</title>
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
            justify-content: center; /* 居中显示 */
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            text-decoration: none; /* 去掉下划线 */
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #4CAF50;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .admin-form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

        .form-group input, .form-group textarea, .form-group select {
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

        .error-message {
            color: red;
            margin-bottom: 20px;
            text-align: center;
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
        <h1>添加商品</h1>
        <div class="admin-form-container">
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">商品名称:</label>
                    <input type="text" id="title" name="title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">商品描述:</label>
                    <textarea id="description" name="description" rows="5" required><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">价格:</label>
                    <input type="number" id="price" name="price" value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="category_id">分类:</label>
                    <select id="category_id" name="category_id" required>
                        <?php 
                        $categories = $conn->query("SELECT * FROM categories");
                        while ($category = $categories->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo isset($category_id) && $category_id == $category['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tag">标签:</label>
                    <input type="text" id="tag" name="tag" value="<?php echo isset($tag) ? htmlspecialchars($tag) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="picture_url">图片:</label>
                    <input type="file" id="picture_url" name="picture_url" accept="image/*" required>
                </div>
                <button type="submit" class="submit-btn">添加商品</button>
            </form>
        </div>
    </main>
</body>
</html>