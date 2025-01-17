<?php
session_start(); // بدء الجلسة
session_unset(); // إزالة جميع المتغيرات في الجلسة
session_destroy(); // تدمير الجلسة
header("Location: login.php"); // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* تصميم صفحة الخروج */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .logout-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h2 {
            color: #5c5c5c;
        }

        .message {
            font-size: 1.2rem;
            color: #4caf50;
            margin: 20px 0;
        }

        .login-link {
            display: inline-block;
            background-color: #ff6347;
            color: white;
            padding: 12px 25px;
            font-size: 1rem;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .login-link:hover {
            background-color: #e53e36;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h2>You have successfully logged out!</h2>
        <p class="message">We hope to see you again soon.</p>
        <a href="login.php" class="login-link">Login Again</a>
    </div>
</body>
</html>
