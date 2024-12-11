<?php
session_start(); // Start the session to handle user login state
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }

        .contact-header {

            color: #fff;
            padding: 50px 20px;
            text-align: center;
            border-radius: 0 0 30px 30px;
        }

        .contact-header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .contact-header p {
            font-size: 18px;
        }

        .contact-form {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: -50px;
        }

        .form-control:focus {
            border-color: #4834d4;
            box-shadow: 0 0 5px rgba(72, 52, 212, 0.5);
        }

        .btn-primary {
            background-color: #4834d4;
            border-color: #4834d4;
        }

        .btn-primary:hover {
            background-color: #6c63ff;
            border-color: #6c63ff;
        }

        .contact-info {
            text-align: center;
            margin-top: 30px;
        }

        .contact-info h3 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .contact-info p {
            font-size: 16px;
            color: #6c757d;
        }

        .social-icons a {
            color: #6c757d;
            font-size: 24px;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #4834d4;
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
                        <a class="nav-link fw-semibold" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="about.php">About</a>
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
    <div class="contact-header bg-dark">
        <h1>Contact Us</h1>
        <p>We’d love to hear from you. Get in touch through the form below!</p>
    </div>

    <div class="container">
        <div class="contact-info">
            <h3>Reach Out To Us</h3>
            <p><strong>Phone:</strong> +123 456 7890</p>
            <p><strong>Email:</strong> contact@example.com</p>
            <p><strong>Address:</strong> 123 Street, City, Country</p>
            <div class="social-icons">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHz" crossorigin="anonymous"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>

</html>