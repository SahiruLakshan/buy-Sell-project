<?php
include_once("database/db_connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo "<script>alert('Email already exists');</script>";
    } else {
        // Insert new user into database
        $sql = "INSERT INTO users (name, email,address,phone_number, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name,$email,$address,$phone_number,$password);
        
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/icon.png" type="image/x-icon">
    <title>Register</title>
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

        .form-container h2 { 
            text-align: center; 
            margin-bottom: 20px;
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
    <h1 style="text-align: center;">Stock Management System</h1>
    <h2>Register</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="address" placeholder="Address" required>
        <input type="text" name="phone_number" placeholder="Phone Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>


</body>
</html>
