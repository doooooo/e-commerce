<?php
include 'connect.php';
session_start();

if(isset($_GET['logout']))
{
    $_SESSION['admin_id'] = null;
    $_SESSION['admin_name'] = null;
}

if (isset($_POST['login'])) {
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $sql = "SELECT * FROM ADMIN WHERE USERID = '$name' AND PASSWORD='$pass'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0)
    {
        if ($row = mysqli_fetch_assoc($result)) {
            // echo json_encode($row);
            $_SESSION['admin_id'] = $row['USERID'];
            $_SESSION['admin_name'] = $row['NAME'];
            header("Location: index.php");
        } 
    }
    else{
        $error='Wrong username & password';
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="style.css">
</head>
<body>
<section>
<div class="container">
<form method="post" action="login.php">
		<table>
			<tr><td></td><td><h2>Admin Login</h2></td></tr>
			<tr><td><span>Username</span></td><td><input type="text" name="name" required/></td></tr>
			<tr><td><span>Password</span></td><td><input type="password" name="pass" required/></td></tr>
			<tr><td></td><td><input class="submit-button" type="submit" name="login" value="Login"/></td></tr>
			<tr><td></td><td></td></tr>
            <?php
            if(isset($error))
            {
                echo "<tr><td></td><td><span>".$error."</span></td></tr>";
            }
            ?>
			<tr><td></td><td></td></tr>
		</table>
	</form>
</div>
</section>
</body>