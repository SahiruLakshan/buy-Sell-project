<?php
session_start(); // Start the session to handle user login state
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }

        .hero-section {
            color: #fff;
            padding: 100px 20px;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 48px;
            font-weight: bold;
        }

        .hero-section p {
            font-size: 20px;
            margin-top: 15px;
        }

        .about-section {
            margin: 50px 0;
        }

        .about-section img {
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .about-section h2 {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .about-section p {
            font-size: 18px;
            line-height: 1.8;
        }

        .values-section {
            background: #f1f1f1;
            padding: 50px 20px;
            border-radius: 15px;
        }

        .values-section h2 {
            font-size: 32px;
            margin-bottom: 20px;
            text-align: center;
        }

        .value-card {
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .value-card h3 {
            font-size: 24px;
            margin-top: 15px;
        }

        .value-card p {
            font-size: 16px;
            color: #6c757d;
        }

        .team-section h2 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 40px;
        }

        .team-card img {
            border-radius: 50%;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .team-card h3 {
            font-size: 20px;
            margin-top: 15px;
        }

        .team-card p {
            color: #6c757d;
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
    <!-- Hero Section -->
    <div class="hero-section bg-dark">
        <h1>About Us</h1>
        <p>Learn more about our journey, mission, and values.</p>
    </div>

    <!-- About Section -->


    <div class="text-center mt-3" style="margin: 100px;text-align:justify">
        <h2>Who We Are</h2>
        <p>We are a team of dedicated professionals driven by passion and innovation. Our mission is to create exceptional solutions that empower businesses and individuals alike. With a commitment to excellence, we continually strive to push the boundaries of what's possible.</p>
    </div>



    <!-- Values Section -->
    <div class="container values-section">
        <h2>Our Core Values</h2>
        <div class="row text-center mt-4">
            <div class="col-lg-4">
                <div class="value-card">
                    <i class="bi bi-lightbulb-fill text-primary" style="font-size: 40px;"></i>
                    <h3>Innovation</h3>
                    <p>We foster creativity and embrace change to stay ahead of the curve.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="value-card">
                    <i class="bi bi-people-fill text-primary" style="font-size: 40px;"></i>
                    <h3>Collaboration</h3>
                    <p>Our success is built on teamwork and mutual respect.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="value-card">
                    <i class="bi bi-shield-lock-fill text-primary" style="font-size: 40px;"></i>
                    <h3>Integrity</h3>
                    <p>We conduct our business with honesty and transparency.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>

</html>