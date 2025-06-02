<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card_name = filter_input(INPUT_POST, 'card_name', FILTER_SANITIZE_STRING);
    $card_number = filter_input(INPUT_POST, 'card_number', FILTER_SANITIZE_STRING);
    $expiry_date = filter_input(INPUT_POST, 'expiry_date', FILTER_SANITIZE_STRING);
    $cvv = filter_input(INPUT_POST, 'cvv', FILTER_SANITIZE_STRING);
    
    // Basic validation
    if (empty($card_name) || empty($card_number) || empty($expiry_date) || empty($cvv)) {
        $_SESSION['error'] = "All card details are required";
        header("Location: payment.php");
        exit();
    }

    // Validate card number (basic check for 16 digits)
    if (!preg_match('/^\d{16}$/', str_replace(' ', '', $card_number))) {
        $_SESSION['error'] = "Invalid card number";
        header("Location: payment.php");
        exit();
    }

    // Validate expiry date (MM/YY format)
    if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiry_date)) {
        $_SESSION['error'] = "Invalid expiry date format";
        header("Location: payment.php");
        exit();
    }

    // Validate CVV (3 or 4 digits)
    if (!preg_match('/^\d{3,4}$/', $cvv)) {
        $_SESSION['error'] = "Invalid CVV";
        header("Location: payment.php");
        exit();
    }

    // Here you would typically:
    // 1. Connect to your payment gateway
    // 2. Process the payment
    // 3. Update your database with the transaction details
    
    // For demonstration, we'll just show a success message
    $_SESSION['success'] = "Card payment processed successfully!";
    header("Location: payment.php");
    exit();
} else {
    header("Location: payment.php");
    exit();
}
?> 