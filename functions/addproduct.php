<?php

include_once("../database/db_connect.php");// Include the database connection file

session_start();// Start session to manage user sessions

if (!isset($_SESSION['user_id'])) {// Check if the user is logged in and is an admin (user_type == 1), otherwise redirect to login page
    header("Location: /stock/login.php"); // Redirect to login if not authorized
    exit; // Ensure no further code is executed
}

$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {// Check if the form was submitted using the POST method
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $colors = $_POST['colors'];

    $photo = $_FILES['photo']['name']; // Get the photo file name
    $photo_temp = $_FILES['photo']['tmp_name']; // Get the temporary file location
    $upload_dir = "../uploads/"; // Define the upload directory
    move_uploaded_file($photo_temp, $upload_dir . $photo); // Move the uploaded file to the destination folder

    // Prepare the SQL statement to insert new product data into the 'products' table
    $sql = "INSERT INTO products (user_id,category, brand, model, price, photo, description, colors)
            VALUES ('$user_id','$category', '$brand', '$model', '$price', '$photo', '$description', '$colors')";

    // Execute the SQL query and check if the insert was successful
    if ($conn->query($sql) === TRUE) {
        // If successful, show an alert and reload the page
        echo "<script>
                window.onload = function() {
                    alert('Product added successfully!'); // Show success message in an alert box
                    window.location.href = window.location.pathname; // Reload the current page
                };
              </script>";
        exit(); // Exit to prevent further execution
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/stock/img/icon.png" type="image/x-icon">

    <title>Stock Management - Office Chairs</title>
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
            margin-top: 300px;
            /* Add padding to the top */
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
    </style>
</head>

<body>
    <form action="" method="POST" enctype="multipart/form-data" id="chairForm">
        <h1>Add Chairs to Stock</h1>

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="High Back">High Back</option>
            <option value="Low Back">Low Back</option>
            <option value="Task">Task</option>
            <option value="Visitor">Visitor</option>
            <option value="Waiting Chair">Waiting Chair</option>
            <option value="Lecture Hall Chair">Lecture Hall Chair</option>
        </select>

        <label for="brand">Brand Name:</label>
        <input type="text" name="brand" id="brand" required>

        <label for="model">Model Name:</label>
        <input type="text" name="model" id="model" required>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="photo">Photo:</label>
        <input type="file" name="photo" id="photo" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="colors">Available Colors:</label>
        <input type="text" name="colors" id="colors" required>

        <input type="submit" value="Add Chair">
    </form>
</body>

</html>
