<?php
include('../includes/connect.php');
include('../functions/common_function.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='user_login.php';</script>";
    exit();
}

// Function to validate card details
function validateCard($card_number, $expiry_date, $cvv) {
    // Validate card number (16 digits)
    if (!preg_match('/^\d{16}$/', $card_number)) {
        return false;
    }
    
    // Validate expiry date (MM/YY format)
    if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiry_date)) {
        return false;
    }
    
    // Validate CVV (3 or 4 digits)
    if (!preg_match('/^\d{3,4}$/', $cvv)) {
        return false;
    }
    
    return true;
}

// Function to update order status
function updateOrderStatus($con, $order_id, $status, $payment_mode) {
    $update_order = "UPDATE user_orders SET order_status='$status' WHERE order_id=$order_id";
    mysqli_query($con, $update_order);
    
    // Insert payment record
    $amount = $_POST['amount'] ?? 0;
    $invoice_number = time() . rand(1000, 9999);
    $payment_date = date('Y-m-d H:i:s');
    
    $insert_payment = "INSERT INTO user_payments (order_id, invoice_number, amount, payment_mode, payment_date) 
                      VALUES ($order_id, '$invoice_number', $amount, '$payment_mode', '$payment_date')";
    mysqli_query($con, $insert_payment);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $payment_method = $_POST['payment_method'];
    $username = $_SESSION['username'];
    
    // Get user details
    $get_user = "SELECT * FROM user_table WHERE username='$username'";
    $result = mysqli_query($con, $get_user);
    $user_data = mysqli_fetch_assoc($result);
    
    // Get order details
    $get_order = "SELECT * FROM user_orders WHERE order_id=$order_id AND user_id={$user_data['user_id']}";
    $result_order = mysqli_query($con, $get_order);
    $order_data = mysqli_fetch_assoc($result_order);
    
    if (!$order_data) {
        echo "<script>alert('Invalid order!')</script>";
        echo "<script>window.location.href='profile.php?my_orders';</script>";
        exit();
    }
    
    // Process payment based on method
    switch ($payment_method) {
        case 'upi':
            $utr_number = mysqli_real_escape_string($con, $_POST['utr_number']);
            
            if (empty($utr_number)) {
                echo "<script>alert('Please enter UTR number!')</script>";
                echo "<script>window.location.href='confirm_payment.php?order_id=$order_id';</script>";
                exit();
            }
            
            // Here you would typically verify the UTR with the payment gateway
            // For now, we'll just update the order status
            updateOrderStatus($con, $order_id, 'complete', 'UPI');
            
            // Redirect to success page
            echo "<script>window.location.href='payment_receipt.php?order_id=$order_id';</script>";
            break;
            
        case 'card':
            $card_name = mysqli_real_escape_string($con, $_POST['card_name']);
            $card_number = mysqli_real_escape_string($con, $_POST['card_number']);
            $expiry_date = mysqli_real_escape_string($con, $_POST['expiry_date']);
            $cvv = mysqli_real_escape_string($con, $_POST['cvv']);
            
            if (!validateCard($card_number, $expiry_date, $cvv)) {
                echo "<script>alert('Invalid card details!')</script>";
                echo "<script>window.location.href='confirm_payment.php?order_id=$order_id';</script>";
                exit();
            }
            
            // Here you would typically process the card payment through a payment gateway
            // For now, we'll just update the order status
            updateOrderStatus($con, $order_id, 'complete', 'Card');
            
            // Redirect to success page
            echo "<script>window.location.href='payment_receipt.php?order_id=$order_id';</script>";
            break;
            
        case 'netbanking':
            $bank = mysqli_real_escape_string($con, $_POST['bank']);
            
            if (empty($bank)) {
                echo "<script>alert('Please select a bank!')</script>";
                echo "<script>window.location.href='confirm_payment.php?order_id=$order_id';</script>";
                exit();
            }
            
            // Here you would typically redirect to the bank's payment gateway
            // For now, we'll just update the order status
            updateOrderStatus($con, $order_id, 'complete', 'Net Banking');
            
            // Redirect to success page
            echo "<script>window.location.href='payment_receipt.php?order_id=$order_id';</script>";
            break;
            
        case 'wallet':
            $wallet = mysqli_real_escape_string($con, $_POST['wallet'] ?? '');
            
            if (empty($wallet)) {
                echo "<script>alert('Please select a wallet!')</script>";
                echo "<script>window.location.href='confirm_payment.php?order_id=$order_id';</script>";
                exit();
            }
            
            // Here you would typically redirect to the wallet's payment gateway
            // For now, we'll just update the order status
            updateOrderStatus($con, $order_id, 'complete', 'Wallet');
            
            // Redirect to success page
            echo "<script>window.location.href='payment_receipt.php?order_id=$order_id';</script>";
            break;
            
        case 'cod':
            $delivery_note = mysqli_real_escape_string($con, $_POST['delivery_note'] ?? '');
            
            // Update order with delivery note if provided
            if (!empty($delivery_note)) {
                $update_note = "UPDATE user_orders SET delivery_note='$delivery_note' WHERE order_id=$order_id";
                mysqli_query($con, $update_note);
            }
            
            // Update order status for COD
            updateOrderStatus($con, $order_id, 'pending', 'Cash on Delivery');
            
            // Redirect to success page
            echo "<script>window.location.href='payment_receipt.php?order_id=$order_id';</script>";
            break;
            
        default:
            echo "<script>alert('Invalid payment method!')</script>";
            echo "<script>window.location.href='confirm_payment.php?order_id=$order_id';</script>";
            break;
    }
} else {
    // If not POST request, redirect to orders page
    echo "<script>window.location.href='profile.php?my_orders';</script>";
}
?> 