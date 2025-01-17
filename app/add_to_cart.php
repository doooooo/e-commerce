<?php
session_start();
include 'config.php';

// تحقق من تسجيل الدخول
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    // echo 'email is not set';
    exit();
}

// التحقق من product_id
if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
    header('Location: index.php?error=InvalidProductID');
    // echo 'not set product_id';
    exit();
}

$product_id = intval($_POST['product_id']); // حماية من الإدخال غير الصحيح
$user_email = $_SESSION['user_email'];

// جلب user_id بناءً على email
$sql = "SELECT ID FROM USER WHERE USERID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['ID'];
} else {
    header('Location: index.php?error=UserNotFound');
    // echo 'user not found';
    exit();
}
// echo 'user_id '.$user_id;
// إدخال المنتج إلى السلة
$sql = "INSERT INTO cart (user_id, product_id, quantity) 
        VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE quantity = quantity + 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);

if ($stmt->execute()) {
    header('Location: cart.php?success=ProductAdded');
    //  echo 'added product to cart';
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
