<?php
include_once("database/db_connect.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token exists in the database
    $stmt = $conn->prepare("SELECT id FROM user WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Verify the user
        $update = $conn->prepare("UPDATE user SET is_verified = 1, verification_token = NULL WHERE verification_token = ?");
        $update->bind_param("s", $token);

        if ($update->execute()) {
            echo "<script>alert('Email verified successfully!'); window.location.href='login.php';</script>";
        } else {
            echo "Error verifying email.";
        }
    } else {
        echo "Invalid or expired token.";
    }
}
?>
