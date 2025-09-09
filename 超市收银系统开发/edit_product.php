<?php
include 'config.php';
include 'auth.php';
checkAuth();

// 获取商品ID
$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    header('Location: admin.php');
    exit();
}

// 获取商品信息
$stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header('Location: admin.php');
    exit();
}

// 更新商品
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $tag = $_POST['tag'] ?? '';
    $picture_url = $_FILES['picture_url']['name'];

    // 上传新图片
    if ($picture_url) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($picture_url);
        move_uploaded_file($_FILES["picture_url"]["tmp_name"], $target_file);
    } else {
        $target_file = $product['picture_url'];
    }

    $stmt = $conn->prepare("UPDATE products SET title = ?, description = ?, price = ?, picture_url = ?, category_id = ?, tag = ? WHERE id = ?");
    $stmt->bind_param("sssdssi", $title, $description, $price, $target_file, $category_id, $tag, $product_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "商品更新成功！";
        header('Location: admin.php');
        exit();
    } else {
        $error = "商品更新失败，请重试。";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>编辑商品</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>编辑商品</h1>
        <nav>
            <ul>
                <li><a href="index.php">首页</a></li>
                <li><a href="admin.php">管理</a></li>
                <li><a href="logout.php">退出</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="admin-form-container">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">商品名称:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">商品描述:</label>
                    <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">价格:</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="category_id">分类:</label>
                    <select id="category_id" name="category_id" required>
                        <?php 
                        $categories = $conn->query("SELECT * FROM categories");
                        while ($category = $categories->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tag">标签:</label>
                    <input type="text" id="tag" name="tag" value="<?php echo htmlspecialchars($product['tag'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="picture_url">图片:</label>
                    <input type="file" id="picture_url" name="picture_url" accept="image/*">
                    <img src="<?php echo $product['picture_url']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" style="max-width: 200px; margin-top: 10px;">
                </div>
                <button type="submit">更新商品</button>
            </form>
        </div>
    </main>
</body>
</html>