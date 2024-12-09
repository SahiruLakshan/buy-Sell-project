<?php
// Include the database connection file
include_once("../database/db_connect.php");

// Start session to manage user sessions
session_start();

// Check if the user is logged in and is an admin (user_type == 1), otherwise redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
    header("Location: /stock/login.php"); // Redirect to login if not authorized
    exit; 
}

// Get the product ID from the URL
$id = $_GET['id'];

// Prepare and execute an SQL query to select the product with the given ID
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); // Bind the product ID as an integer
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc(); // Fetch the product as an associative array

// Check if the product exists, if not, terminate the script
if (!$product) {
    die("Product not found.");
}

// Handle form submission for updating the product details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated product details from the form
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $warranty = $_POST['warranty'];
    $colors = $_POST['colors'];
    $stock = $_POST['stock'];

    // Handle file upload for the product photo, if a new one was uploaded
    if ($_FILES['photo']['name']) {
        $photo = $_FILES['photo']['name']; // Get the new photo name
        $photo_temp = $_FILES['photo']['tmp_name']; // Get the temporary file path
        $upload_dir = "../uploads/"; // Define the directory to upload the photo
        move_uploaded_file($photo_temp, $upload_dir . $photo); // Move the uploaded file to the uploads directory
    } else {
        $photo = $product['photo']; // Keep the existing photo if no new file is uploaded
    }
    
    // Prepare and execute an SQL query to update the product details in the database
    $sql = "UPDATE products SET category = ?, brand = ?, model = ?, price = ?, photo = ?, description = ?, warranty = ?, colors = ?, stock = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssis", $category, $brand, $model, $price, $photo, $description, $warranty, $colors, $stock, $id);

    // Check if the update was successful and provide feedback
    if ($stmt->execute()) {
        // If successful, show an alert and redirect to the product view page
        echo "<script>alert('Product updated successfully!'); window.location.href='productview.php';</script>";
    } else {
        // Display an error message if the update failed
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
            font-family: Arial, sans-serif;
            background-image: url('../img/bgi.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
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
    </style>
</head>

<body>

    <div class="container">
        <h1>Edit Product <?php echo htmlspecialchars($product['brand']); ?> <?php echo htmlspecialchars($product['model']); ?></h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="High Back" <?php if ($product['category'] == 'High Back') echo 'selected'; ?>>High Back</option>
                <option value="Low Back" <?php if ($product['category'] == 'Low Back') echo 'selected'; ?>>Low Back</option>
                <option value="Task" <?php if ($product['category'] == 'Task') echo 'selected'; ?>>Task</option>
                <option value="Visitor" <?php if ($product['category'] == 'Visitor') echo 'selected'; ?>>Visitor</option>
                <option value="Waiting Chair" <?php if ($product['category'] == 'Waiting Chair') echo 'selected'; ?>>Waiting Chair</option>
                <option value="Lecture Hall Chair" <?php if ($product['category'] == 'Lecture Hall Chair') echo 'selected'; ?>>Lecture Hall Chair</option>
            </select>

            <label for="brand">Brand Name:</label>
            <input type="text" name="brand" id="brand" value="<?php echo htmlspecialchars($product['brand']); ?>" required>

            <label for="model">Model Name:</label>
            <input type="text" name="model" id="model" value="<?php echo htmlspecialchars($product['model']); ?>" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>

            <label for="photo">Photo (Leave blank to keep current photo):</label>
            <input type="file" name="photo" id="photo">

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label for="warranty">Warranty Period (in months):</label>
            <input type="number" name="warranty" id="warranty" value="<?php echo htmlspecialchars($product['warranty']); ?>" required>

            <label for="colors">Available Colors:</label>
            <input type="text" name="colors" id="colors" value="<?php echo htmlspecialchars($product['colors']); ?>" required>

            <label for="stock">Available Stock:</label>
            <input type="number" name="stock" id="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>

            <input type="submit" value="Update Product">
        </form>
    </div>

</body>

</html>