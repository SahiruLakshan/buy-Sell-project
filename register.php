<?php
include_once("database/db_connect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Autoload PHPMailer

include_once("database/db_connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password
    $token = bin2hex(random_bytes(16)); // Generate a unique token

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM user WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo "<script>alert('Email already exists');</script>";
    } else {
        // Insert new user into database with verification token
        $sql = "INSERT INTO user (name, email, address, phone_number, password, verification_token, is_verified) VALUES (?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $address, $phone_number, $password, $token);
        
        if ($stmt->execute()) {
            // Send verification email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Set your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'teccom.srilanka@gmail.com'; // Your email address
                $mail->Password = 'lriahnuzhugqfzan'; // Your email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('teccom.srilanka@gmail.com', 'Stock Management System');
                $mail->addAddress($email, $name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Email Verification';
                $mail->Body = "
                    Hi $name,<br><br>
                    Thank you for Registering. Please verify your email by clicking the link below:<br>
                    <a href='http://localhost/stock/verify.php?token=$token'>Click here to Verify Email</a>
                ";

                $mail->send();
                echo "<script>alert('Registration successful! Check your email to verify your account.'); window.location.href='verifyalert.php';</script>";
            } catch (Exception $e) {
                echo "Error sending email: {$mail->ErrorInfo}";
            }
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
            background-image: url('https://media.licdn.com/dms/image/v2/D4E12AQFfnhRzC8NDqA/article-cover_image-shrink_720_1280/article-cover_image-shrink_720_1280/0/1708313622003?e=2147483647&v=beta&t=uexCt_3lIMuj8YoAQQIUbWD76ZKkAldpqxqeF0TjRrM'); 
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
            background-color: blue; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
        }

        button:hover {
            background-color: red;
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
    <h1 style="text-align: center;">Buy & Sell Platform</h1>
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
