<?php
session_start();
include 'config.php';
include 'auth.php';
checkAuth();

// 初始化购物车
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 添加商品到购物车
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
    }

    header('Location: cart.php');
    exit();
}

// 更新购物车
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $qty) {
        if ($qty > 0) {
            $_SESSION['cart'][$product_id] = $qty;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    header('Location: cart.php');
    exit();
}

// 清空购物车
if (isset($_GET['clear_cart']) && $_GET['clear_cart'] == 'true') {
    $_SESSION['cart'] = [];
    header('Location: cart.php');
    exit();
}

// 移除购物车中的商品
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: cart.php');
    exit();
}

// 获取购物车商品
$cart_products = [];
$total = 0;

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        $product['quantity'] = $quantity;
        $product['subtotal'] = $product['price'] * $quantity;
        $cart_products[] = $product;
        $total += $product['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>购物车</title>
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

        /* 购物车表格样式 */
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-table th {
            background-color: #f5f5f5;
            text-align: left;
            padding: 15px;
            font-weight: 600;
            color: #333;
        }

        .cart-table td {
            padding: 15px;
            border-top: 1px solid #eee;
            vertical-align: middle;
        }

        /* 商品样式 */
        .product-info {
            display: flex;
            align-items: center;
        }

        .product-info img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }

        .product-details h3 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .product-details p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }

        /* 数量输入框样式 */
        .quantity-input {
            width: 60px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }

        /* 价格样式 */
        .price {
            color: #333;
            font-weight: 600;
        }

        .subtotal {
            color: #e53935;
            font-weight: 600;
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

        .remove-btn {
            background-color: #f44336;
            color: white;
        }

        .remove-btn:hover {
            background-color: #d32f2f;
        }

        /* 总计样式 */
        .cart-footer {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .total-price {
            color: #e53935;
        }

        /* 购物车操作按钮样式 */
        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .update-btn {
            background-color: #2196F3;
            color: white;
        }

        .update-btn:hover {
            background-color: #1976D2;
        }

        .clear-btn {
            background-color: #f44336;
            color: white;
        }

        .clear-btn:hover {
            background-color: #d32f2f;
        }

        .checkout-btn {
            background-color: #4CAF50;
            color: white;
        }

        .checkout-btn:hover {
            background-color: #388E3C;
        }

        /* 购物车为空时的样式 */
        .empty-cart {
            text-align: center;
            padding: 50px 0;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .empty-cart p {
            margin-bottom: 20px;
            font-size: 18px;
            color: #666;
        }

        .continue-shopping {
            padding: 12px 25px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .continue-shopping:hover {
            background-color: #388E3C;
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
        <h2>购物车</h2>
        <?php if (empty($cart_products)): ?>
            <div class="empty-cart">
                <p>购物车是空的。</p>
                <a href="products.php" class="continue-shopping">继续购物</a>
            </div>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>商品</th>
                        <th>价格</th>
                        <th>数量</th>
                        <th>小计</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_products as $product): ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="<?php echo $product['picture_url']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                    <div class="product-details">
                                        <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                                        <p>分类: <?php echo htmlspecialchars($product['category_name']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="price">¥<?php echo $product['price']; ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $product['id']; ?>]" value="<?php echo $product['quantity']; ?>" min="1" max="99" class="quantity-input">
                            </td>
                            <td class="subtotal">¥<?php echo number_format($product['subtotal'], 2); ?></td>
                            <td>
                                <form action="cart.php?action=remove&id=<?php echo $product['id']; ?>" method="post" style="display: inline-block;">
                                    <button type="submit" class="btn remove-btn">移除</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="cart-total">总计：</td>
                        <td class="cart-total total-price">¥<?php echo number_format($total, 2); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div class="cart-actions">
                <form method="post">
                    <button type="submit" name="update_cart" class="btn update-btn">更新购物车</button>
                </form>
                <form action="cart.php?clear_cart=true" method="post" style="display: inline-block;">
                    <button type="submit" class="btn clear-btn">清空购物车</button>
                </form>
                <a href="checkout.php" class="btn checkout-btn">结算</a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>