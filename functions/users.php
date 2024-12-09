<?php
include_once("../database/db_connect.php");
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
    header("Location: /stock/login.php");
    exit;
}

// Handle user deletion
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    // Prevent deletion of the admin itself
    if ($id == $_SESSION['user_id']) {
        echo "<script>alert('You cannot delete your own account');</script>";
    } else {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>alert('User deleted successfully!'); window.location.href='users.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

// Fetch users
$sql = "SELECT id, name, email,address,phone_number, user_type FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/stock/img/icon.png" type="image/x-icon">
    <title>Users Handle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background-image: url('../img/bgi.png');
        }

        .header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            background-color: #f44336;
            /* Red background for delete button */
        }

        .btn:hover {
            background-color: #d32f2f;
            /* Darker red on hover */
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
        User Management
    </div>

    <div class="container">
        <span class="back-header">
            <h1>Registered User List</h1>
            <a href="./dashboard.php" class="btn back-btn">Back</a>
        </span>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <!-- <th>User Type</th> -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $user_type = ($row['user_type'] == 1) ? 'Admin' : 'Ordinary User';
                        echo "<tr>";
                        echo "<td>{$row['name']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['address']}</td>";
                        echo "<td>{$row['phone_number']}</td>";
                        // echo "<td>{$user_type}</td>";
                        // if ($row['user_type'] == 0) { // Only allow deletion of ordinary users
                        //     echo "<td>
                        //         <form action='' method='POST' style='display:inline;' onsubmit=\"return confirm('Are you sure you want to delete this user?');\">
                        //             <input type='hidden' name='delete_id' value='{$row['id']}'>
                        //             <input type='submit' class='btn' value='Delete'>
                        //         </form>
                        //     </td>";
                        // } else {
                        //     echo "<td>—</td>"; // No action for admins
                        // }
                        // echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>