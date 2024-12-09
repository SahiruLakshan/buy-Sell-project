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
$sql = "SELECT category, brand, model, price, description, colors, photo FROM products WHERE user_id = ?";
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
            max-width: 800px;
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
        .products-table th, .products-table td {
            text-align: left;
        }
        .product-photo {
            width: 100px;
            height: 100px;
            object-fit: cover;
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
                                    <img src="uploads/<?php echo htmlspecialchars($product['photo']); ?>" alt="Product Photo" class="product-photo">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-warning">No products submitted yet.</p>
            <?php endif; ?>
        </div>

        <div class="text-center mt-4">
            <a href="../index.php" class="btn btn-primary">Back to Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
