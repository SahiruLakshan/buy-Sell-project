<?php
include_once("../database/db_connect.php");

// session_start();

// // Check if the user is logged in, otherwise redirect to login page
// if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
//     header("Location: /stock/login.php");
//     exit;
// }

// Handle product deletion
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully!'); window.location.href='productview.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Handle category filter
$category_filter = "";
if (isset($_GET['category']) && $_GET['category'] != "") {
    $category_filter = $_GET['category'];
    $sql = "SELECT * FROM products WHERE category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category_filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Fetch all products if no category is selected
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/stock/img/icon.png" type="image/x-icon">
    <title>Product View Table</title>
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

        th, td {
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
    </style>
</head>
<body>

    <div class="container">
        <h1>Product List</h1>

        <!-- Category Filter Form -->
        <form action="" method="GET">
            <label for="category">Filter by Category:</label>
            <select name="category" id="category">
                <option value="">All Categories</option>
                <option value="High Back" <?php if ($category_filter == 'High Back') echo 'selected'; ?>>High Back</option>
                <option value="Low Back" <?php if ($category_filter == 'Low Back') echo 'selected'; ?>>Low Back</option>
                <option value="Task" <?php if ($category_filter == 'Task') echo 'selected'; ?>>Task</option>
                <option value="Visitor" <?php if ($category_filter == 'Visitor') echo 'selected'; ?>>Visitor</option>
                <option value="Waiting Chair" <?php if ($category_filter == 'Waiting Chair') echo 'selected'; ?>>Waiting Chair</option>
                <option value="Lecture Hall Chair" <?php if ($category_filter == 'Lecture Hall Chair') echo 'selected'; ?>>Lecture Hall Chair</option>
            </select>
            <input type='submit' class='btn btn-delete' value='Filter'>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Price</th>
                    <th>Photo</th>
                    <th>Description</th>
                    <th>Warranty</th>
                    <th>Colors</th>
                    <th>Available Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['category']}</td>";
                        echo "<td>{$row['brand']}</td>";
                        echo "<td>{$row['model']}</td>";
                        echo "<td>{$row['price']}</td>";
                        echo "<td><img src='../uploads/{$row['photo']}' alt='{$row['model']}' style='width: 100px;'></td>";
                        echo "<td>{$row['description']}</td>";
                        echo "<td>{$row['colors']}</td>";
                        echo "<td>
                            <a href='editproduct.php?id={$row['id']}' class='btn btn-edit'>Edit</a>
                            <form action='' method='POST' style='display:inline;' onsubmit=\"return confirm('Are you sure you want to delete this product?');\">
                                <input type='hidden' name='delete_id' value='{$row['id']}'>
                                <input type='submit' class='btn btn-delete' value='Delete'>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No products found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
