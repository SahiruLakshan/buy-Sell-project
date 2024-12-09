<?php

session_start(); // Start the session to manage user login sessions

include_once("../database/db_connect.php"); // Include the database connection

// // Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: /stock/login.php"); // Redirect to login page if the user is not logged in
    exit;
}

// // Handle logout functionality
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session to log out the user
    header("Location: /stock/login.php"); // Redirect to the login page after logout
    exit; // Ensure no further code is executed
}


$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : ''; // Handle search input for product search (brand or model)

$selected_category = isset($_POST['category']) ? $_POST['category'] : ''; // Handle category selection input for filtering products by category

$sql = "SELECT * FROM products WHERE 1=1"; // Base SQL query to retrieve all products


if ($search_term != '') {
    $sql .= " AND (brand LIKE '%$search_term%' OR model LIKE '%$search_term%')"; // Add a condition to the SQL query if a search term is provided (searches by brand or model)
}


if ($selected_category != '' && $selected_category != 'All') { // Add a condition to the SQL query if a specific category is selected (and not 'All')
    $sql .= " AND category = '$selected_category'";
}

// Execute the SQL query to get the filtered products
$result = $conn->query($sql);

$category_sql = "SELECT DISTINCT category FROM products"; // Fetch distinct categories from the products table for category dropdown
$category_result = $conn->query($category_sql);

// Handle order submission
if (isset($_POST['add_to_cart'])) {
     // Ensure session is started
    $user_id = $_SESSION['user_id']; // Current user's ID
    $product_id = $_POST['product_id'];
    $order_date = date('Y-m-d'); // Get the current date in YYYY-MM-DD format

    // Insert the order into the orders table
    $order_sql = "INSERT INTO orders (user_id, product_id, order_date) 
                  VALUES ('$user_id', '$product_id', '$order_date')";

    if ($conn->query($order_sql) === TRUE) {
        echo "<script>alert('Order has been submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error submitting the order: " . $conn->error . "');</script>";
    }
}

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/stock/img/icon.png" type="image/x-icon">
    <title>Stock Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background-image: url('../img/bgi.png');
        }

        .title {
            text-align: center;
            margin: 20px 0;
            font-size: 32px;
            color: #333;
        }

        .header {
            background-color: #3498db;
            color: white;
            padding: 20px;
            height: 150px;
            font-size: 28px;
            letter-spacing: 1px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header span {
            margin-left: 20px;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            margin-right: 20px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .search-form {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .cart {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            /* Lighter shadow */
            max-height: 150px;
            /* Decrease height */
            overflow-y: auto;
            /* Enable scrolling for long content */
            margin-right: 20px;
            color: #333;
            width: 250px;
            /* Decrease the width */
        }

        .cart h6 {
            font-size: 16px;
            /* Smaller font size */
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .cart ul {
            list-style: none;
            /* Remove default bullet points */
            padding-left: 0;
        }

        .cart li {
            background-color: #ecf0f1;
            padding: 8px;
            /* Less padding for more compact look */
            margin-bottom: 8px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart li h6 {
            font-size: 14px;
            /* Smaller font for cart item names */
            margin: 0;
        }

        .cart li span {
            font-size: 12px;
            /* Smaller font for price and quantity */
            color: #7f8c8d;
        }

        .cart .empty-cart-message {
            text-align: center;
            font-size: 14px;
            /* Decrease font size */
            color: #7f8c8d;
        }

        .cart h6 {
            display: flex;
            align-items: center;
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .cart svg {
            margin-right: 8px;
            /* Space between icon and text */
            fill: #3498db;
            /* Color of the icon */
        }


        .cart button {
            display: block;
            width: 100%;
            background-color: #3498db;
            color: white;
            padding: 8px;
            /* Less padding for smaller button */
            border-radius: 4px;
            border: none;
            font-size: 16px;
            /* Decrease font size */
            cursor: pointer;
            margin-top: 8px;
        }

        .cart button:hover {
            background-color: #2980b9;
        }


        .cart button {
            display: block;
            width: 100%;
            background-color: #3498db;
            color: white;
            padding: 10px;
            border-radius: 5px;
            border: none;
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
        }

        .cart button:hover {
            background-color: #2980b9;
        }



        .search-form input,
        .search-form select {
            padding: 10px;
            font-size: 16px;
            width: 40%;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .search-form button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #45a049;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: calc(25% - 20px);
            box-sizing: border-box;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        .card h3 {
            font-size: 18px;
            color: #333;
            margin: 10px 0 5px;
        }

        .card h4 {
            font-size: 15px;
            color: #333;
            margin: 10px 0 5px;
        }

        .card p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }

        .card .category {
            font-size: 16px;
            color: #007BFF;
            margin: 10px 0;
            font-weight: bold;
        }

        .card .stock {
            font-size: 14px;
            color: #FF5722;
            margin-bottom: 10px;
        }

        /* Container to center the button */
        .button-container {
            text-align: center;
            margin-top: 10px;
            /* Adds some spacing above the button */
        }

        /* Styles for the button */
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #45a049;
        }

        /* Modal CSS */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

    </style>
</head>

<body>
    <div class="header">
        <span>
            <h3>Welcome,Buying & Selling System</h3>
        </span>

        <form method="POST" style="margin: 0;">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </div>

    <form class="search-form" method="POST" action="">
        <input type="text" name="search_term" placeholder="Search by brand or model" value="<?php echo $search_term; ?>">

        <select name="category">
            <option value="All">All Categories</option>
            <?php
            if ($category_result->num_rows > 0) {
                while ($category_row = $category_result->fetch_assoc()) {
                    $selected = ($selected_category == $category_row['category']) ? 'selected' : '';
                    echo "<option value='{$category_row['category']}' $selected>{$category_row['category']}</option>";
                }
            }
            ?>
        </select>

        <button type="submit">Search</button>
    </form>

    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card'>";
                echo "<img src='../uploads/{$row['photo']}' alt='{$row['model']}'>";
                echo "<h3>{$row['brand']} {$row['model']}</h3>";
                echo "<p class='category' style='color: black;'>Category: {$row['category']}</p>";
                echo "<h4 class='stock'>Price: Rs. {$row['price']}/=</h4>";
                echo "<div class='button-container'>";
                echo "<a href='#' style='margin-bottom:20px' class='btn' 
                      onclick='viewDetails(`{$row['photo']}`, `{$row['brand']} {$row['model']}`, `{$row['category']}`, 
                      `{$row['description']}`, `{$row['price']}`)'>View Details</a>";
                echo "<form method='POST' action=''>
                          <input type='hidden' name='product_id' value='{$row['id']}'>
                          <input type='hidden' name='product_brand' value='{$row['brand']}'>
                          <input type='hidden' name='product_model' value='{$row['model']}'>
                          <input type='hidden' name='product_price' value='{$row['price']}'>
                          <button type='submit' name='add_to_cart' class='btn' 
                                  style='background-color: #4CAF50;'>Order Now</button>
                      </form>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No products found</p>";
        }
        ?>
    </div>

    <!-- Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="modalImage" src="" alt="Product Image">
            <h1 id="modalTitle"></h1>
            <p id="modalCategory"></p>
            <p id="modalDescription"></p>
            <p id="modalStock"></p>
            <p id="modalPrice"></p>
        </div>
    </div>

    <script>
        // Get modal and close button
        var modal = document.getElementById('productModal');
        var span = document.getElementsByClassName('close')[0];

        // When the user clicks on close (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Function to display product details in the modal
        function viewDetails(photo, title, category, description, price) {
            document.getElementById('modalImage').src = '../uploads/' + photo;
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalCategory').innerText = 'Category: ' + category;
            document.getElementById('modalDescription').innerText = description;
            document.getElementById('modalPrice').innerText = 'Price: Rs. ' + price + '/=';
            modal.style.display = "block";
        }
    </script>
</body>

</html>