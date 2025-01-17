<?php
$servername = "testdb";
$username = "springstudent";
$password = "springstudent";
$dbname = "testdb"; // Replace with the name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
