<?php
include_once("../database/db_connect.php");

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Update the order status to 'Complete'
    $sql = "UPDATE orders SET status = 'Complete' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false]);
}
?>
