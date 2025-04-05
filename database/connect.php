<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mypham_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn ) {
    die("thanh cong" . mysql_connect_error());
}
else{
    echo"loi ket noi";
}
