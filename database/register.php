<?php
require 'connect.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){
$full_name = trim($_POST["full_name"]); 
$password = trim($_POST["password"]); 
$phone = trim($_POST["phone"]); 
$address = trim($_POST["address"]); 

$hashed_Password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users(user_name, password, phone, address) VALUES(?,?,?,?)";
$stmt = mysqli_prepare($conn,$sql);
if($stmt){
    mysqli_stmt_bind_param($stmt, "ssss", $full_name, $hashed_Password, $phone, $address);
    if(mysqli_stmt_execute($stmt))
        header("refresh:1; url=login.html");
    exit();
    }else{
        echo "error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);

}else{
    echo"error preparing statenment: " . mysqli_error($conn);
}
mysqli_close($conn)
?>

