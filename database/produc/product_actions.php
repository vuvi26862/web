<?php
require 'connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}
$action = $_GET['action'] ?? '';
try {
    switch ($action) {
        case 'add':
            // Xử lý upload ảnh
            $targetDir = "uploads/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $fileName = uniqid() . '_' . basename($_FILES["product_image"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            

            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath)) {
                $stmt = $conn->prepare("INSERT INTO product (
                    product_name, description, price, quantity, image_url
                ) VALUES (?, ?, ?, ?, ?)");
                
                $stmt->bind_param(
                    "ssdis", 
                    $_POST['product_name'],
                    $_POST['description'],
                    $_POST['price'],
                    $_POST['quantity'],
                    $targetFilePath
                );
            } else {
                throw new Exception("Có lỗi xảy ra khi upload ảnh.");
            }
            break;
            
        case 'delete':
            $id = (int)$_GET['id'];
            
            // Lấy đường dẫn ảnh để xóa file
            $sql = "SELECT image_url FROM product WHERE product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            
            // Xóa file ảnh nếu tồn tại
            if ($product && !empty($product['image_url']) && file_exists($product['image_url'])) {
                unlink($product['image_url']);
            }
            
            // Xóa sản phẩm từ database
            $stmt = $conn->prepare("DELETE FROM product WHERE product_id = ?");
            $stmt->bind_param("i", $id);
            break;
            
        default:
            throw new Exception("Action không hợp lệ");
    }

    if ($stmt->execute()) {
        header("Location: produc.php");
        exit();
    } else {
        throw new Exception("Lỗi thực thi: " . $stmt->error);
    }
} catch (Exception $e) {
    die("Lỗi: " . $e->getMessage());
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}


?>