<!--connect file-->
<?php
include('../includes/connect.php');
include('../functions/common_function.php');
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

// Handle form submission
if(isset($_POST['update_account'])) {
    $user_username = $_POST['user_username'];
    $user_email = $_POST['user_email'];
    $user_address = $_POST['user_address'];
    $user_mobile = $_POST['user_mobile'];
    
    // Handle image upload
    $user_image = $_FILES['user_image']['name'];
    $user_image_tmp = $_FILES['user_image']['tmp_name'];
    
    if($user_image != '') {
        move_uploaded_file($user_image_tmp, "./user_images/$user_image");
        
        // Update query with image
        $update_data = "UPDATE user_table SET username='$user_username', user_email='$user_email', 
                       user_address='$user_address', user_mobile='$user_mobile', user_image='$user_image' 
                       WHERE user_id=$user_id";
    } else {
        // Update query without image
        $update_data = "UPDATE user_table SET username='$user_username', user_email='$user_email', 
                       user_address='$user_address', user_mobile='$user_mobile' 
                       WHERE user_id=$user_id";
    }
    
    $result_query = mysqli_query($con, $update_data);
    
    if($result_query) {
        echo "<script>alert('Account updated successfully!')</script>";
        echo "<script>window.location.href='profile.php?edit_account';</script>";
    }
}

// Handle order deletion
if (isset($_POST['delete_order'])) {
    $delete_order_id = $_POST['delete_order_id'];
    $delete_query = "DELETE FROM user_orders WHERE order_id = $delete_order_id";
    mysqli_query($con, $delete_query);
    echo "<script>alert('Order deleted successfully.'); window.location.href='profile.php?my_orders';</script>";
    exit();
}

// Function to get pending orders
function get_pending_orders($user_id) {
    global $con;
    
    $get_pending = "SELECT uo.*, op.product_id, p.product_title, p.product_image1, p.product_price 
                   FROM user_orders uo 
                   JOIN orders_pending op ON uo.order_id = op.order_id 
                   JOIN products p ON op.product_id = p.product_id 
                   WHERE uo.user_id = '$user_id' AND op.order_status = 'pending'";
    
    $result = mysqli_query($con, $get_pending);
    
    if(mysqli_num_rows($result) > 0) {
        echo "<div class='order-table p-4'>
            <h3 class='text-warning mb-4'>Pending Orders</h3>
            <table class='table table-bordered'>
                <thead class='bg-warning text-dark'>
                    <tr>
                        <th>S.no</th>
                        <th>Product</th>
                        <th>Amount Due</th>
                        <th>Total Products</th>
                        <th>Invoice Number</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class='bg-light'>";
        
        $number = 1;
        while($row = mysqli_fetch_assoc($result)) {
            $order_id = $row['order_id'];
            $product_title = $row['product_title'];
            $product_image = $row['product_image1'];
            $amount_due = $row['amount_due'];
            $total_products = $row['total_products'];
            $invoice_number = $row['invoice_number'];
            $order_date = $row['order_date'];
            
            echo "<tr>
                <td>$number</td>
                <td>
                    <img src='../admin_area/product_images/$product_image' class='product-img me-2' alt='$product_title'>
                    $product_title
                </td>
                <td>₹$amount_due</td>
                <td>$total_products</td>
                <td>$invoice_number</td>
                <td>$order_date</td>
                <td>
                    <a href='confirm_payment.php?order_id=$order_id' class='btn btn-warning btn-sm'>
                        Confirm Payment
                    </a>
                </td>
            </tr>";
            $number++;
        }
        echo "</tbody></table></div>";
    } else {
        echo "<div class='alert alert-info mt-4'>No pending orders found.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Website - Edit Account</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style.css">
    <style>
        body{
            overflow-x:hidden;
        }
        .profile_img{
            width: 90%;
            margin: auto;
            display:block;
            object-fit:contain;
        }
        .contact-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .contact-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .edit-form {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 20px auto;
        }
        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
        }
        .current-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 15px auto;
            display: block;
            border: 3px solid #0d6efd;
        }
        .btn-update {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            margin-top: 20px;
        }
        .section-title {
            color: #0d6efd;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #0d6efd;
        }
        .order-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .order-card:hover {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="container-fluid p-0">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <!-- Logo & Navbar Toggler -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../images/logo.png" alt="Logo" class="logo" style="height: 50px; width: auto; margin-right: 10px;">
                <span>Chintu</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links & Search -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left-Aligned Navbar Links -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item active"><a class="nav-link" href="./index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="./display_all.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">My Account</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="../cart.php">
                            <i class="fa fa-shopping-cart"></i><sup><?php  cart_item();  ?></sup>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Total Price: <?php total_cart_price();  ?></a>
                    </li>
                </ul>

                <!-- Right-Aligned Search Bar -->
                <form class="d-flex" action="../search_product.php" method="get">
                    <input class="form-control me-2" type="search" placeholder="Search" name="search_data">
                     <input type="submit" value="Search" class="btn btn-outline-light" name="search_data_product">
                </form>
            </div>
        </div>
    </nav>

<!--calling cart function  -->
<?php
cart();
?>
<!--second child-->
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <ul class="navbar-nav me-auto">
        
        <?php
        if(!isset($_SESSION['username'])){
            echo "<li class='nav-item'>
            <a class='nav-link' href='#'>Welcome Guest</a>
        </li> ";
        }else{
            echo "<li class='nav-item'>
            <a class='nav-link' href='#'>Welcome ".$_SESSION['username']."</a>
        </li> ";
        }

        if(!isset($_SESSION['username'])){
            echo "<li class='nav-item'>
            <a class='nav-link' href='./users_area/user_login.php'>Login</a>
        </li> ";
        }else{
            echo "<li class='nav-item'>
            <a class='nav-link' href='logout.php'>Logout</a>
        </li> ";
        }

        ?>
    </ul>
</nav>

<!-- third child-->
 <div class="bg-light">
    <h3 class="text-center">Hidden store</h3>
    <p class="text-center">Communication is at the heart of e-commerce and community</p>
 </div>


 <!-- fourth child-->
  <div class="row">
    <div class="col-md-2 ">
<ul class="navbar-nav bg-seconadry text-center" style="height: 100vh;">
<li class="nav-item bg-primary "><a class="nav-link text-light" href="#"><h4>Your Profile</h4></a></li>
<?php
// Make sure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Query to fetch user image
    $query = "SELECT * FROM `user_table` WHERE username='$username'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_image = $row['user_image'];

        echo "<li class='nav-item bg-secondary'>
                <img src='./user_images/$user_image' class='profile_img my-4' alt='User Image'>
              </li>";
    } else {
        echo "<li class='nav-item bg-secondary'>Image not found.</li>";
    }
} else {
    echo "<li class='nav-item bg-secondary'><img src='../images/default-profile.jpg' class='profile_img my-4' alt='User Image'></li>";
}
?>


<li class="nav-item bg-secondary"><a class="nav-link text-light" href="profile.php?my_orders">Pending Orders</a></li>
<li class="nav-item bg-secondary"><a class="nav-link text-light" href="profile.php?edit_account">Edit Account</a></li>
<li class="nav-item bg-secondary"><a class="nav-link text-light" href="profile.php?my_orders">My Orders</a></li>
<li class="nav-item bg-secondary"><a class="nav-link text-light" href="profile.php?delete_account">Delete Account</a></li>
<li class="nav-item bg-secondary"><a class="nav-link text-light" href="user_login.php">Logout</a></li>
    
</ul>
    </div>
    <div class="col-md-10 ">
        <?php
        if(isset($_GET['edit_account'])) {
            // Get user data
            $username = $_SESSION['username'];
            $get_user = "SELECT * FROM user_table WHERE username='$username'";
            $result = mysqli_query($con, $get_user);
            $row_fetch = mysqli_fetch_assoc($result);
            
            echo "<div class='edit-form'>
                <h3 class='section-title'>Edit Account Information</h3>
                <form action='' method='post' enctype='multipart/form-data'>
                    <div class='row'>
                        <div class='col-md-6'>
                            <div class='form-group'>
                                <label for='user_username' class='form-label'>Username</label>
                                <input type='text' id='user_username' class='form-control' name='user_username' 
                                       value='".$row_fetch['username']."' required>
                            </div>
                            
                            <div class='form-group'>
                                <label for='user_email' class='form-label'>Email</label>
                                <input type='email' id='user_email' class='form-control' name='user_email' 
                                       value='".$row_fetch['user_email']."' required>
                            </div>
                        </div>
                        
                        <div class='col-md-6'>
                            <div class='form-group'>
                                <label for='user_address' class='form-label'>Address</label>
                                <input type='text' id='user_address' class='form-control' name='user_address' 
                                       value='".$row_fetch['user_address']."' required>
                            </div>
                            
                            <div class='form-group'>
                                <label for='user_mobile' class='form-label'>Mobile</label>
                                <input type='text' id='user_mobile' class='form-control' name='user_mobile' 
                                       value='".$row_fetch['user_mobile']."' required>
                            </div>
                        </div>
                    </div>
                    
                    <div class='text-center mt-4'>
                        <label for='user_image' class='form-label'>Profile Image</label>
                        <img src='./user_images/".$row_fetch['user_image']."' class='current-image' alt='Current Profile Image'>
                        <input type='file' id='user_image' class='form-control' name='user_image' 
                               style='max-width: 300px; margin: 15px auto;'>
                    </div>
                    
                    <div class='text-center'>
                        <input type='submit' name='update_account' class='btn btn-primary btn-update' value='Update Account'>
                    </div>
                </form>
            </div>";
        } else if(isset($_GET['my_orders'])) {
            echo "<div class='order-table p-4 mt-4'>
                <h3 class='text-success mb-4'>All Orders</h3>
                <table class='table table-bordered'>
                    <thead class='bg-primary text-white'>
                        <tr>
                            <th>S.no</th>
                            <th>Products</th>
                            <th>Amount Due</th>
                            <th>Total Products</th>
                            <th>Invoice Number</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class='bg-light'>";

            // Get all orders for the user
            $get_orders = "SELECT uo.*, op.product_id, p.product_title, p.product_image1, p.product_price 
                         FROM user_orders uo 
                         JOIN orders_pending op ON uo.order_id = op.order_id 
                         JOIN products p ON op.product_id = p.product_id 
                         WHERE uo.user_id='$user_id' 
                         ORDER BY uo.order_date DESC";
            $result_orders = mysqli_query($con, $get_orders);
            $number = 1;

            if(mysqli_num_rows($result_orders) > 0) {
                $current_order_id = null;
                $product_list = '';
                
                while ($row = mysqli_fetch_assoc($result_orders)) {
                    if ($current_order_id !== $row['order_id']) {
                        // If this is a new order, display the previous order's data
                        if ($current_order_id !== null) {
                            echo "<tr>
                                <td>$number</td>
                                <td>$product_list</td>
                                <td>₹$amount_due</td>
                                <td>$total_products</td>
                                <td>$invoice_number</td>
                                <td>$order_date</td>
                                <td><span class='badge " . ($order_status == 'complete' ? 'bg-success' : 'bg-warning') . "'>$order_status</span></td>
                                <td>";
                            
                            // Action column with Edit/Delete for pending orders
                            if ($order_status == 'complete') {
                                echo "<span class='badge bg-success'>Paid</span>";
                            } else {
                                echo "<a href='confirm_payment.php?order_id=$current_order_id' class='btn btn-warning btn-sm me-1'>Confirm Payment</a> ";
                                echo "<a href='edit_order.php?order_id=$current_order_id' class='btn btn-info btn-sm me-1'>Edit</a> ";
                                echo "<form method='post' action='' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this order?\");'>
                                        <input type='hidden' name='delete_order_id' value='$current_order_id'>
                                        <button type='submit' name='delete_order' class='btn btn-danger btn-sm'>Delete</button>
                                      </form>";
                            }
                            echo "</td></tr>";
                            $number++;
                        }
                        
                        // Start new order
                        $current_order_id = $row['order_id'];
                        $amount_due = $row['amount_due'];
                        $total_products = $row['total_products'];
                        $invoice_number = $row['invoice_number'];
                        $order_date = $row['order_date'];
                        $order_status = $row['order_status'];
                        $product_list = '';
                    }
                    
                    // Add product to the list
                    $product_list .= "<div class='d-flex align-items-center mb-2'>
                        <img src='../admin_area/product_images/{$row['product_image1']}' 
                             class='product-img me-2' 
                             alt='{$row['product_title']}'
                             style='width: 80px; height: 80px; object-fit: cover; border-radius: 8px;'>
                        <div>
                            <div class='fw-bold'>{$row['product_title']}</div>
                            <div class='text-warning'>₹{$row['product_price']}</div>
                        </div>
                    </div>";
                }
                
                // Display the last order
                if ($current_order_id !== null) {
                    echo "<tr>
                        <td>$number</td>
                        <td>$product_list</td>
                        <td>₹$amount_due</td>
                        <td>$total_products</td>
                        <td>$invoice_number</td>
                        <td>$order_date</td>
                        <td><span class='badge " . ($order_status == 'complete' ? 'bg-success' : 'bg-warning') . "'>$order_status</span></td>
                        <td>";
                    
                    if ($order_status == 'complete') {
                        echo "<span class='badge bg-success'>Paid</span>";
                    } else {
                        echo "<a href='confirm_payment.php?order_id=$current_order_id' class='btn btn-warning btn-sm me-1'>Confirm Payment</a> ";
                        echo "<a href='edit_order.php?order_id=$current_order_id' class='btn btn-info btn-sm me-1'>Edit</a> ";
                        echo "<form method='post' action='' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this order?\");'>
                                <input type='hidden' name='delete_order_id' value='$current_order_id'>
                                <button type='submit' name='delete_order' class='btn btn-danger btn-sm'>Delete</button>
                              </form>";
                    }
                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>No orders found</td></tr>";
            }
            echo "</tbody></table></div>";

            // Contact Section
            echo "<div id='contact' class='contact-info mt-5'>
                <h3 class='text-center mb-4'>Contact Us</h3>
                <div class='row'>
                    <div class='col-md-6'>
                        <h4>Get in Touch</h4>
                        <p><i class='fas fa-map-marker-alt'></i> 789 Ghandhi nagar, Chennai</p>
                        <p><i class='fas fa-phone'></i> +91 6369209689</p>
                        <p><i class='fas fa-envelope'></i> support@buynest.com</p>
                        <p><i class='fas fa-clock'></i> Any time</p>
                    </div>
                    <div class='col-md-6'>
                        <div class='contact-form'>
                            <h4>Send us a Message</h4>
                            <form action='' method='post'>
                                <div class='mb-3'>
                                    <input type='text' class='form-control' placeholder='Your Name' required>
                                </div>
                                <div class='mb-3'>
                                    <input type='email' class='form-control' placeholder='Your Email' required>
                                </div>
                                <div class='mb-3'>
                                    <textarea class='form-control' rows='4' placeholder='Your Message' required></textarea>
                                </div>
                                <button type='submit' class='btn btn-primary'>Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>";
        } else {
            get_user_order_details();
        }
        ?>
    </div>
  </div>



<!-- last child-->
<!--include footer -->
<?php
include("../includes/footer.php");

?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
