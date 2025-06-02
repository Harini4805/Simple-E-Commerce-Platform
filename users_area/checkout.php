<?php
include('../includes/connect.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='user_login.php';</script>";
    exit();
}

$username = $_SESSION['username'];
$get_user = "SELECT * FROM user_table WHERE username='$username'";
$result = mysqli_query($con, $get_user);
$row_fetch = mysqli_fetch_assoc($result);
$user_id = $row_fetch['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecommerce Website - Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .full-page {
            padding: 40px 50px;
            min-height: 100vh;
        }
    </style>
</head>
<body>

<!-- Checkout/Login Section -->
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <?php
            if (!isset($_SESSION['username'])) {
                include('user_login.php');
            } else {
                include('payment.php');
            }
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<?php
// Process order placement
if(isset($_POST['place_order'])) {
    $amount_due = $_POST['amount_due'];
    $total_products = $_POST['total_products'];
    $invoice_number = mt_rand();
    $order_status = 'pending';

    // Insert into user_orders table
    $insert_orders = "INSERT INTO user_orders (user_id, amount_due, total_products, invoice_number, order_date, order_status) 
                     VALUES ($user_id, $amount_due, $total_products, $invoice_number, NOW(), '$order_status')";
    $result_query = mysqli_query($con, $insert_orders);

    if($result_query) {
        // Get the order_id of the newly inserted order
        $order_id = mysqli_insert_id($con);
        
        // Insert into orders_pending table
        $cart_query = "SELECT * FROM cart_details WHERE ip_address='$ip'";
        $result = mysqli_query($con, $cart_query);
        
        while($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];
            
            $insert_pending = "INSERT INTO orders_pending (order_id, user_id, product_id, quantity) 
                             VALUES ($order_id, $user_id, $product_id, $quantity)";
            mysqli_query($con, $insert_pending);
        }
        
        // Clear the cart
        $empty_cart = "DELETE FROM cart_details WHERE ip_address='$ip'";
        mysqli_query($con, $empty_cart);
        
        echo "<script>alert('Order placed successfully!')</script>";
        echo "<script>window.location.href='profile.php?my_orders';</script>";
    } else {
        echo "<script>alert('Error placing order!')</script>";
        echo "<script>window.location.href='checkout.php';</script>";
    }
}
?>

</body>
</html>
