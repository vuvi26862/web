<?php
// Include database connection
require '../connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input data
    $full_name = trim($_POST["full_name"]);
    $password = trim($_POST["password"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);

    // Basic validation
    if (empty($full_name) || empty($password) || empty($phone) || empty($address)) {
        echo "<p style='color:red;'>All fields are required!</p>";
        exit();
    }

    // Additional validation
    if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        echo "<p style='color:red;'>Invalid phone number format! Please enter 10-15 digits.</p>";
        exit();
    }

    if (strlen($full_name) > 100) {
        echo "<p style='color:red;'>Full name must not exceed 100 characters!</p>";
        exit();
    }

    if (strlen($phone) > 20) {
        echo "<p style='color:red;'>Phone number must not exceed 20 characters!</p>";
        exit();
    }

    // Hash password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL insert statement (using 'password' as per the database schema)
    $sql = "INSERT INTO users (user_name, password, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $full_name, $hashed_password, $phone, $address);

        if (mysqli_stmt_execute($stmt)) {
            echo "<p style='color:green;'>Đăng ký thành công! Bạn sẽ được chuyển hướng đến trang đăng nhập trong giây lát...</p>";
            header("refresh:2; url=../login/login.php");
            exit();
        } else {
            echo "<p style='color:red;'>Lỗi khi thực hiện câu lệnh SQL: " . mysqli_error($conn) . "</p>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<p style='color:red;'>Lỗi chuẩn bị câu lệnh SQL: " . mysqli_error($conn) . "</p>";
    }

    // Close database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="login1.css">
</head>
<body>
<div class="menthod">
    <h1>Sign Up</h1>
    <form method="post" action="">
        <p>Full Name</p>
        <input type="text" name="full_name" class="firstname" placeholder="Full name" required><br>
        <p>Phone Number</p>
        <input type="text" name="phone" placeholder="Phone number" required><br>
        <p>Address</p>
        <input type="text" name="address" placeholder="Address" required><br>
        <p>Password</p>
        <input type="password" name="password" class="Password" placeholder="Password" required><br>
        <div class="men">
            <ul>
                <li>Already have an account?</li>
                <li><a href="../login/login.php">Login</a></li>
            </ul>
        </div>
        <div class="mentr">
            <button type="submit">Create Account</button>
        </div>
    </form>
</div>
    <script src="login1.js"></script>
</body>
</html>