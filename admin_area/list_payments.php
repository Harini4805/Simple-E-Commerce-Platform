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
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h3 class="text-center text-success">All Payments</h3>

    <?php
    $get_payments = "SELECT * FROM `user_payments`";
    $result = mysqli_query($con, $get_payments);
    $row_count = mysqli_num_rows($result);

    if ($row_count == 0) {
        echo "<h2 class='text-center text-danger p-3 mt-5'>No Payments Recived Yet</h2>";

    } else {
        echo "
        <table class='table table-bordered mt-3'>
            <thead class='bg-primary text-light'>
                <tr>
                    <th>S.no</th>
                    <th>Invoice Number</th>
                    <th>Amount</th>
                    <th>Payment Mode</th>
                    <th>Order Date</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody class='bg-secondary text-light'>";

        $number = 0;
        while ($row_data = mysqli_fetch_assoc($result)) {
            $order_id = $row_data['order_id'];
            $payment_id=$row_data['payment_id'];
            $amount= $row_data['amount'];
            $invoice_number = $row_data['invoice_number'];
            $payment_mode = $row_data['payment_mode'];
            $payment_date=$row_data['payment_date'];
            $number++;

            echo "<tr>
                <td>$number</td>
                <td>$invoice_number</td>
                <td>$amount</td>
                <td>$payment_mode</td>
                <td>$payment_date</td>
                
                <td>
                    <a href='javascript:void(0);' onclick='deleteOrder($order_id)' class='text-light'>
                        <i class='fa-solid fa-trash'></i>
                    </a>
                </td>
            </tr>";
        }

        echo "</tbody></table>";
    }
    ?>
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
