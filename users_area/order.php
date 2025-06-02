<?php
include('../includes/connect.php');
include('../functions/common_function.php');

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}

// Getting total price and number of products
$get_ip_address = getIPAddress();
$total_price = 0;
$invoice_number = mt_rand();
$status = 'pending';

$cart_query = "SELECT * FROM `cart_details` WHERE ip_address='$get_ip_address'";
$result_cart = mysqli_query($con, $cart_query);
$count_products = mysqli_num_rows($result_cart);

// Calculate total price
while ($row_cart = mysqli_fetch_array($result_cart)) {
    $product_id = $row_cart['product_id'];
    $product_quantity = $row_cart['quantity'];
    if ($product_quantity == 0) {
        $product_quantity = 1;
    }

    $product_query = "SELECT * FROM `products` WHERE product_id=$product_id";
    $result_product = mysqli_query($con, $product_query);
    while ($row_product = mysqli_fetch_array($result_product)) {
        $price = $row_product['product_price'];
        $total_price += ($price * $product_quantity);
    }
}

// Insert into user_orders table
$insert_orders = "INSERT INTO `user_orders` 
(user_id, amount_due, invoice_number, total_products, order_date, order_status) 
VALUES 
($user_id, $total_price, $invoice_number, $count_products, NOW(), '$status')";
$result_order = mysqli_query($con, $insert_orders);

if ($result_order) {
    echo "<script>alert('Order has been placed successfully!')</script>";
    echo "<script>window.open('profile.php', '_self')</script>";
}

// Insert into orders_pending table for each product
mysqli_data_seek($result_cart, 0); // reset pointer to loop again
while ($row_cart = mysqli_fetch_array($result_cart)) {
    $product_id = $row_cart['product_id'];
    $product_quantity = $row_cart['quantity'];
    if ($product_quantity == 0) {
        $product_quantity = 1;
    }

    $insert_pending = "INSERT INTO `orders_pending` 
    (user_id, invoice_number, product_id, quantity, order_status) 
    VALUES 
    ($user_id, $invoice_number, $product_id, $product_quantity, '$status')";
    mysqli_query($con, $insert_pending);
}

// Clear the cart
$clear_cart = "DELETE FROM `cart_details` WHERE ip_address='$get_ip_address'";
mysqli_query($con, $clear_cart);
?>
