<?php
session_start(); 
require '../connect.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$admin_username = 'admin';
$admin_password = 'admin123';
$admin_hashed = password_hash($admin_password, PASSWORD_DEFAULT);

$check_admin = "SELECT * FROM users WHERE user_name = ?";
$stmt = mysqli_prepare($conn, $check_admin);
mysqli_stmt_bind_param($stmt, "s", $admin_username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) == 0) {
    $insert_admin = "INSERT INTO users (user_name, password) VALUES (?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_admin);
    mysqli_stmt_bind_param($insert_stmt, "ss", $admin_username, $admin_hashed);
    mysqli_stmt_execute($insert_stmt);
    mysqli_stmt_close($insert_stmt);

    // echo "<p style='color:green;'>Admin account has been created! Username: admin, Password: admin123</p>";
}

mysqli_stmt_close($stmt);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["name"]); 
    $password = trim($_POST["password"]);

    // Kiểm tra người dùng có tồn tại hay không
    $sql = "SELECT user_id, user_name, password FROM users WHERE user_name = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $user_id, $db_username, $hashed_password);
            mysqli_stmt_fetch($stmt);

            // Kiểm tra mật khẩu
            if (password_verify($password, $hashed_password)) {
                // Lưu thông tin vào session
                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $db_username;

                // Kiểm tra nếu là admin
                if ($db_username === 'admin') {
                    $_SESSION["is_admin"] = true;
                    header("Location: ..database/user.php"); // Chuyển hướng đến trang quản lý user
                } else {
                    $_SESSION["is_admin"] = false;
                    header("Location: ../index.html"); // Chuyển hướng đến trang chính
                }
                exit();
            } else {
                // echo "<p style='color:red;'>Invalid password!</p>";
            }
        } else {
            // echo "<p style='color:red;'>User not found!</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="loginn.css">

</head>
<body>
   
<div class="menthod">
    <h1>Login</h1>
    <form method="post" action="">
        <p>Email:</p>
        <input type="text" name="name" class="email" placeholder="email or number phone" id="email"><br>
        <p>Password</p>
        <input type="password" name="password" class="password" placeholder="Password" id="pwd"><br>
        <div class="men">
            <ul>
                <li>Forgot Password?</li>
                <li><a href="../web/singup/singup.php">Sign up for an account?</a></li>
            </ul>
        </div>
        <br>
        <div class="mentr">
            <button type="submit" class="submit-btn">Sign in</button>
        </div>
    </form>
</div>
    <script src="web/login/crip.js"></script>
</body>
</html>
