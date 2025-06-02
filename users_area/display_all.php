<?php
include('../includes/connect.php');
include('../functions/common_function.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products - Chintu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            overflow-x: hidden;
        }
    
        .product-card img {
            height: 200px;
            object-fit: contain;
        }

        .list-group a {
            text-decoration: none;
            color: #000;
            padding: 8px 12px;
            display: block;
        }

        .list-group a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<div class="container-fluid p-0">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <!-- Logo & Navbar Toggler -->
            <a class="navbar-brand d-flex align-items-center" href="../index.php">
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
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item active"><a class="nav-link" href="display_all.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="user_registeration.php">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="../contact.php">Contacts</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="../cart.php">
                            <i class="fa fa-shopping-cart"></i><sup><?php cart_item(); ?></sup>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Total Price: <?php total_cart_price(); ?></a>
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
            <a class='nav-link' href='./users_area/logout.php'>Logout</a>
        </li> ";
        }

        ?>
    </ul>
</nav>

    <!-- third child-->
    <div class="bg-light">
        <h3 class="text-center">BuyNest</h3>
        <p class="text-center">Where Smart Shoppers Build Their Nest.</p>
    </div>

    <!-- Main Content -->
    <div class="container-fluid p-0">
        <div class="row px-1">
            <!-- Left: Product Listings -->
            <div class="col-md-10">
                <div class="row">
                    <?php get_all_products(); ?>
                </div>
            </div>

            <!--sidenav -->
            <div class="col-md-2 bg-secondary p-0">
                <!--brands to be displayed-->
                <ul class="navbar-nav me-auto text-center">
                    <li class="nav-item bg-primary">
                        <a href="#" class="nav-link text-light"><h4>Delivery Brands</h4></a>
                    </li>
                    <?php getbrands(); ?>
                </ul>

                <!-- categories to be displayed-->
                <ul class="navbar-nav me-auto text-center">
                    <li class="nav-item bg-primary">
                        <a href="#" class="nav-link text-light"><h4>Categories</h4></a>
                    </li>
                    <?php getcategories(); ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include("../includes/footer.php"); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 