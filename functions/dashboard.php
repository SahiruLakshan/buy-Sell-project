<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
    header("Location: /stock/login.php");
    exit;
}

if (isset($_POST['logout'])) {
    // Destroy the session and redirect to login page
    session_destroy();
    header("Location: /stock/login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/stock/img/icon.png" type="image/x-icon">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            background-image: url('../img/bgi.png');
        }

        .header {
            background-color: #3498db;
            color: white;
            padding: 20px;
            font-size: 28px;
            letter-spacing: 1px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header span {
            margin-left: 20px;
        }

        .button-container {
            display: flex;
            align-items: center;
            gap: 20px; /* Space between buttons */
        }

        .logout-btn, .view-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
        }

        .view-btn {
            background-color: #2ecc71;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .view-btn:hover {
            background-color: #27ae60;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .tab {
            background-color: #ffffff;
            width: 300px;
            height: 250px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        .tab:hover {
            background-color: #3498db;
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        a {
            text-decoration: none;
            color: inherit;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: color 0.3s ease;
        }

        /* Specific tab colors */
        .tab:nth-child(1) {
            background-color: #f39c12;
        }

        .tab:nth-child(2) {
            background-color: #27ae60;
        }

        .tab:nth-child(3) {
            background-color: #e74c3c;
        }

        .tab:nth-child(4) {
            background-color: #8e44ad;
        }

        .tab:nth-child(1):hover {
            background-color: #d35400;
        }

        .tab:nth-child(2):hover {
            background-color: #2ecc71;
        }

        .tab:nth-child(3):hover {
            background-color: #c0392b;
        }

        .tab:nth-child(4):hover {
            background-color: #9b59b6;
        }

        .container {
            max-width: 1200px;
            margin: 10px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
            padding: 50px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <span>Welcome, Admin - <?php echo $_SESSION['user_name']; ?> to Chairs Stock Management System Dashboard</span>
        <div class="button-container">
            <a href="../user/home.php" style="margin: 0;" class="view-btn">Home Page</a>
            <form method="POST" style="margin: 0;">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="tab">
            <a href="addproduct.php">Add Product</a>
        </div>
        <div class="tab">
            <a href="productview.php">View Products</a>
        </div>
        <div class="tab">
            <a href="users.php">Users Handle</a>
        </div>
        <div class="tab">
            <a href="ordersview.php">Orders</a>
        </div>
    </div>
</body>

</html>
