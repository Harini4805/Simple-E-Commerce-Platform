<?php
include('../includes/connect.php');

if (isset($_POST['product_id'], $_POST['action'])) {
    $product_id = (int)$_POST['product_id'];
    $action = $_POST['action'];

    // Get current stock
    $result = mysqli_query($con, "SELECT stock FROM products WHERE product_id = $product_id");
    if ($row = mysqli_fetch_assoc($result)) {
        $stock = (int)$row['stock'];
        if ($action === 'increase') {
            $stock++;
        } elseif ($action === 'decrease' && $stock > 0) {
            $stock--;
        }
        // Update stock in DB
        $update = mysqli_query($con, "UPDATE products SET stock = $stock WHERE product_id = $product_id");
        if ($update) {
            echo $stock;
            exit();
        }
    }
}
echo 'error'; 