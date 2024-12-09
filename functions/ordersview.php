<?php
include_once("../database/db_connect.php");

session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
    header("Location: /stock/login.php");
    exit;
}

$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

// Handle order deletion
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    $sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Order deleted successfully!'); window.location.href='ordersview.php';</script>";
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
    <title>Orders View Table</title>
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

        .header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
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
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #ffa500;
        }

        .btn-delete {
            background-color: #f44336;
        }

        .btn-edit:hover {
            background-color: #e69500;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
        }

        .back-header {
            display: flex;
            align-items: center;
        }

        .back-header h1 {
            margin-right: 100px;
            /* Adjust spacing between title and button */
        }

        .back-btn {
            background-color: red;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: darkred;
        }
    </style>
</head>

<body>
    <div class="header">
      Order Management
    </div>
    <div class="container">
        <span class="back-header">
            <h1>Order List</h1>
            <a href="./dashboard.php" class="btn back-btn">Back</a>
        </span>

        <table>
            <thead>
                <tr>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th></th>
                </tr>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['user_id']}</td>
                                <td>{$row['product']}</td>
                                <td>{$row['price']}</td>
                                <td>{$row['quantity']}</td>
                                <td>

                            <form action='' method='POST' style='display:inline;' onsubmit=\"return confirm('Are you sure you want to delete this product?');\">
                                <input type='hidden' name='delete_id' value='{$row['id']}'>
                                <input type='submit' class='btn btn-delete' value='Delete'>
                            </form>
                        </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>