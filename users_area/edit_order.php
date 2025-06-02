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
    
    // Get order details
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

// Handle form submission
if(isset($_POST['update_order'])) {
    $new_address = mysqli_real_escape_string($con, $_POST['address']);
    $new_phone = mysqli_real_escape_string($con, $_POST['phone']);
    $new_quantity = (int)$_POST['quantity'];
    
    // Update user details
    $update_user = "UPDATE user_table SET 
                    user_address = '$new_address',
                    user_mobile = '$new_phone'
                    WHERE user_id = {$user_data['user_id']}";
    mysqli_query($con, $update_user);
    
    // Update order quantity and recalculate amount
    $new_amount = $order_data['product_price'] * $new_quantity;
    $update_order = "UPDATE user_orders SET 
                    total_products = $new_quantity,
                    amount_due = $new_amount
                    WHERE order_id = $order_id";
    mysqli_query($con, $update_order);
    
    echo "<script>alert('Order updated successfully!')</script>";
    echo "<script>window.location.href='profile.php?my_orders';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order - Ecommerce Website</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .edit-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 800px;
        }
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="edit-form">
            <h2 class="text-center mb-4">Edit Order #<?php echo $order_id; ?></h2>
            
            <form action="" method="POST">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Product Details</h5>
                        <div class="d-flex align-items-center mb-3">
                            <img src="../admin_area/product_images/<?php echo $order_data['product_image1']; ?>" 
                                 class="product-image me-3" alt="Product Image">
                            <div>
                                <h6 class="mb-0"><?php echo $order_data['product_title']; ?></h6>
                                <p class="text-muted mb-0">Price: ₹<?php echo $order_data['product_price']; ?></p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   value="<?php echo $order_data['total_products']; ?>" min="1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Total Amount</label>
                            <p class="form-control-plaintext" id="totalAmount">
                                ₹<?php echo $order_data['amount_due']; ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Delivery Details</h5>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" 
                                   value="<?php echo $user_data['username']; ?>" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" 
                                   value="<?php echo $user_data['user_email']; ?>" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo $user_data['user_mobile']; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Delivery Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo $user_data['user_address']; ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons justify-content-center">
                    <a href="profile.php?my_orders" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                    <button type="submit" name="update_order" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Calculate total amount when quantity changes
        document.getElementById('quantity').addEventListener('input', function() {
            const price = <?php echo $order_data['product_price']; ?>;
            const quantity = this.value;
            const total = price * quantity;
            document.getElementById('totalAmount').textContent = '₹' + total;
        });
    </script>
</body>
</html> 