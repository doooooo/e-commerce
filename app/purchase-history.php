<?php
session_start();
include 'config.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

// استرجاع البريد الإلكتروني للمستخدم
$user_email = $_SESSION['user_email'];

// الحصول على user_id بناءً على البريد الإلكتروني
$sql = "SELECT ID FROM USER WHERE USERID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['ID'];
} else {
    echo "Error: User not found.";
    exit();
}

// التعامل مع إعادة الطلب عند الضغط على زر "Reorder"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reorder'])) {
    $product_name = $_POST['reorder'];

    // الحصول على product_id بناءً على اسم المنتج
    $sql_product = "SELECT ID FROM PRODUCT WHERE NAME = ?";
    $stmt_product = $conn->prepare($sql_product);
    $stmt_product->bind_param("s", $product_name);
    $stmt_product->execute();
    $product_result = $stmt_product->get_result();

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
        $product_id = $product['ID'];

        // إضافة المنتج إلى سلة التسوق
        $sql_cart = "INSERT INTO cart (user_id, product_id, quantity) 
                     VALUES (?, ?, 1)
                     ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        $stmt_cart = $conn->prepare($sql_cart);
        $stmt_cart->bind_param("ii", $user_id, $product_id);

        if ($stmt_cart->execute()) {
            echo "<script>alert('Product reordered successfully'); window.location.href='purchase-history.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt_cart->close();
    } else {
        echo "Error: Product not found.";
    }

    $stmt_product->close();
}

// استعلام للحصول على سجل المشتريات
$sql_purchases = "SELECT p.NAME AS product_name, ph.Qty, ph.Date, ph.Price 
                  FROM PURCHASES ph 
                  JOIN PRODUCT p ON ph.PRODUCTID = p.ID 
                  WHERE ph.USERID = ? 
                  ORDER BY ph.Date DESC";

$stmt_purchases = $conn->prepare($sql_purchases);
$stmt_purchases->bind_param("i", $user_id);
$stmt_purchases->execute();
$purchases = $stmt_purchases->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchases History</title>
    <link rel="stylesheet" href="purchases-history.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<section>
		<div class="navbar flex">
			<div class="flex">
				<img class="logo" src="../logo.jpeg"/>
				<h2>Admin Portal</h2>
			</div>
		  <div class="flex nav-options">
			<p><a class="menu" href="index.php">All Products</a></p>
			<p><a class="menu" href="new_product.html">Add Product</a></p>
		  </div>
		  <div class="flex cart">
			<a href="login.php?logout=true">
			  <i style="color:white;margin-right:20px;" class="fa-solid fa-sign-out" aria-hidden="true"></i></a>
			</div>
		</div>
	  </section>
    <div class="history-container">
        <h2 class="history-title">Your Purchases History</h2>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Date Purchased</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // echo 'userid '.$user_id;
                if ($purchases->num_rows > 0) {
                    while ($purchase = $purchases->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($purchase['product_name']) . "</td>
                                <td>$" . $purchase['Price'] . ".00</td> <!-- قد تحتاج لتعديل السعر حسب قاعدة البيانات -->
                                <td>" . htmlspecialchars($purchase['Date']) . "</td>
                                <td>
                                    <form action='purchase-history.php' method='POST'>
                                        <button type='submit' name='reorder' value='" . htmlspecialchars($purchase['product_name']) . "' class='reorder-button'>Reorder</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No purchases found in your history.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div>
        <a href="index.php" class="back-to-home">Back to Home</a>
    </div>
</body>
</html>

<?php
// إغلاق الاتصال
$stmt->close();
// $stmt_product->close();
// $stmt_cart->close();
$stmt_purchases->close();
$conn->close();
?>
