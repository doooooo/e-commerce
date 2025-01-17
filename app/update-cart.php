<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// التحقق من تسجيل الدخول
// if (isset($_SESSION['user_email'])) {
//     echo "User is logged in: " . $_SESSION['user_email'];
// } else {
//     echo "User is not logged in.";
// }
// exit();

// التحقق من وجود البيانات المطلوبة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity']) && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity < 1) {
        echo json_encode(['success' => false, 'error' => 'Invalid quantity.']);
        exit();
    }

    // جلب user_id من email
    $user_email = $_SESSION['user_email'];
    $sql = "SELECT ID FROM USER WHERE USERID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['ID'];
    } else {
        echo json_encode(['success' => false, 'error' => 'User not found.']);
        exit();
    }

    // تحديث الكمية في السلة
    $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $user_id, $product_id);

    if ($stmt->execute()) {
        // إعادة حساب الإجمالي
        $sql = "
            SELECT SUM(p.Price * c.quantity) AS total_price
            FROM cart c
            JOIN PRODUCT p ON c.product_id = p.ID
            WHERE c.user_id = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $total = $result->fetch_assoc();

        echo json_encode(['success' => true, 'total_price' => $total['total_price']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update cart.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
