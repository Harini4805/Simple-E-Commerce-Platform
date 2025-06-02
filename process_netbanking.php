<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bank = filter_input(INPUT_POST, 'bank', FILTER_SANITIZE_STRING);
    $account_name = filter_input(INPUT_POST, 'account_name', FILTER_SANITIZE_STRING);
    
    // Basic validation
    if (empty($bank) || empty($account_name)) {
        $_SESSION['error'] = "Bank and account holder name are required";
        header("Location: payment.php");
        exit();
    }

    // Validate bank selection
    $valid_banks = ['sbi', 'hdfc', 'icici', 'axis'];
    if (!in_array($bank, $valid_banks)) {
        $_SESSION['error'] = "Invalid bank selection";
        header("Location: payment.php");
        exit();
    }

    // Here you would typically:
    // 1. Connect to the selected bank's API
    // 2. Initiate the net banking session
    // 3. Redirect to the bank's login page
    
    // For demonstration, we'll just show a success message
    $_SESSION['success'] = "Redirecting to " . strtoupper($bank) . " net banking...";
    header("Location: payment.php");
    exit();
} else {
    header("Location: payment.php");
    exit();
}
?> 