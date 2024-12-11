<?php

include_once("../database/db_connect.php"); // Include the database connection file

session_start(); // Start session to manage user sessions

if (!isset($_SESSION['user_id'])) {
    header("Location: /stock/login.php"); // Redirect to login if not authorized
    exit; // Ensure no further code is executed
}

$success_message = '';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the product details from the database
    $sql = "SELECT * FROM product WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "No product ID provided.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $colors = $_POST['colors'];

    $photo = $product['photo']; // Default to the current photo

    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo']['name'];
        $photo_temp = $_FILES['photo']['tmp_name'];
        $upload_dir = "../uploads/";
        move_uploaded_file($photo_temp, $upload_dir . $photo);
    }

    // Prepare the SQL statement to update the product
    $sql = "UPDATE product SET category = ?, brand = ?, model = ?, price = ?, photo = ?, description = ?, colors = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdsssi", $category, $brand, $model, $price, $photo, $description, $colors, $product_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Product updated successfully!');
                window.location.href = '/stock/user/profile.php'; // Redirect to the profile page
              </script>";
        exit; // Prevent further execution
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/stock/img/icon.png" type="image/x-icon">

    <title>Edit Product</title>
    <style>
        body {
            background-image: url('../img/bgi.png');
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <h1>Edit Product</h1>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"> <?php echo $success_message; ?> </div>
        <?php endif; ?>

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="High Back" <?php if ($product['category'] == "High Back") echo "selected"; ?>>High Back</option>
            <option value="Low Back" <?php if ($product['category'] == "Low Back") echo "selected"; ?>>Low Back</option>
            <option value="Task" <?php if ($product['category'] == "Task") echo "selected"; ?>>Task</option>
            <option value="Visitor" <?php if ($product['category'] == "Visitor") echo "selected"; ?>>Visitor</option>
            <option value="Waiting Chair" <?php if ($product['category'] == "Waiting Chair") echo "selected"; ?>>Waiting Chair</option>
            <option value="Lecture Hall Chair" <?php if ($product['category'] == "Lecture Hall Chair") echo "selected"; ?>>Lecture Hall Chair</option>
        </select>

        <label for="brand">Brand Name:</label>
        <input type="text" name="brand" id="brand" value="<?php echo $product['brand']; ?>" required>

        <label for="model">Model Name:</label>
        <input type="text" name="model" id="model" value="<?php echo $product['model']; ?>" required>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" value="<?php echo $product['price']; ?>" required>

        <label for="photo">Photo:</label>
        <input type="file" name="photo" id="photo">
        <p>Current Photo: <?php echo $product['photo']; ?></p>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?php echo $product['description']; ?></textarea>

        <label for="colors">Available Colors:</label>
        <input type="text" name="colors" id="colors" value="<?php echo $product['colors']; ?>" required>

        <input type="submit" value="Update Product">
    </form>
</body>

</html>