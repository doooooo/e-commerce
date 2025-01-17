<?php
// تضمين ملف إعداد الاتصال بقاعدة البيانات
include 'config.php';
session_start();

// التحقق من حالة تسجيل الدخول
if (isset($_SESSION['user_id'])) {
    // إذا كان المستخدم مسجلاً دخولًا، إعادة توجيه إلى الصفحة الرئيسية (index.php)
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استلام المدخلات
    $email = $_POST['email'];
    $password = $_POST['password'];

    // التحقق من أن المدخلات ليست فارغة
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=empty_fields");
        exit;
    }

    // تحضير الاستعلام للتحقق من وجود المستخدم باستخدام email بدلاً من id
    $sql = "SELECT * FROM USER WHERE USERID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // استخدم email كمعامل
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // التحقق من كلمة المرور
        if (password_verify($password, $user['PASSWORD'])) { // استخدم password بدلاً من PASSWORD
            // تسجيل الدخول بنجاح
            $_SESSION['user_id'] = $user['ID'];          // تخزين id في الجلسة
            $_SESSION['user_name'] = $user['NAME'];  // تخزين username في الجلسة
            $_SESSION['user_email'] = $user['USERID']; 
            header("Location: index.php");
            exit;
        } else {
            // كلمة المرور غير صحيحة
            header("Location: login.php?error=invalid_password");
            exit;
        }
    } else {
        // المستخدم غير موجود
        header("Location: login.php?error=user_not_found");
        exit;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="stlowRe.css">
</head>
<body>
    <div class="auth-container">
        <h2 class="auth-title">Login Form</h2>

        <!-- عرض رسالة النجاح إذا تم تمريرها عبر الـ URL -->  <!-- عرض رسائل الأخطاء إذا تم تمريرها عبر الـ URL -->
        <?php if (isset($_GET['error'])): ?>
            <p class="auth-message error">
                <?php 
                    if ($_GET['error'] == 'empty_fields') {
                        echo "Please fill in both fields.";
                    } elseif ($_GET['error'] == 'invalid_password') {
                        echo "Incorrect password. Please try again.";
                    } elseif ($_GET['error'] == 'user_not_found') {
                        echo "User not found. Please check your email.";
                    }
                ?>
            </p>
        <?php endif; ?>

        <!-- نموذج تسجيل الدخول -->
        <form action="login.php" method="POST" class="auth-form">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
    
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
                
            <button type="submit" class="auth-button">Login</button>
        </form>
        <p class="auth-footer">Don't have an account? <a href="Register_PAGE.PHP">Register here</a></p>
    </div>
</body>
</html>
