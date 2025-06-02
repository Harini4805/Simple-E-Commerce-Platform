<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Here you would typically:
    // 1. Update the order status in your database
    // 2. Send confirmation email to the customer
    // 3. Generate order receipt
    
    $_SESSION['success'] = "Order confirmed! You can pay when the order is delivered.";
    header("Location: payment.php");
    exit();
} else {
    header("Location: payment.php");
    exit();
}
?> 