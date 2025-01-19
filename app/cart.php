<?php
session_start();
include 'config.php';

// جلب بيانات السلة
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
if ($user_email) {
    // التحقق من وجود المستخدم في قاعدة البيانات
    $sql = "SELECT ID FROM USER WHERE USERID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['ID'];
        // echo 'user id '.$user_id;
    } else {
        // إذا لم يتم العثور على المستخدم، يمكن إيقاف الوصول للسلة أو عرض رسالة
        header('Location: login.php?error=UserNotFound');
        exit();
    }
} else {
    // إذا لم يكن المستخدم مسجلاً الدخول يمكن عرض السلة كزائر
    $user_id = null; // هذا سيجعل السلة تعرض بشكل عام لجميع المستخدمين
}

// جلب عناصر السلة
$sql = "SELECT p.ID, p.NAME, p.Photo, p.Price, c.quantity FROM cart c JOIN PRODUCT p ON c.product_id = p.ID WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result();
// echo 'cart item num '.$cart_items->num_rows;
// حساب الإجمالي
$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar flex">
        <div><img class="logo" src="logo.jpeg"/></div>
        <div class="flex nav-options">
            <p><a href="index.php">Home</a></p>
            <p><a href="Register.php">Register</a></p>
            <!-- إزالة رابط "Register.html" هنا -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <p><a href="logout.php">Logout</a></p>
            <?php else: ?>
                <p><a href="login.php">Login</a></p>
            <?php endif; ?>
        </div>
        <div class="flex cart">
            <p>
            <a href="cart.php">Cart</a>
            <i class="fa-solid fa-cart-shopping"></i>
        </p>
        </div>
    </div>

    <!-- Cart Section -->
    <section class="container cart-container">
        
            <div>
                <h2 class="cart-title">Your Cart</h2>
                <table style="margin: 30px">
                    <tr><td><label for="name">Full Name:</label></td><td><input type="text" size="30" id="name" name="name" required></td></tr>
                    <tr></tr>
                    <tr><td><label for="phone">Phone:</label></td><td><input type="text" size="30" id="phone" name="phone" required></td></tr>
                    <tr></tr>
                    <tr><td><label for="addr">Address:</label></td><td><textarea name="addr" id="addr" cols="30" rows="3" required></textarea></td></tr>
                    <tr><td></td><td></td></tr>
                    <tr><td></td><td></td></tr>
                </table>
            </div>

        <form id="cart-form" class="cart-form" action="checkout.php" method="POST">
            
            <div class="cart-items flex">
                <?php if ($cart_items->num_rows > 0): ?>
                    <?php while ($item = $cart_items->fetch_assoc()): ?>
                        <?php $total_price += $item['Price'] * $item['quantity']; ?>
                        <div class="card cart-item">
                            <img src="images/<?php echo $item['Photo']; ?>" alt="Product">
                            <div>
                                <h3><?php echo htmlspecialchars($item['NAME']); ?></h3>
                                <p>Price: $<?php echo number_format($item['Price'], 2); ?></p>
                                <label for="quantity-<?php echo $item['ID']; ?>">Quantity:</label>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity-<?php echo $item['ID']; ?>" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" 
                                       data-product-id="<?php echo $item['ID']; ?>" 
                                       class="quantity-field">
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>
            <div class="cart-summary">
                <h3>Total: $<span id="total-price"><?php echo number_format($total_price, 2); ?></span></h3>
            </div>
            <div class="purchase-history">
                <!-- إظهار رابط "View Purchase History" إذا كان المستخدم قد سجل الدخول -->
                <?php if (isset($_SESSION['user_email'])): ?>
                    <!-- <a href="checkout.php" class="purchase-history-btn">Checkout</a> -->
                    <button type="submit" class="purchase-history-btn">Checkout</button>
                    <a href="purchase-history.php" class="purchase-history-btn">View Purchase History</a>
                <?php else: ?>
                    <p>Please <a href="login.php">login</a> to view your purchase history.</p>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <!-- JavaScript -->
    <script>
        $(document).ready(function () {
            // استمع لتغيير الكمية
            $('.quantity-field').on('change', function () {
                const productId = $(this).data('product-id');
                const quantity = $(this).val();

                // إرسال الطلب إلى السيرفر باستخدام AJAX
                $.ajax({
                    url: 'update-cart.php',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        quantity: quantity,
                        update_quantity: true
                    },
                    success: function (response) {
                        // تحديث الإجمالي في حال نجاح الطلب
                        if (response.success) {
                            $('#total-price').text(response.total_price);
                        } else {
                            alert('Failed to update cart. Please try again.');
                        }
                    },
                    error: function (error) {  
                        alert('An error occurred while updating the cart.');
                    }
                });
            });
        });

        //////Form validation///////////////////////
        const form = document.getElementById("cart-form");

            form.addEventListener("submit", (event) => {
                // Get form fields
                const name = document.getElementById("name").value;
                const phone = document.getElementById("phone").value;
                const address = document.getElementById("addr").value;
                let isValid = true;
                let errorMessage = "";
                // Validate name
                if (!name) {
                    isValid = false;
                    errorMessage += "Full Name is required.\n";
                }

                // Validate phone
                if (phone==='') {
                    isValid = false;
                    errorMessage += "Phone is required.\n";
                } /*else if (!/^\d{10}$/.test(phone)) {
                    isValid = false;
                    errorMessage += "Phone must be a 10-digit number.\n";
                }*/

                // Validate address
                if (address==='') {
                    isValid = false;
                    errorMessage += "Address is required.\n";
                }

                // Show error message if invalid
                if (!isValid) {
                    event.preventDefault(); // Prevent form submission
                    alert(errorMessage);
                }
            });
    </script>
</body>
</html>
