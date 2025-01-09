<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>My first PHP page</h1>
<div class="topnav">
  <a class="menu" href="new_product.html">Add Product</a>
</div>
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
		echo "<li><a class='delete' href='index.php?delid=".$row['ID']."'>Delete</a></li>";
		echo "</ul></div>";
	}
	// echo "</ul>";
    echo "</div>";
	
}
else echo "0 results found";

mysqli_free_result($ret);
mysqli_close($conn);
?>

</body>
</html>