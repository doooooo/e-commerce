<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="../style.css">
</head>
<body>
<section>
      <div class="navbar flex">
        <div></div>
        <div class="flex nav-options">
          <p><a class="menu" href="index.html">All Products</a></p>
          <p><a class="menu" href="new_product.html">Add Product</a></p>
          <p>option</p>
          <p>option</p>
        </div>
        <div class="flex cart">
          <p>Cart</p>
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
      </div>
    </section>
<dialog id="editDialog">
        <form method="post" action="index.php">
            <input type="hidden" name="id"/>
            <table>
                <tr><td></td><td><h2>Edit Product</h2></td></tr>
                <tr><td>Name</td><td><input type="text" name="name"/></td></tr>
                <tr><td>Category</td><td><select name="category">
                    <option value="Candy">Candy</option>
                    <option value="Snacks">Snacks</option>
                    <option value="Poultry">Poultry</option>
                    <option value="Meat">Meat</option>
                </select></td></tr>
                <tr><td>Price</td><td><input type="number" name="price" min="0" step="any"/></td></tr>
                <tr><td>Image</td><td><input type="file" name="image"/></td></tr>
                <tr><td><input type="submit" name="edit" value="Ok"/></td><td><button id="closePopup" onclick="closePopup(e)">Close</button></td></tr>
                <tr><td></td><td></td></tr>
            </table>
        </form>
</dialog>
<?php include '../connect.php';
//insert product
if(isset($_POST["add"])) 
{ 
	$name = $_POST["name"]; 
	$category = $_POST["category"];
	$price = $_POST["price"]; 
	$insertsql = "INSERT INTO PRODUCT (`Category`, `NAME`, `Price`) VALUES ('$category', '$name', $price);";
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
	$editsql = "UPDATE PRODUCT SET Category = '$category', NAME = '$name', Price = $price where ID = $id";
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
    else
        echo "Product deleted successfuly";


}
//display all products
if(isset($_GET["category"])) {
    $category = $_GET["category"];
    $sql="SELECT * FROM PRODUCT where CATEGORY like '$category'";
	echo "<div class='filter'><a class='clear' href='index.php'>&gt;&gt;Clear all filters</a></div>";
}
else{
    $sql="SELECT * FROM PRODUCT";
}
$ret = mysqli_query ($conn, $sql);
if(mysqli_num_rows($ret) > 0)
{
    echo "<div class='container'>";
	// echo "<ul>";
	while($row=mysqli_fetch_assoc($ret))
	{
		#echo "EMP ID: {$row['id']}<br/> ". "EMP NAME: {$row['id']}<br/>". "EMP salary: {$row['salary']}<br/>";
		echo "<div class='card'> <ul>";
        echo "<li><span>". $row['NAME']."</span></li>";
		echo "<li><span>". $row['Price']."</span></li>";
		echo "<li><a class='category' href='index.php?category=".$row['Category']."'>".$row['Category']."</a></li>";
        echo "<li><a href='#' class='edit' onclick=\"onEditProduct(".$row['ID'].", event)\">Edit</a></li>";
		echo "<li><a class='delete' href='index.php?delid=".$row['ID']."' onclick='onDeleteProduct(event)'>Delete</a></li>";
		echo "</ul></div>";
	}
	// echo "</ul>";
    echo "</div>";
	
}
else echo "0 results found";

mysqli_free_result($ret);
mysqli_close($conn);
?>
<script src="script.js"></script>
</body>
</html>