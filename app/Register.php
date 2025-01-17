<?php
// تضمين إعدادات الاتصال بقاعدة البيانات
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استلام بيانات النموذج
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // التحقق من أن البيانات ليست فارغة
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: register.html?error=All fields are required.");
        exit;
    }

    // التحقق من وجود البريد الإلكتروني مسبقًا
    $check_sql = "SELECT * FROM USER WHERE USERID = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // البريد الإلكتروني موجود بالفعل
        header("Location: register.html?error=Email already exists. Please use another email.");
        exit;
    } else {
        // تشفير كلمة المرور
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // إدخال المستخدم الجديد في قاعدة البيانات
        $sql = "INSERT INTO USER (NAME, USERID, PASSWORD) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            // إعادة التوجيه إلى صفحة تسجيل الدخول مع رسالة نجاح
            header("Location: login.php?success=Registration successful! Please log in.");
            exit;
        } else {
            header("Location: register.html?error=Failed to register. Please try again later.");
        }

        $stmt->close();
    }
}

include("./Register_PAGE.PHP");
$conn->close();
?>
