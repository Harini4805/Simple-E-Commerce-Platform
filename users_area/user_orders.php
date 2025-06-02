<?php
// Include database connection
include('../includes/connect.php');
include('../functions/common_function.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        .product_img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h3 class="text-success mb-4">My Orders</h3>
    
    <!-- Filter Buttons -->
    <div class="mb-4">
        <a href="user_orders.php?filter=all" class="btn btn-primary">All Orders</a>
        <a href="user_orders.php?filter=pending" class="btn btn-warning">Pending Orders</a>
    </div>

    <table class="table table-bordered">
        <thead class="bg-primary text-white">
            <tr>
                <th>S.no</th>
                <th>Amount Due</th>
                <th>Total Products</th>
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Status</th>
                <th>Products</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="bg-secondary text-light">
        <?php
        // Modify the query based on filter
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
        if ($filter === 'pending') {
            $get_orders = "SELECT * FROM user_orders WHERE user_id='$user_id' AND order_status='pending' ORDER BY order_date DESC";
        } else {
            $get_orders = "SELECT * FROM user_orders WHERE user_id='$user_id' ORDER BY 
                CASE 
                    WHEN order_status='complete' THEN 1 
                    ELSE 2 
                END, 
                order_date DESC";
        }
        $result_orders = mysqli_query($con, $get_orders);
        $number = 1;

        while ($row = mysqli_fetch_assoc($result_orders)) {
            $order_id = $row['order_id'];
            $amount_due = $row['amount_due'];
            $total_products = $row['total_products'];
            $invoice_number = $row['invoice_number'];
            $order_date = $row['order_date'];
            $order_status = $row['order_status'];

            echo "<tr>
                <td>$number</td>
                <td>₹$amount_due</td>
                <td>$total_products</td>
                <td>$invoice_number</td>
                <td>$order_date</td>
                <td>$order_status</td>";

            // Fetch products for this order
            $get_products = "SELECT p.product_title, p.product_image1, op.quantity FROM orders_pending op JOIN products p ON op.product_id = p.product_id WHERE op.invoice_number = '$invoice_number' AND op.user_id = '$user_id'";
            $result_products = mysqli_query($con, $get_products);
            echo "<td><ul style='list-style:none;padding-left:0;'>";
            while ($prod = mysqli_fetch_assoc($result_products)) {
                $title = htmlspecialchars($prod['product_title']);
                $img = htmlspecialchars($prod['product_image1']);
                $qty = (int)$prod['quantity'];
                echo "<li class='mb-1'><img src='../images/$img' class='product_img me-2' alt='$title' title='$title'> $title <span class='badge bg-light text-dark ms-2'>x$qty</span></li>";
            }
            echo "</ul></td>";

            if ($order_status == 'complete') {
                echo "<td>Paid</td>";
            } else {
                echo "<td><a href='confirm_payment.php?order_id=$order_id' class='text-light'>Confirm Payment</a></td>";
            }

            echo "</tr>";
            $number++;
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
