<?php

session_start(); // Start the session to manage user login sessions
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Include PHPMailer
include_once("../database/db_connect.php"); // Include the database connection

if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session to log out the user
    header("Location: /stock/index.php"); // Redirect to the login page after logout
    exit; // Ensure no further code is executed
}


$search_term = isset($_POST['search_term']) ? $_POST['search_term'] : ''; // Handle search input for product search (brand or model)

$selected_category = isset($_POST['category']) ? $_POST['category'] : ''; // Handle category selection input for filtering products by category

$sql = "SELECT * FROM product WHERE 1=1"; // Base SQL query to retrieve all products


if ($search_term != '') {
    $sql .= " AND (brand LIKE '%$search_term%' OR model LIKE '%$search_term%')"; // Add a condition to the SQL query if a search term is provided (searches by brand or model)
}


if ($selected_category != '' && $selected_category != 'All') { // Add a condition to the SQL query if a specific category is selected (and not 'All')
    $sql .= " AND category = '$selected_category'";
}

// Execute the SQL query to get the filtered products
$result = $conn->query($sql);

$category_sql = "SELECT DISTINCT category FROM product"; // Fetch distinct categories from the products table for category dropdown
$category_result = $conn->query($category_sql);

// Handle order submission
if (isset($_POST['order_product'])) {
    $user_id = $_SESSION['user_id']; // Current user's ID
    $product_id = $_POST['product_id'];
    $order_date = date('Y-m-d'); // Current date

    // Insert the order into the database
    $order_sql = "INSERT INTO product_orders (user_id, product_id, order_date) 
                  VALUES ('$user_id', '$product_id', '$order_date')";

    if ($conn->query($order_sql) === TRUE) {
        // Retrieve the auto-generated order ID
        $order_id = $conn->insert_id;

        // Get the product details from the product table
        $product_sql = "SELECT brand, model, price FROM product WHERE id = '$product_id'";
        $product_result = $conn->query($product_sql);

        if ($product_result && $product_result->num_rows > 0) {
            $product_row = $product_result->fetch_assoc();
            $product_brand = $product_row['brand'];
            $product_model = $product_row['model'];
            $product_price = $product_row['price'];

            // Get the user's email
            $user_sql = "SELECT email FROM user WHERE id = '$user_id'";
            $user_result = $conn->query($user_sql);

            if ($user_result && $user_result->num_rows > 0) {
                $user_row = $user_result->fetch_assoc();
                $user_email = $user_row['email'];

                // Send order details to the user's email
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'teccom.srilanka@gmail.com'; // Your Gmail
                    $mail->Password = 'lriahnuzhugqfzan';   // App Password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('teccom.srilanka@gmail.com', 'Stock Management System');
                    $mail->addAddress($user_email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Order Confirmation';
                    $mail->Body = "
                        <h2>Order Confirmation</h2>
                        <p>Thank you for your order! Here are your order details:</p>
                        <ul>
                            <li><strong>Order ID:</strong> $order_id</li>
                            <li><strong>Product ID:</strong> $product_id</li>
                            <li><strong>Brand:</strong> $product_brand</li>
                            <li><strong>Model:</strong> $product_model</li>
                            <li><strong>Price:</strong> Rs.$product_price</li>
                            <li><strong>Order Date:</strong> $order_date</li>
                        </ul>
                        <p>We will process your order shortly.</p>
                    ";

                    $mail->send();
                    echo "<script>alert('Order has been submitted successfully! Check your email for confirmation.');</script>";
                } catch (Exception $e) {
                    echo "<script>alert('Order placed, but failed to send email: {$mail->ErrorInfo}');</script>";
                }
            }
        } else {
            echo "<script>alert('Product details not found.');</script>";
        }
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
        /* Base Styling */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #3498db, #4CAF50);
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header h3 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        /* Search Form */
        .search-form {
            max-width: 800px;
            margin: 20px auto;
            display: flex;
            gap: 10px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .search-form input,
        .search-form select {
            flex: 1;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-form button {
            padding: 12px 20px;
            background: #4CAF50;
            color: white;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .search-form button:hover {
            background: #45a049;
        }

        /* Product Container */
        .container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Product Card */
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 300px;
            height: 450px;
            margin: 0 auto;
            /* Center-align smaller cards */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 200px;
            /* Match card width */
            height: 150px;
            /* Fixed height */
            object-fit: cover;
            /* Maintain aspect ratio and fill */
        }

        .card h3 {
            font-size: 18px;
            margin: 10px 0;
            color: #3498db;
        }

        .card .category {
            font-size: 14px;
            color: #7f8c8d;
        }

        .card .stock {
            font-size: 16px;
            color: #e74c3c;
            font-weight: bold;
        }

        .button-container {
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #3498db;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #2980b9;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Center the modal */
            width: 90%;
            /* Default width */
            max-width: 500px;
            /* Limit maximum width */
            background: rgba(255, 255, 255, 1);
            z-index: 1000;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            text-align: center;
        }

        .close {
            font-size: 24px;
            cursor: pointer;
            color: #333;
            float: right;
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
            <?php if (isset($_SESSION['name'])): ?>

                <h3 style="text-transform: uppercase;">Welcome,<?php echo htmlspecialchars($_SESSION['name']); ?> to Buying & Selling System's Product View Section</h3>
                <span style="display: flex; gap: 10px; justify-content:center;">
                    <form method="POST" style="margin: 0;">
                        <button type="submit" name="logout" class="logout-btn" style="margin: 0;">Logout</button>
                    </form>
                    <a href="../index.php" class="btn btn-primary" style="text-decoration: none; padding: 5px 20px; background-color: #007bff; color: white; border-radius: 5px; display: inline-block;">Back To Home</a>
                </span>


            <?php else: ?>
                <!-- Show login and register if not logged in -->
                <a href="../register.php" class="btn btn-danger">Register</a>
                <a href="../login.php" class="btn btn-success">Login</a>
            <?php endif; ?>
        </span>
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

                // Check if the user is logged in before showing the "Order Now" button
                if (isset($_SESSION['user_id'])) {
                    echo "<form method='POST' action=''>
                              <input type='hidden' name='product_id' value='{$row['id']}'>
                              <input type='hidden' name='product_brand' value='{$row['brand']}'>
                              <input type='hidden' name='product_model' value='{$row['model']}'>
                              <input type='hidden' name='product_price' value='{$row['price']}'>
                              <button type='submit' onclick='return confirmOrder();' name='order_product' class='btn' 
                                      style='background-color: #4CAF50;' >Order Now</button>
                          </form>";
                } else {
                    echo "<a href='/stock/login.php' class='btn' style='background-color: #e74c3c;'>Login to Order</a>";
                }

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
        function confirmOrder() {
            return confirm("Are you sure you want to place this order?");
        }
    </script>
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