<?php
require 'connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Lấy ID sản phẩm cần chỉnh sửa
$product_id = (int)$_GET['id'];
// Lấy thông tin sản phẩm từ database
$stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Không tìm thấy sản phẩm");
}

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Xử lý upload ảnh mới (nếu có)
        $image_url = $product['image_url'];
        if (!empty($_FILES['product_image']['name'])) {
            $targetDir = "uploads/";
            $fileName = uniqid() . '_' . basename($_FILES["product_image"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath)) {
                // Xóa ảnh cũ
                if (file_exists($image_url)) {
                    unlink($image_url);
                }
                $image_url = $targetFilePath;
            } else {
                throw new Exception("Có lỗi xảy ra khi upload ảnh.");
            }
        }
        
        // Cập nhật thông tin sản phẩm
        $stmt = $conn->prepare("UPDATE product SET 
            product_name = ?, 
            description = ?, 
            price = ?, 
            quantity = ?, 
            image_url = ?
            WHERE product_id = ?");
        
        $stmt->bind_param(
            "ssdisi", 
            $_POST['product_name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['quantity'],
            $image_url,
            $product_id
        );
        
        if ($stmt->execute()) {
            header("Location: produc.php");
            exit();
        } else {
            throw new Exception("Lỗi cập nhật sản phẩm: " . $stmt->error);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa sản phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .edit-product-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .btn-submit {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background: #2980b9;
        }
        .current-image {
            margin-top: 10px;
        }
        .current-image img {
            max-width: 200px;
            max-height: 200px;
        }
        .error {
            color: #e74c3c;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <div class="edit-product-form">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="product_name">products name</label>
                    <input type="text" id="product_name" name="product_name" 
                           value="<?= htmlspecialchars($product['product_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">describe</label>
                    <textarea id="description" name="description"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">price</label>
                    <input type="number" id="price" name="price" step="0.01" 
                           value="<?= htmlspecialchars($product['price']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="quantity">quantity</label>
                    <input type="number" id="quantity" name="quantity" 
                           value="<?= htmlspecialchars($product['quantity']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="product_image">New product photo (leave blank if keeping old photo)</label>
                    <input type="file" id="product_image" name="product_image" accept="image/*">
                    
                    <?php if (!empty($product['image_url'])): ?>
                        <div class="current-image">
                            <p>current photo:</p>
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Ảnh sản phẩm hiện tại">
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-submit">Product Updates</button>
                <a href="produc.php" style="margin-left: 10px;">Come back</a>
            </form>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>