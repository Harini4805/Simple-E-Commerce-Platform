<?php
include('../includes/connect.php');
include('../functions/common_function.php');  
session_start();

// Optional: Redirect to login if admin is not logged in
if (!isset($_SESSION['admin_name'])) {
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin_image {
            width: 100px;
            object-fit: contain;
        }
        .footer {
            position: absolute;
            bottom: 0;
        }
        body {
            overflow-x: hidden;
        }
        .product_img {
            width: 100px;
            object-fit: contain;
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-primary">
        <div class="container-fluid">
            <img src="../images/logo.png" alt="" class="logo">
            <nav class="navbar navbar-expand-lg">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">
                            Welcome <?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Guest'; ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-light">
        <h3 class="text-center p-2">Manage Details</h3>
    </div>

    <!-- Sidebar -->
    <div class="row">
        <div class="col-md-12 bg-secondary p-1 d-flex align-items-center">
            <div class="p-5">
                <a href="#"><img src="../images/pineapplejuice.png" alt="" class="admin_image"></a>
                <p class="text-light text-center">
                    <?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin'; ?>
                </p>
            </div>
            <div class="button text-center">
                <button class="my-3"><a href="insert_product.php" class="nav-link text-light bg-info my-1">Insert Products</a></button>
                <button><a href="index.php?view_products" class="nav-link text-light bg-info my-1">View Products</a></button>
                <button><a href="index.php?insert_category" class="nav-link text-light bg-info my-1">Insert Category</a></button>
                <button><a href="index.php?view_categories" class="nav-link text-light bg-info my-1">View Category</a></button>
                <button><a href="index.php?insert_brand" class="nav-link text-light bg-info my-1">Insert Brands</a></button>
                <button><a href="index.php?view_brands" class="nav-link text-light bg-info my-1">View Brands</a></button>
                <button><a href="index.php?list_orders" class="nav-link text-light bg-info my-1">All Orders</a></button>
                <button><a href="index.php?list_payments" class="nav-link text-light bg-info my-1">All Payments</a></button>
                <button><a href="index.php?list_users" class="nav-link text-light bg-info my-1">List Users</a></button>
                <button><a href="index.php?stock_details" class="nav-link text-light bg-info my-1">Stock Details</a></button>
                <button><a href="admin_logout.php" class="nav-link text-light bg-info my-1">Logout</a></button>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="container my-3">
        <?php
        if (isset($_GET['insert_category'])) {
            include('insert_categories.php');
        }
        if (isset($_GET['insert_brand'])) {
            include('insert_brands.php');
        }
        if (isset($_GET['view_products'])) {
            include('view_products.php');
        }
        if (isset($_GET['edit_products'])) {
            include('edit_products.php');
        }
        if (isset($_GET['view_categories'])) {
            include('view_categories.php');
        }
        if (isset($_GET['view_brands'])) {
            include('view_brands.php');
        }
        if (isset($_GET['list_orders'])) {
            include('list_orders.php');
        }
        if (isset($_GET['list_payments'])) {
            include('list_payments.php');
        }
        if (isset($_GET['list_users'])) {
            include('list_users.php');
        }
        if (isset($_GET['stock_details'])) {
            include('stock_details.php');
        }

        ?>
    </div>

    <!-- Footer -->
    <?php include("../includes/footer.php"); ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
</body>
</html>
