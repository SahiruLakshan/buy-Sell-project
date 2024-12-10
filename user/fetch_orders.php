<?php
include_once("../database/db_connect.php");

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // SQL to fetch orders along with user details
    $sql = "SELECT u.name, u.email, u.address, u.phone_number AS phone, o.order_date, o.status, o.id AS order_id
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.product_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    echo json_encode($orders);
} else {
    echo json_encode([]);
}
?>
