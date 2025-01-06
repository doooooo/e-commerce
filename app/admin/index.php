<!DOCTYPE html>
<html>
<body>

<?php include '../connect.php';
echo "<h1>My first PHP page</h1>";
$sql="SELECT * FROM PRODUCT";
$ret = mysqli_query ($conn, $sql);
if(mysqli_num_rows($ret) > 0)
{
    echo "<div>";
	echo "<ul>";
	while($row=mysqli_fetch_assoc($ret))
	{
		#echo "EMP ID: {$row['id']}<br/> ". "EMP NAME: {$row['id']}<br/>". "EMP salary: {$row['salary']}<br/>";
		echo "<li> <ul>";
		echo "<li>". $row['Category']."</li>";
		echo "<li>". $row['NAME']."</li>";
		echo "<li>". $row['Price']."</li>";
		echo "</ul>";
	}
	echo "</ul>";
    echo "</div>";
}
else echo "0 results found";

mysqli_free_result($ret);
mysqli_close($conn);
?>

</body>
</html>