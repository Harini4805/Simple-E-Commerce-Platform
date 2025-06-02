<?php
include('../includes/connect.php');
include('../functions/common_function.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='user_login.php';</script>";
    exit();
}

if(isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $username = $_SESSION['username'];
    
    // Get user details
    $get_user = "SELECT * FROM user_table WHERE username='$username'";
    $result = mysqli_query($con, $get_user);
    $user_data = mysqli_fetch_assoc($result);
    
    // Get order and payment details
    $get_order = "SELECT uo.*, up.payment_mode, up.payment_date, p.product_title, p.product_image1, p.product_price
                 FROM user_orders uo 
                 JOIN orders_pending op ON uo.order_id = op.order_id 
                 JOIN products p ON op.product_id = p.product_id 
                 LEFT JOIN user_payments up ON uo.order_id = up.order_id
                 WHERE uo.order_id = $order_id AND uo.user_id = {$user_data['user_id']}";
    $result_order = mysqli_query($con, $get_order);
    
    if(mysqli_num_rows($result_order) > 0) {
        $order_data = mysqli_fetch_assoc($result_order);
    } else {
        echo "<script>alert('Invalid order!')</script>";
        echo "<script>window.location.href='profile.php?my_orders';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request!')</script>";
    echo "<script>window.location.href='profile.php?my_orders';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - Ecommerce Website</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .receipt {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 800px;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px dashed #dee2e6;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .receipt-body {
            margin-bottom: 20px;
        }
        .receipt-footer {
            text-align: center;
            border-top: 2px dashed #dee2e6;
            padding-top: 20px;
            margin-top: 20px;
        }
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .print-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
        .action-buttons {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }
        @media print {
            .action-buttons {
                display: none;
            }
            .receipt {
                box-shadow: none;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="receipt">
            <div class="receipt-header">
                <h2 class="text-primary">Payment Receipt</h2>
                <p class="text-muted">Thank you for your purchase!</p>
            </div>
            
            <div class="receipt-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Order Details</h5>
                        <p><strong>Order ID:</strong> #<?php echo $order_id; ?></p>
                        <p><strong>Invoice Number:</strong> <?php echo $order_data['invoice_number']; ?></p>
                        <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order_data['order_date'])); ?></p>
                        <p><strong>Payment Date:</strong> <?php echo date('F j, Y H:i:s', strtotime($order_data['payment_date'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Customer Details</h5>
                        <p><strong>Name:</strong> <?php echo $user_data['username']; ?></p>
                        <p><strong>Email:</strong> <?php echo $user_data['user_email']; ?></p>
                        <p><strong>Address:</strong> <?php echo $user_data['user_address']; ?></p>
                        <p><strong>Phone:</strong> <?php echo $user_data['user_mobile']; ?></p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="../admin_area/product_images/<?php echo $order_data['product_image1']; ?>" 
                                             class="product-image me-3" alt="Product Image">
                                        <span><?php echo $order_data['product_title']; ?></span>
                                    </div>
                                </td>
                                <td>₹<?php echo $order_data['product_price']; ?></td>
                                <td><?php echo $order_data['total_products']; ?></td>
                                <td>₹<?php echo $order_data['amount_due']; ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                <td><strong>₹<?php echo $order_data['amount_due']; ?></strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Payment Method:</strong></td>
                                <td><?php echo $order_data['payment_mode']; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="receipt-footer">
                <p class="mb-0">Thank you for shopping with us!</p>
                <p class="text-muted">This is a computer-generated receipt and does not require a signature.</p>
            </div>
        </div>

        <div class="action-buttons">
            <a href="profile.php?my_orders" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to My Orders
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Receipt
            </button>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 