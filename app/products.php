<?php
session_start();
include 'config.php'; // ملف الاتصال بقاعدة البيانات

// استعلام لجلب جميع المنتجات من قاعدة البيانات
$query = "SELECT * FROM PRODUCT";  // تأكد أن جدول المنتجات يسمى 'products'
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="stylePRO.css">
</head>
<body>
    <div class="products-container">
        <h2 class="products-title">Our Products</h2>
        <div class="product-list">
            <?php
            // التحقق من وجود منتجات في قاعدة البيانات
            if ($result->num_rows > 0) {
                // عرض كل منتج
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-card">
                            <img src="' . $row['Photo'] . '" alt="' . $row['NAME'] . '">
                            <h3>' . $row['NAME'] . '</h3>
                            <p class="product-price">$' . $row['price'] . '</p>
                            <form action="add_to_cart.php" method="POST" style="display: inline;">
                                <input type="hidden" name="product_id" value="' . $row['ID'] . '"> <!-- ID المنتج -->
                                <button type="submit" class="add">Add to Cart</button>
                            </form>
                        </div>';
                }
            } else {
                echo "<p>No products found.</p>";
            }
            ?>
        </div>
        <a href="index.php" class="back-to-home">Back to Home</a>
    </div>

    
</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>
