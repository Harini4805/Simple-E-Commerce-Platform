<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utr_number = filter_input(INPUT_POST, 'utr_number', FILTER_SANITIZE_STRING);
    
    if (empty($utr_number)) {
        $_SESSION['error'] = "UTR number is required";
        header("Location: payment.php");
        exit();
    }

    // Here you would typically:
    // 1. Validate the UTR number format
    // 2. Check with your payment gateway if the transaction exists
    // 3. Update your database with the payment status
    
    // For demonstration, we'll just show a success message
    $_SESSION['success'] = "UPI payment verified successfully!";
    header("Location: payment.php");
    exit();
} else {
    header("Location: payment.php");
    exit();
}
?> 