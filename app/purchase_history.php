<?php
// Start session to manage logged-in user
session_start();

// Database connection
$host = 'testdb';
$dbname = 'testdb';
$username = 'springstudent'; // Use 'root' for XAMPP/WAMP
$password = 'springstudent'; // Leave blank for XAMPP/WAMP
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to view your purchase history.";
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch purchase history for the logged-in user
$sql = "
    SELECT 
        p.id AS purchase_id,
        pr.name AS product_name,
        p.Qty,
        p.Price,
        p.Date
    FROM PURCHASES p
    JOIN product pr ON p.PRODUCTID = pr.id
    WHERE p.USERID = :user_id
    ORDER BY p.Date DESC
";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .no-purchases {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Your Purchase History</h1>
    <?php if (count($purchases) > 0): ?>
        <table>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Purchase Date</th>
            </tr>
            <?php foreach ($purchases as $index => $purchase): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($purchase['product_name']) ?></td>
                    <td><?= htmlspecialchars($purchase['quantity']) ?></td>
                    <td>$<?= htmlspecialchars($purchase['total_price']) ?></td>
                    <td><?= htmlspecialchars($purchase['purchase_date']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p class="no-purchases">You have no purchases yet.</p>
    <?php endif; ?>
</body>
</html>
