<?php
session_start();
include 'config.php';
include 'auth.php';
checkAuth(); // 确保用户已登录

// 获取所有商品
$products = $conn->query("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>商品列表</title>
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

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .product-card h3 {
            margin-bottom: 10px;
        }

        .product-card p {
            margin-bottom: 5px;
        }

        .product-card .price {
            font-weight: bold;
            color: #e53935;
            margin-bottom: 15px;
        }

        .product-card button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .product-card button:hover {
            background-color: #45a049;
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
        <h2>商品列表</h2>
        <div class="products-grid">
            <?php while ($product = $products->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="<?php echo $product['picture_url']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                    <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                    <p>分类: <?php echo htmlspecialchars($product['category_name']); ?></p>
                    <p class="price">¥<?php echo number_format($product['price'], 2); ?></p>
                    <form method="post" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="add_to_cart">加入购物车</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>