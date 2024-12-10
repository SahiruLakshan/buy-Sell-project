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
            max-width: 1000px;
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
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Colors</th>
                            <th>Photo</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['category']); ?></td>
                                <td><?php echo htmlspecialchars($product['brand']); ?></td>
                                <td><?php echo htmlspecialchars($product['model']); ?></td>
                                <td><?php echo htmlspecialchars($product['price']); ?></td>
                                <td><?php echo htmlspecialchars($product['description']); ?></td>
                                <td><?php echo htmlspecialchars($product['colors']); ?></td>
                                <td>
                                    <img src="../uploads/<?php echo htmlspecialchars($product['photo']); ?>" alt="Product Photo" class="product-photo">
                                </td>
                                <td style="text-align: center; white-space: nowrap;"> <!-- Center buttons and prevent wrapping -->
                                    <button class="btn btn-info me-2" onclick="showOrders(<?php echo $product['id']; ?>)">Show Orders</button>
                                    <button class="btn btn-danger" onclick="deleteProduct(<?php echo $product['id']; ?>)">Delete Product</button>
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
            xhr.onload = function () {
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