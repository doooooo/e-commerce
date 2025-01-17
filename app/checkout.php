<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT c.product_id, c.quantity, p.Price FROM cart c JOIN PRODUCT p ON c.product_id = p.ID WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result();

$total_price = 0;
while ($item = $cart_items->fetch_assoc()) {
    $total_price += $item['Price'] * $item['quantity'];
}

// Add the order to the orders table
// $sql = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("id", $user_id, $total_price);
// $stmt->execute();

// Move items from cart to purchase history
// $sql = "INSERT INTO PURCHASES (USERID, PRODUCTID, Qty) 
//         SELECT user_id, product_id, quantity FROM cart WHERE user_id = ?";
$sql = "INSERT INTO PURCHASES (USERID, PRODUCTID, Qty, Date, Price)
        SELECT c.user_id, c.product_id, c.quantity, NOW(), (p.Price * c.quantity)
        FROM cart c 
        JOIN PRODUCT p ON c.product_id = p.ID 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Delete items from the cart
$sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

echo "Checkout completed successfully!";
echo "<br><br><a href='purchase-history.php'>View purchases history</a>";
$stmt->close();
$conn->close();
?>
