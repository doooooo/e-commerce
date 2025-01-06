<?php  
$host = 'testdb';   $user = 'springstudent';  $pass = 'springstudent';    
$dbname = 'testdb';  $port=3306;
  
$conn = mysqli_connect ($host, $user, $pass, $dbname, $port);  
if(!$conn){  
      die('Could not connect: '.mysqli_connect_error());  
}  
/*echo 'Connected successfully <br/>'; 

#$sql="SELECT * FROM emp WHERE salary >2100";
$n = $_GET['nameee'];
$s = $_GET['sal'];
$add = $_GET['add'];

$sql="INSERT INTO emp (namee, salary, address) VALUES ('$n', '$s', '$add')";
$ret = mysqli_query ($conn, $sql);

if (!$ret)
	echo "Could not insert";
else
	echo "Data inserted successfully";


$sql="SELECT * FROM emp";
$ret = mysqli_query ($conn, $sql);
if(mysqli_num_rows($ret) > 0)
{
	echo "<ol>";
	while($row=mysqli_fetch_assoc($ret))
	{
		#echo "EMP ID: {$row['id']}<br/> ". "EMP NAME: {$row['id']}<br/>". "EMP salary: {$row['salary']}<br/>";
		echo "<li> <ul>";
		echo "<li>". $row['id']."</li>";
		echo "<li>". $row['namee']."</li>";
		echo "<li>". $row['salary']."</li>";
		echo "<li> {$row['address']} </li>";
		echo "</ul>";
	}
	echo "</ol>";
}
else echo "0 results found";

mysqli_free_result($ret);
mysqli_close($conn);*/

?>