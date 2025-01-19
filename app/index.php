<?php
session_start();
include 'config.php';


// جلب المنتجات مع التصنيف إذا كان لديك جدول تصنيفات
// $sql = "SELECT p.*, c.name AS category_name 
//         FROM products p 
//         LEFT JOIN categories c ON p.category_id = c.id";
$sql = "SELECT * FROM PRODUCT";
if(isset($_GET["category"])) {
  $category = $_GET["category"];
  $sql="SELECT * FROM PRODUCT where CATEGORY like '$category'";
}
$result = $conn->query($sql);
$products = [];
// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         $products[] = $row;
//     }
// }

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
        <p><a href="Register.php">Register</a></p>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p><a href="logout.php">Logout</a></p>
        <?php else: ?>
            <p><a href="login.php">Login</a></p>
        <?php endif; ?>
      </div>
      <div class="flex cart">
        <p><a href="cart.php">Cart</a></p>
        <i class="fa-solid fa-cart-shopping"></i>
      </div>
    </div>

     <!-- Sidebar -->
     <div id="sidebar" class="sidebar hide-sidebar">
        <h1>Categories:</h1>
        <ul class="list">
          <li><a class='category' href='index.php?category=Dresses'>Dresses</a></li>
          <li><a class='category' href='index.php?category=Pants'>Pants</a></li>
          <li><a class='category' href='index.php?category=Skirts'>Skirts</a></li>
          <li><a class='category' href='index.php?category=Sweaters'>Sweaters</a></li>
          <li><a class='category' href='index.php?category=Accessories'>Accessories</a></li>
          <li><a class='category' href='index.php?category=Veil'>Veil</a></li>
          <li><a class='category' href='index.php?category=Shoes'>Shoes</a></li>
          <li><a class='category' href='index.php'>&lt;&lt;Clear filter&gt;&gt;</a></li>
        </ul>
        <!-- Toggle button -->
        <div class="toggle flex" onclick="hideSidebar()">
            <i class="fa-solid fa-arrow-right-to-bracket"></i>
        </div>
    </div>

    <!-- Main Content -->
    <!-- <section>
        <div class="hero"></div>
    </section> -->

    <!-- Product Highlights -->
    <section>
      <div class="container flex">
        <!-- Product Cards -->
         <?php 
         if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              // $products[] = $row;
              echo "<div class='card'>
                <img src='images/".$row['Photo']."' alt='".$row['NAME']."' />
                <h2>".$row['NAME']."</h2>
                <p>$". $row['Price']."</p>
                <form action='add_to_cart.php' method='POST' style='display: inline;'>
                  <input type='hidden' name='product_id' value='".$row['ID']."'> 
                  <input type='submit' class='add' value='Add to Cart'/>
              </form>
              </div>";
          }
         }
         ?>
        <!-- <div class="card">
          <img src="download.jpeg" alt="Product 1" />
          <h2>Product 1</h2>
          <p>$50</p>
          <form action="add_to_cart.php" method="POST" style="display: inline;">
            <input type="hidden" name="product_id" value="1"> 
            <input type="submit" class="add" value="Add to Cart"/>
        </form>
        </div>
        <div class="card">
          <img src="product2.jpg" alt="Product 2" />
          <h2>Product 2</h2>
          <p>$30</p>
          <form action="add_to_cart.php" method="POST" style="display: inline;">
            <input type="hidden" name="product_id" value="2"> 
            <button type="submit" class="add">Add to Cart</button>
        </form>
        </div>
        <div class="card">
          <img src="product3.jpg" alt="Product 3" />
          <h2>Product 3</h2>
          <p>$40</p>
          <form action="add_to_cart.php" method="POST" style="display: inline;">
            <input type="hidden" name="product_id" value="3"> 
            <button type="submit" class="add">Add to Cart</button>
        </form>
        </div>
        <div class="card">
          <img src="product4.jpg" alt="Product 4" />
          <h2>Product 4</h2>
          <p>$60</p>
          <form action="add_to_cart.php" method="POST" style="display: inline;">
            <input type="hidden" name="product_id" value="4"> 
            <button type="submit" class="add">Add to Cart</button>
        </form>
        </div> -->
      </div>
    </section>

    <!-- Footer or Other Sections -->
    <footer>
      <p>&copy; 2025 Your Store. All Rights Reserved.</p>
    </footer>
    
     <script>
        function hideSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hide-sidebar');
        }
    </script>

</body>
</html>