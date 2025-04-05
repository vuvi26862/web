<?php
session_start();
require 'connect.php';

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Xử lý thêm & cập nhật người dùng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_user'])) {
    $id = $_POST['user_id'] ?? '';
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($id) {
        // Cập nhật người dùng (Không cập nhật mật khẩu)
        $stmt = $conn->prepare("UPDATE users SET user_name=?, phone=?, address=? WHERE user_id=?");
        $stmt->bind_param("sssi", $username, $phone, $address, $id);
    } else {
        // Thêm người dùng mới (hash mật khẩu)
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (user_name, password, phone, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $password, $phone, $address);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Lưu thành công!'); window.location='user.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Xử lý xóa người dùng
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Xóa thành công!'); window.location='user.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Lấy danh sách người dùng
$result = $conn->query("SELECT user_id, user_name, phone, address FROM users");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>

<div class="container">
    <h2>User Management</h2>
    
    <!-- Form thêm / sửa người dùng -->
    <form method="POST">
        <input type="hidden" name="user_id" id="user_id">
        <input type="text" name="username" id="username" placeholder="name" required>
        <input type="text" name="phone" id="phone" placeholder="number-phone" required>
        <input type="text" name="address" id="address" placeholder="address">
        <input type="password" name="password" id="password" placeholder="password">
        <button type="submit" name="save_user" class="btn">save</button>
    </form>

    <!-- Bảng danh sách người dùng -->
    <table>
        <tr>
            <th>ID</th>
            <th>name</th>
            <th>number-phone</th>
            <th>address</th>
            <th>Act</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['user_id'] ?></td>
            <td><?= $row['user_name'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['address'] ?></td>
            <td>
                <button class="btn edit-btn" onclick="editUser('<?= $row['user_id'] ?>',
                 '<?= $row['user_name'] ?>', '<?= $row['phone'] ?>', '<?= $row['address'] ?>')"> edit</button>
                <a href="?delete=<?= $row['user_id'] ?>" class="btn delete-btn" 
                onclick="return confirm('Xác nhận xóa?');"> delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="buttons">
        <a href="dashboard.php">Come back</a>
    </div>
</div>

<script>
    function editUser(id, username, phone, address) {
        document.getElementById("user_id").value = id;
        document.getElementById("username").value = username;
        document.getElementById("phone").value = phone;
        document.getElementById("address").value = address;
        document.getElementById("password").removeAttribute("required");
    }
</script>

</body>
</html>

<?php $conn->close(); ?>
