<?php
session_start();
include 'config.php';

// 处理登录
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'] ?? 'user';
            header('Location: index.php');
            exit();
        } else {
            $error = "密码错误！";
        }
    } else {
        $error = "用户不存在！";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>用户登录</title>
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

        .login-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
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

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        .form-group input:focus {
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

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .register-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
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

        .social-login {
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
    <div class="login-container">
        <h2>用户登录</h2>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <form method="post">
            <div class="form-group">
                <label for="email">电子邮箱:</label>
                <input type="email" id="email" name="email" placeholder="请输入您的电子邮箱" required>
            </div>
            <div class="form-group">
                <label for="password">密码:</label>
                <input type="password" id="password" name="password" placeholder="请输入您的密码" required>
            </div>
            <div class="form-group">
                <button type="submit">登录</button>
            </div>
        </form>
        <div class="divider">
            <span>或者</span>
        </div>
        <div class="social-login">
            <a href="#" class="social-btn facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-btn google">
                <i class="fab fa-google"></i>
            </a>
        </div>
        <p class="register-link">没有账号？<a href="register.php">立即注册</a></p>
    </div>
</body>
</html>