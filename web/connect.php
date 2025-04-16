<?php
$host = 'localhost';
$username = 'root'; // Default XAMPP MySQL username
$password = '';     // Default XAMPP MySQL password (empty)
$database = 'mypham_db'; // Replace with your database name

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>