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

if (!$stmt->execute()) {
    $msg="Error in Checkout";
    $link="<br><a href='index.php'>Back to Home</a>";
}

else{
    $msg="Checkout completed successfully!";
    $link="<br><a href='purchase-history.php'>View purchases history</a>";
}

// echo "Checkout completed successfully!";
// echo "<br><br><a href='purchase-history.php'>View purchases history</a>";


$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <div class="navbar flex">
        <div><img class="logo" src="logo.jpeg"/></div>
      <div class="flex nav-options">
        <p><a href="index.php">Home</a></p>
        <p><a href="products.php">Products</a></p>
        <p><a href="Register.php">Register</a></p>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p><a href="logout.php">Logout</a></p>
        <?php else: ?>
            <p><a href="login.php">Login</a></p>
        <?php endif; ?>
      </div>
      <div></div>
    </div>
    <section>
      <div class="flex">
            <div style="margin: auto;margin-top: 30px;">
                <br>
                <?php 
                echo $msg;
                echo $link;
                ?>
            </div>
        </div>
    </section>
    </body>