<?php
// Include the database connection file
include_once("database/db_connect.php");
session_start(); // Start a new session or resume the existing one

// Check if the request method is POST (form submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get email and password from the login form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement to fetch user by email
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql); // Prepare the SQL statement
    $stmt->bind_param("s", $email); // Bind the email to the prepared statement
    $stmt->execute(); // Execute the statement
    $result = $stmt->get_result(); // Get the result of the query

    // Check if a user with the provided email exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Fetch the user details as an associative array

        if (password_verify($password, $user['password'])) { // Verify the provided password with the hashed password stored in the database
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['address'] = $user['address'];
            $_SESSION['phone_number'] = $user['phone_number'];

            header("Location: ../stock/index.php");
            exit; // Ensure no further code is executed
        } else {
            echo "<script>alert('Invalid credentials');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/icon.png" type="image/x-icon">
    <title>Login</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-image: url('img/bgi.png'); 
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container { 
            max-width: 400px; 
            width: 100%;
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 { 
            text-align: center; 
            margin-bottom: 20px;
            color: #333;
        }

        input { 
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            box-sizing: border-box;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button { 
            width: 100%; 
            padding: 10px; 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Stock Management System</h1>
    <h2>Login</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
</html>
