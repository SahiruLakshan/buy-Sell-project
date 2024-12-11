<?php
// Include the database connection file
include_once("../database/db_connect.php");
session_start();

// Assuming user details are stored in session variables
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['name'];
$address = $_SESSION['address'] ?? 'No Address Provided';
$email = $_SESSION['email'] ?? 'No Email Provided';
$phone = $_SESSION['phone_number'] ?? 'No Phone Provided';
$image = $_SESSION['image'] ?? 'default-profile.png'; // Use default image if not provided

// Fetch products submitted by the user
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
$sql = "SELECT id, category, brand, model, price, description, colors, photo FROM product WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .profile-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile-container h2 {
            margin-bottom: 20px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .profile-info {
            font-size: 1.1em;
            margin-bottom: 10px;
            text-align: left;
        }

        .products-table {
            margin-top: 30px;
        }

        .products-table th,
        .products-table td {
            text-align: left;
        }

        .product-photo {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .orders-table {
            margin-top: 30px;
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
                        <a class="nav-link fw-semibold" href="../index.php">Home</a>
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
                        <!-- Welcome message and buttons aligned to the right -->
                        <span class="text-success me-3">Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong></span>
                        <a href="./user/profile.php" class="btn btn-info btn-sm me-2">Profile</a>
                        <a href="logout.php" class="btn btn-warning btn-sm">Logout</a>
                    <?php else: ?>
                        <!-- Login and register buttons -->
                        <a href="register.php" class="btn btn-danger btn-sm me-2">Register</a>
                        <a href="login.php" class="btn btn-success btn-sm">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <div class="profile-container bg-light">
        <h2 class="text-primary">User Profile</h2>
        <!-- Display user image -->
        <img src="uploads/<?php echo htmlspecialchars($image); ?>" alt="Profile Image" class="profile-image">
        <div class="profile-info">
            <strong>Name:</strong> <?php echo htmlspecialchars($name); ?>
        </div>
        <div class="profile-info">
            <strong>Address:</strong> <?php echo htmlspecialchars($address); ?>
        </div>
        <div class="profile-info">
            <strong>Email:</strong> <?php echo htmlspecialchars($email); ?>
        </div>
        <div class="profile-info">
            <strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?>
        </div>

        <!-- Display user's products -->
        <div class="products-table">
            <h3 class="text-success">Submitted Products</h3>
            <?php if (count($products) > 0): ?>
                <table class="table table-striped table-bordered" style="table-layout: fixed; width: 100%;">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 100px;">Category</th>
                            <th style="width: 100px;">Brand</th>
                            <th style="width: 100px;">Model</th>
                            <th style="width: 100px;">Price</th>
                            <th style="width: 250px;">Description</th>
                            <th style="width: 100px;">Colors</th>
                            <th style="width: 100px;">Photo</th>
                            <th style="width: 250px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td style="word-wrap: break-word; word-break: break-word;">
                                    <?php echo htmlspecialchars($product['category']); ?>
                                </td>
                                <td style="word-wrap: break-word; word-break: break-word;">
                                    <?php echo htmlspecialchars($product['brand']); ?>
                                </td>
                                <td style="word-wrap: break-word; word-break: break-word;">
                                    <?php echo htmlspecialchars($product['model']); ?>
                                </td>
                                <td style="word-wrap: break-word; word-break: break-word;">
                                    <?php echo htmlspecialchars($product['price']); ?>
                                </td>
                                <td style="word-wrap: break-word; word-break: break-word;">
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </td>
                                <td style="word-wrap: break-word; word-break: break-word;">
                                    <?php echo htmlspecialchars($product['colors']); ?>
                                </td>
                                <td>
                                    <img src="../uploads/<?php echo htmlspecialchars($product['photo']); ?>" alt="Product Photo" class="img-fluid" style="max-height: 80px; max-width: 100%;">
                                </td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <button class="btn btn-info btn-sm me-2" onclick="showOrders(<?php echo $product['id']; ?>)">Show Orders</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteProduct(<?php echo $product['id']; ?>)">Delete Product</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


            <?php else: ?>
                <p class="text-warning">No products submitted yet.</p>
            <?php endif; ?>
        </div>

        <!-- Orders table (hidden initially) -->
        <div class="orders-table" id="ordersTable" style="display: none;">
            <h3 class="text-primary">Orders</h3>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th style="width: 100px;">Order Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="ordersBody">
                    <!-- Orders will be dynamically loaded here -->
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="../index.php" class="btn btn-primary">Back to Home</a>
        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        function showOrders(productId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_orders.php?product_id=' + productId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const orders = JSON.parse(xhr.responseText);
                    const ordersBody = document.getElementById('ordersBody');
                    ordersBody.innerHTML = '';

                    if (orders.length > 0) {
                        orders.forEach(order => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td>${order.name}</td>
                        <td>${order.email}</td>
                        <td>${order.address}</td>
                        <td>${order.phone}</td>
                        <td>${order.order_date}</td>
                        <td>
                            <button style="text-align: center; white-space: nowrap;" class="btn btn-success complete-order-btn" 
                                    ${order.status === 'Complete' ? 'disabled' : ''} 
                                    onclick="completeOrder(${order.order_id}, this)">
                                ${order.status === 'Complete' ? 'Completed' : 'Complete Order'}
                            </button>
                        </td>
                    `;
                            ordersBody.appendChild(row);
                        });
                    } else {
                        const row = document.createElement('tr');
                        row.innerHTML = '<td colspan="7">No orders found for this product.</td>';
                        ordersBody.appendChild(row);
                    }

                    document.getElementById('ordersTable').style.display = 'block';
                }
            };
            xhr.send();
        }

        function completeOrder(orderId, button) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'complete_order.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        button.innerText = 'Completed';
                        button.disabled = true;
                        button.classList.remove('btn-success');
                        button.classList.add('btn-secondary');
                    } else {
                        alert('Failed to complete the order. Please try again.');
                    }
                }
            };
            xhr.send('order_id=' + orderId);
        }

        function deleteProduct(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                // Create an XMLHttpRequest
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_product.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Handle the response
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert(response.message);
                            location.reload(); // Reload the page to update the product list
                        } else {
                            alert("Error: " + response.message);
                        }
                    }
                };

                // Send the request with product_id
                xhr.send("product_id=" + productId);
            }
        }
    </script>
    
</body>

</html>