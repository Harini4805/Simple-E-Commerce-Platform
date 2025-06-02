<?php
include('../includes/connect.php');
include('../functions/common_function.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>payment page</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        payment_img{
            width: 100%;
            margin: auto;
            display: block;
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
                    <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="display_all.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="./users_area/user_registeration.php">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contacts</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fa fa-shopping-cart"></i><sup><?php  cart_item();  ?></sup>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Total Price: <?php total_cart_price();  ?></a>
                    </li>
                </ul>

                <!-- Right-Aligned Search Bar -->
                <form class="d-flex" action="search_product.php" method="get">
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

<div class="bg-light">
    <h3 class="text-center">BuyNest</h3>
    <p class="text-center">Where Smart Shoppers Build Their Nest.</p>
</div>
    <!-- php code to access user id-->
     <?php
      $user_ip=getIPAddress();
      $get_user="select * from `user_table` where user_ip='$user_ip' ";
      $result=mysqli_query($con,$get_user);
      $run_query=mysqli_fetch_array($result);
      $user_id=$run_query['user_id'];

      ?>
    <div class="container">
        <h2 class="text-center text-primary"> Payment Options</h2>
        <div class="row d-flex justify-content-center allign-items-center my-5">
            <div class="col-md-6">
            <a href="https://www.paypal.com" target="_blank"><img src="../images/upi.jpg" alt="" class="payment_img"></a>
            </div>
        <div class="col-md-6">
            <a href="order.php?user_id"<?php echo $user_id ?>><h2 class="text-center">Pay Offline</h2></a>
            <div class="container text-center mt-5">
    <h2>Proceed to Payment</h2>
    <a href="order.php?user_id=<?= $user_id ?>" class="btn btn-success mt-3">Place Order</a>
            </div>
        </div>
    </div>
    <?php
include("../includes/footer.php");

?>
</body>
</html>