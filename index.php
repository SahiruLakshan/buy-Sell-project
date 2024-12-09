<?php
session_start(); // Start the session to handle user login state
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Agricultural Product Wholesale Trade Platform</title>
    <link rel="stylesheet" href="home.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
<header class="header">
    <a href="#" class="logo">All products</a>

    <?php if (isset($_SESSION['name'])): ?>
        <!-- Show logout, profile, and username if logged in -->
        <span class="text-success">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
        <a href="./user/profile.php" class="btn btn-info">Profile</a>
        <a href="logout.php" class="btn btn-warning">Logout</a>
    <?php else: ?>
        <!-- Show login and register if not logged in -->
        <a href="register.php" class="btn btn-danger">Register</a>
        <a href="login.php" class="btn btn-success">Login</a>
    <?php endif; ?>

    <nav class="nav-items">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
    </nav>
</header>
    <main>
        <div class="intro">
            <h1>Online Buying & Selling Platform</h1>
            <p><b>Buying and Selling </b></p>
            <span>
                <a href="./user/home.php" class="btn btn-primary">View & Buy Products</a>&nbsp;&nbsp;<a href="./functions/addproduct.php" class="btn btn-warning">Add Products</a>
            </span>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
