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