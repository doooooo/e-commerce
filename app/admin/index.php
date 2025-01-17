<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    // echo 'email is not set';
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="style.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
</head>
<body>
<section>
      <div class="navbar flex">
        <h2>Admin Portal</h2>
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
    <section>
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
        <div class="toggle flex" onclick="hideSidebar()">
          <i class="fa-solid fa-arrow-right-to-bracket"></i>
        </div>
      </div>
    </section>
<dialog id="editDialog">
        <form method="post" action="index.php" enctype="multipart/form-data">
            <input type="hidden" name="id"/>
            <table>
                <tr><td></td><td><h2>Edit Product</h2></td></tr>
                <tr><td><span>Name</span></td><td><input type="text" name="name"/></td></tr>
                <tr><td><span>Category</span></td><td><select name="category">
                    <option value="Dresses">Dresses</option>
                    <option value="Pants">Pants</option>
                    <option value="Skirts">Skirts</option>
                    <option value="Sweaters">Sweaters</option>
                    <option value="Accessories">Accessories</option>
                    <option value="Veil">Veil</option>
                    <option value="Shoes">Shoes</option>
                </select></td></tr>
                <tr><td><span>Price</span></td><td><input type="number" name="price" min="0" step="any"/></td></tr>
                <tr><td><span>Image</span></td><td><input type="file" name="image"/></td></tr>
                <tr><td></td><td><input type="submit" class="submit-button" name="edit" value="Ok"/><button id="closePopup" class="submit-button" onclick="closePopup(e)">Close</button></td></tr>
                <tr><td></td><td></td></tr>
            </table>
        </form>
</dialog>
<?php include 'connect.php';
//insert product
if(isset($_POST["add"])) 
{ 
    $name = $_POST["name"]; 
	$category = $_POST["category"];
	$price = $_POST["price"]; 
    $photo = '';
    //if photo is uploaded
    if (isset($_FILES['image'])) {
        $target_dir = "/var/www/html/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $photo=htmlspecialchars(basename($_FILES["image"]["name"]));
        } else {
            $photo='';
        }
    }

	$insertsql = "INSERT INTO PRODUCT (`Category`, `NAME`, `Price`, `Photo`) VALUES ('$category', '$name', $price,'$photo');";
	$retinsert = mysqli_query ($conn, $insertsql);

	if(! $retinsert )
		echo ("Error adding product" . mysqli_error($conn));

}
//edit product
if(isset($_POST["edit"])) 
{ 
    $id = $_POST["id"]; 
	$name = $_POST["name"]; 
	$category = $_POST["category"];
	$price = $_POST["price"]; 
    $photo = '';
    //if photo is uploaded
    if (isset($_FILES['image'])) {
        $target_dir = "/var/www/html/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $photo=htmlspecialchars(basename($_FILES["image"]["name"]));
            $editsql = "UPDATE PRODUCT SET Category = '$category', NAME = '$name', Price = $price, Photo = '$photo' where ID = $id";
        } else {
            $photo='';
            $editsql = "UPDATE PRODUCT SET Category = '$category', NAME = '$name', Price = $price where ID = $id";
        }
    }
    else{
        $editsql = "UPDATE PRODUCT SET Category = '$category', NAME = '$name', Price = $price where ID = $id";
    }
	// $editsql = "UPDATE PRODUCT SET Category = '$category', NAME = '$name', Price = $price, Photo = '$photo' where ID = $id";
	$retedit = mysqli_query ($conn, $editsql);

	if(! $retedit )
		echo ("Error editing product" . mysqli_error($conn));

}
//delete product
if(isset($_GET["delid"])) {
    $id = $_GET["delid"];
    $delsql="DELETE FROM PRODUCT where ID = $id";
    $retdel = mysqli_query ($conn, $delsql);

	if(! $retdel )
		echo ("Error deleting product" . mysqli_error($conn));
    // else
        // echo "Product deleted successfuly";


}
//display all products
if(isset($_GET["category"])) {
    $category = $_GET["category"];
    $sql="SELECT * FROM PRODUCT where CATEGORY like '$category'";
	//echo "<div class='filter'><a class='clear' href='index.php'>&gt;&gt;Clear all filters</a></div>";
}
else{
    $sql="SELECT * FROM PRODUCT";
}
$ret = mysqli_query ($conn, $sql);
if(mysqli_num_rows($ret) > 0)
{
    echo "<section><div class='container'>";
	// echo "<ul>";
	while($row=mysqli_fetch_assoc($ret))
	{
		#echo "EMP ID: {$row['id']}<br/> ". "EMP NAME: {$row['id']}<br/>". "EMP salary: {$row['salary']}<br/>";
		// echo "<div class='card'> <ul>";
        // echo "<li><span>". $row['NAME']."</span></li>";
		// echo "<li><span>". $row['Price']."</span></li>";
		// echo "<li><a class='category' href='index.php?category=".$row['Category']."'>".$row['Category']."</a></li>";
        // echo "<li><a href='#' class='edit' onclick=\"onEditProduct(".$row['ID'].", event)\">Edit</a></li>";
		// echo "<li><a class='delete' href='index.php?delid=".$row['ID']."' onclick='onDeleteProduct(event)'>Delete</a></li>";
		// echo "</ul></div>";

        echo "<div class='card'>
          <img src='../images/".$row['Photo']."'/>
          <h2>". $row['NAME']."</h2>
          <p>". $row['Price']."$</p>
          <a href='#' class='edit' onclick=\"onEditProduct(".$row['ID'].", event)\">Edit</a>
          <a class='delete' href='index.php?delid=".$row['ID']."' onclick='onDeleteProduct(event)'>Delete</a>
        </div>";
	}
	// echo "</ul>";
    echo "</div></section>";
	
}
else echo "0 results found";

mysqli_free_result($ret);
mysqli_close($conn);
?>
<script src="script.js"></script>
</body>
</html>