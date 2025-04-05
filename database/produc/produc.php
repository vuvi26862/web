<?php
require 'connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}

$sql = "SELECT product_id as id, product_name as name, description,
 price, quantity, image_url as image FROM product";
$result = $conn->query($sql);

if (!$result) {
    die("Lỗi truy vấn SQL: " . $conn->error);
}

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .product-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image {
            height: 200px;
            overflow: hidden;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .product-info {
            padding: 15px;
        }
        .product-name {
            font-size: 18px;
            margin: 0 0 10px;
            color: #333;
        }
        .product-price {
            color: #e74c3c;
            font-weight: bold;
            font-size: 16px;
        }
        .product-quantity {
            color: #7f8c8d;
            font-size: 14px;
        }
        .action-buttons {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background: #c0392b;
        }
        .add-product-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
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
            background: #2ecc71;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
        }
        .btn-submit:hover {
            background: #27ae60;
        }
        .no-products {
            text-align: center;
            padding: 50px;
            color: #7f8c8d;
        }
        .btn-edit {
        background: #3498db;
        color: white;
        }
        .btn-edit:hover {
        background: #2980b9;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h1>Product Management</h1>
        <div class="add-product-form">
            <h2>Add product </h2>
            <form method="POST" action="product_actions.php?action=add" 
            enctype="multipart/form-data">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" id="product_name" name="product_name" required>
                </div>
                <div class="form-group">
                    <label for="description">Describe</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                <div class="form-group">
                    <label for="price">price</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="quantity">quantity</label>
                    <input type="number" id="quantity" name="quantity" required>
                </div>
                <div class="form-group">
                    <label for="product_image">Product photo</label>
                    <input type="file" id="product_image" name="product_image" 
                    accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-submit">Add Product</button>
            </form>
        </div>
        
        <div class="product-grid">
            <?php if (empty($products)): ?>
                <div class="no-products">
                    <p>There are no products in the system</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($product['image']) ?>"
                             alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                            <p class="product-price">price: <?= number_format($product['price'], 2) ?>$</p>
                            <p class="product-quantity">quantity: <?= $product['quantity'] ?></p>
                            <div class="action-buttons">
                                <a href="edit_product.php?id=<?= $product['id'] ?>"
                                 class="btn btn-edit">edit</a>
                                <a href="product_actions.php?action=delete&id=<?= $product['id'] ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>