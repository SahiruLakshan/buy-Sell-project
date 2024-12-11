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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Stock Management System</title>
    <style>
        /* Base Styling */
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
            font-family: 'Arial', sans-serif;
        }

        /* Search Form */
        .search-form {
            max-width: 800px;
            margin: 20px auto;
            display: flex;
            gap: 10px;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .search-form input,
        .search-form select {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-form button {
            padding: 12px 20px;
            background: #28a745;
            color: white;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .search-form button:hover {
            background: #218838;
        }

        /* Product Container */
        #container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Product Card */
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 280px;
            height: 480px;
            margin: 0 auto;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card img:hover {
            transform: scale(1.1);
        }

        .card h3 {
            font-size: 20px;
            margin: 15px 0;
            color: #3498db;
        }

        .card .category {
            font-size: 14px;
            color: #7f8c8d;
        }

        .card .stock {
            font-size: 18px;
            color: #e74c3c;
            font-weight: bold;
        }

        .button-container {
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            padding: 5px 10px;
            background: #3498db;
            color: white;
            border-radius: 6px;
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
            width: 90%;
            max-width: 500px;
            background: #fff;
            z-index: 1000;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-content {
            padding: 20px;
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
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-primary" href="#">B & S PLATFORM</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="../contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="../about.php">About</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['name'])): ?>
                        <span class="text-success me-3">Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong></span>
                        <a href="../user/profile.php" class="btn btn-info btn-sm me-2">Profile</a>
                        <a href="../logout.php" class="btn btn-warning btn-sm">Logout</a>
                    <?php else: ?>
                        <a href="../register.php" class="btn btn-danger btn-sm me-2">Register</a>
                        <a href="../login.php" class="btn btn-success btn-sm">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

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

    <div class="container" id="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card'>";
                echo "<img src='../uploads/{$row['photo']}' alt='{$row['model']}'>";
                echo "<h3>{$row['brand']} {$row['model']}</h3>";
                echo "<p class='category'>Category: {$row['category']}</p>";
                echo "<h4 class='stock'>Price: Rs. {$row['price']}/=</h4>";
                echo "<div class='button-container'>";
                echo "<a href='#' class='btn' style='margin-bottom:10px' onclick='viewDetails(`{$row['photo']}`, `{$row['brand']} {$row['model']}`, `{$row['category']}`, `{$row['description']}`, `{$row['price']}`)'>View Details</a><br/>";

                if (isset($_SESSION['user_id'])) {
                    echo "<form method='POST' action=''>
                              <input type='hidden' name='product_id' value='{$row['id']}'>
                              <input type='hidden' name='product_brand' value='{$row['brand']}'>
                              <input type='hidden' name='product_model' value='{$row['model']}'>
                              <input type='hidden' name='product_price' value='{$row['price']}'>
                              <button type='submit' onclick='return confirmOrder();' name='order_product' class='btn btn-sm' style='background-color: #4CAF50;' >Order Now</button>
                          </form>";
                } else {
                    echo "<a href='/stock/login.php' class='btn btn-sm' style='background-color: #e74c3c;'>Login to Order</a>";
                }

                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No products found</p>";
        }
        ?>
    </div>
    <footer class="text-center text-lg-start bg-dark" style="color: white;">
        <!-- Section: Social media -->
        <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
            <!-- Left -->
            <div class="me-5 d-none d-lg-block">
                <span>Get connected with us on social networks:</span>
            </div>
            <!-- Left -->

            <!-- Right -->
            <div>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-google"></i>
                </a>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-linkedin"></i>
                </a>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-github"></i>
                </a>
            </div>
            <!-- Right -->
        </section>
        <!-- Section: Social media -->

        <!-- Section: Links  -->
        <section class="">
            <div class="container text-center text-md-start mt-5">
                <!-- Grid row -->
                <div class="row mt-3">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                        <!-- Content -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            B&S PVT(LTD)
                        </h6>
                        <p>
                            Here you can use rows and columns to organize your footer content. Lorem ipsum
                            dolor sit amet, consectetur adipisicing elit.
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            Products
                        </h6>
                        <p>
                            Chairs
                        </p>
                        <p>
                            Mobile Phones
                        </p>
                        <p>
                            Vehicles
                        </p>
                        <p>
                            Laptops
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            Useful links
                        </h6>
                        <p>
                            <a href="index.php" class="text-reset">Home</a>
                        </p>
                        <p>
                            <a href="about.php" class="text-reset">About</a>
                        </p>
                        <p>
                            <a href="contact.php" class="text-reset">Contact</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                        <p>Pitipana, Homgama</p>
                        <p>
                            buyandsell123@gmail.com
                        </p>
                        <p>+ 01 234 567 88</p>
                        <p>+ 01 234 567 89</p>
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grid row -->
            </div>
        </section>
        <!-- Section: Links  -->

        <!-- Copyright -->
        <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2021 Copyright:
            <a class="text-reset fw-bold" href="https://mdbootstrap.com/">MDBootstrap.com</a>
        </div>
        <!-- Copyright -->
    </footer>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHz" crossorigin="anonymous"></script>

</body>

</html>