<?php
session_start();
include_once("../database/db_connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../stock/login.php");
    exit;
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id == 0) {
    echo "Invalid Product ID";
    exit;
}

// Fetch product details
$sql = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Product not found";
    exit;
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/stock/img/icon.png" type="image/x-icon">
    <title>Product Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .details-container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .details-container img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .details-container h1 {
            font-size: 28px;
            margin-top: 20px;
            color: #333;
        }

        .details-container p {
            font-size: 16px;
            color: #666;
            margin: 10px 0;
        }

        .details-container .price {
            font-size: 22px;
            color: #4CAF50;
            margin-top: 10px;
        }

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="details-container">       
    <img src="../uploads/<?php echo $product['photo']; ?>" alt="<?php echo $product['model']; ?>">
    <h1><?php echo $product['brand'] . ' ' . $product['model']; ?></h1>
    <p>Category: <?php echo $product['category']; ?></p>
    <p><?php echo $product['description']; ?></p>
    <p>Available Stock: <?php echo $product['stock']; ?></p>
    <p class="price">Price: Rs. <?php echo number_format($product['price'], 2); ?>/=</p>

    <a href="stock_management.php" class="btn-back">Back to Products</a>
</div>

</body>
</html>
