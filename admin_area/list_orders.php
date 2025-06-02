<?php
// Database connection
include('../includes/connect.php');

// Handle AJAX delete request
if (isset($_POST['delete_order_id'])) {
    $order_id = $_POST['delete_order_id'];
    $delete_query = "DELETE FROM `user_orders` WHERE order_id = $order_id";
    $delete_result = mysqli_query($con, $delete_query);
    echo $delete_result ? 'success' : 'error';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .product_img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .table {
            margin-top: 20px;
        }
        .table thead {
            background-color: #0d6efd;
            color: white;
        }
        .table tbody {
            background-color: #6c757d;
            color: white;
        }
        .btn {
            margin: 0 5px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h3 class="text-center text-success mb-4">All Orders</h3>

    <!-- Filter Buttons -->
    <div class="text-center mb-4">
        <a href="list_orders.php?filter=all" class="btn btn-primary">All Orders</a>
        <a href="list_orders.php?filter=pending" class="btn btn-warning">Pending Orders</a>
    </div>

    <?php
    // Modify the query based on filter
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    if ($filter === 'pending') {
        $get_orders = "SELECT * FROM `user_orders` WHERE order_status='pending' ORDER BY order_date DESC";
    } else {
        $get_orders = "SELECT * FROM `user_orders` ORDER BY 
            CASE 
                WHEN order_status='complete' THEN 1 
                ELSE 2 
            END, 
            order_date DESC";
    }
    $result = mysqli_query($con, $get_orders);
    $row_count = mysqli_num_rows($result);

    if ($row_count == 0) {
        echo "<h2 class='text-center text-danger p-3 mt-5'>No Orders Yet</h2>";
    } else {
        echo "
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <th>S.no</th>
                    <th>Amount Due</th>
                    <th>Invoice Number</th>
                    <th>Total Products</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>";

        $number = 1;
        while ($row_data = mysqli_fetch_assoc($result)) {
            $order_id = $row_data['order_id'];
            $amount_due = $row_data['amount_due'];
            $invoice_number = $row_data['invoice_number'];
            $total_products = $row_data['total_products'];
            $order_date = $row_data['order_date'];
            $order_status = $row_data['order_status'];

            echo "<tr>
                <td>$number</td>
                <td>₹$amount_due</td>
                <td>$invoice_number</td>
                <td>$total_products</td>
                <td>$order_date</td>
                <td>$order_status</td>
                <td>
                    <a href='javascript:void(0);' onclick='deleteOrder($order_id)' class='text-light'>
                        <i class='fa-solid fa-trash'></i>
                    </a>
                </td>
            </tr>";
            $number++;
        }

        echo "</tbody></table>";
    }
    ?>

    <a href="index.php" class="btn btn-secondary mt-3">Back to Admin Panel</a>
</div>

<!-- jQuery and AJAX for delete -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function deleteOrder(orderId) {
    if (confirm("Are you sure you want to delete this order?")) {
        $.ajax({
            url: 'list_orders.php',
            type: 'POST',
            data: { delete_order_id: orderId },
            success: function(response) {
                if (response.trim() === 'success') {
                    alert("Order deleted successfully.");
                    location.reload(); // refresh list
                } else {
                    alert("Failed to delete order.");
                }
            }
        });
    }
}
</script>

</body>
</html>
