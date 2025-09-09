<?php
session_start();
include 'config.php';

// 处理注册
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "两次输入的密码不一致！";
    }

    // 检查用户是否已存在
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "此电子邮件已被注册，请使用其他邮箱。";
        $stmt->close();
    } else {
        // 插入新用户
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $_SESSION['success'] = "注册成功！请登录。";
            header('Location: login.php');
            exit();
        } else {
            $error = "注册失败: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>用户注册</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
            line-height: 1.6;
        }

        .register-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #45a049;
        }

        .error {
            color: #f44336;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        .success {
            color: #4CAF50;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #ddd;
            z-index: -1;
        }

        .divider span {
            background-color: #fff;
            padding: 0 15px;
            position: relative;
            z-index: 1;
            color: #777;
        }

        .social-register {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #f0f0f0;
            color: #555;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .social-btn:hover {
            background-color: #e0e0e0;
        }

        .social-btn i {
            font-size: 20px;
        }

        .social-btn.facebook i {
            color: #3b5998;
        }

        .social-btn.google i {
            color: #db4437;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="register-container">
        <h2>用户注册</h2>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <?php if (isset($success)) { ?>
            <p class="success"><?php echo $success; ?></p>
        <?php } ?>
        <form method="post">
            <div class="form-group">
                <label for="name">用户名:</label>
                <input type="text" id="name" name="name" placeholder="请输入您的用户名" required>
            </div>
            <div class="form-group">
                <label for="email">电子邮箱:</label>
                <input type="email" id="email" name="email" placeholder="请输入您的电子邮箱" required>
            </div>
            <div class="form-group">
                <label for="password">密码:</label>
                <input type="password" id="password" name="password" placeholder="请输入您的密码" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">确认密码:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="请再次输入您的密码" required>
            </div>
            <div class="form-group">
                <label for="role">用户角色:</label>
                <select id="role" name="role">
                    <option value="user">普通用户</option>
                    <option value="admin">管理员</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">注册</button>
            </div>
        </form>
        <div class="divider">
            <span>或者</span>
        </div>
        <div class="social-register">
            <a href="#" class="social-btn facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-btn google">
                <i class="fab fa-google"></i>
            </a>
        </div>
        <p style="text-align: center; margin-top: 20px;">已有账号? <a href="login.php">立即登录</a></p>
    </div>
</body>
</html>