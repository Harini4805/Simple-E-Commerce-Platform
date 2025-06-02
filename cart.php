<!--connect file-->
<?php
include('includes/connect.php');
include('functions/common_function.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Website using PHP and MySQL.</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <style>
        .cart_img{
    width: 80px;
    height: 80px;
    object-fit:contain;
}
    </style>
</head>
<body>

<!-- Navbar -->
<!--< class="container-fluid p-0"> -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <!-- Logo & Navbar Toggler -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="./images/logo.png" alt="Logo" class="logo" style="height: 50px; width: auto; margin-right: 10px;">
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
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contacts</a></li>
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
<form?php
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


 <!--fourth child-->
 <div class="container">
    <div class="row">
        <form action="" method="POST">
        <table class="table table-bordered text-center">
            
            <!--<tbody> -->
                <!-- php code to display dynamic data-->
                 <?php
                 //global $con;
                 $get_ip_address = getIPAddress();
                 $total_price=0;
                 $cart_query = "SELECT * FROM `cart_details` WHERE ip_address='$get_ip_address'";
                 $result=mysqli_query($con,$cart_query);
                 $result_count=mysqli_num_rows($result);
                 if($result_count>0){
                    echo "<thead>
                <tr>
                    <th>Product Title</th>
                    <th>Product Image</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Remove</th>
                    <th colspan='2'>Operations</th>
                </tr>
            </thead>
            <tbody>";
                
                 while($row=mysqli_fetch_array($result)){
                     $product_id=$row['product_id'];
                     $select_products="Select * FROM `products` WHERE product_id='$product_id'";
                     $result_products=mysqli_query($con,$select_products);
                     while($row_product_price=mysqli_fetch_array($result_products)){
                         $product_price=array($row_product_price['product_price']);
                         $price_table=$row_product_price['product_price'];
                         $product_title=$row_product_price['product_title'];
                         $product_image1=$row_product_price['product_image1'];
                         $product_values=array_sum($product_price);
                         $total_price+=$product_values;
                     

?>
                <tr>
                    <td><?php echo $product_title ?></td>
                    <td><img src="./admin_area/product_images/<?php echo $product_image1 ?>" alt="" class="cart_img"></td>
                    <td><input type="text" name="qty" class="form-input w-50"></td>
                    <?php   
                    $get_ip_address = getIPAddress();
                    if(isset($_POST['update_cart'])){
                        $quantities=$_POST['qty'];
                        $update_cart="update `cart_details` set quantity=$quantities where ip_address='$get_ip_address' ";
                        $result_products_quantity=mysqli_query($con,$update_cart);
                        $total_price=$total_price*$quantities;
                        

                    }
                    ?>
                    <td><?php echo $price_table?></td>
                    <td><input type="checkbox" name="removeitem[] " value="<?php  echo $product_id?>"></td>
                    <td>
                        <!-- <button class="bg-primary px-3 py-2 border-0 mx-3 text-light">Update</button> -->
                         <input type="submit" value="Update Cart" class="bg-primary px-3 py-2 border-0 mx-3 text-light" name="update_cart">
                        <!-- <button class="bg-primary px-3 py-2 border-0 mx-3 text-light">Remove</button> -->
                        <input type="submit" value="Remove Cart" class="bg-primary px-3 py-2 border-0 mx-3 text-light" name="remove_cart">
                        
                    </td>
                </tr>
                <?php 
                }}}

                else{
                    echo "<h2 class='text-center text-danger'>Cart Is Empty.</h2>";
                }
                 ?>
            </tbody>
        </table>
<!--subtotal -->
<div class="d-flex mb-5">
    <?php
    $get_ip_address = getIPAddress();
    
    $cart_query = "SELECT * FROM `cart_details` WHERE ip_address='$get_ip_address'";
    $result=mysqli_query($con,$cart_query);
    $result_count=mysqli_num_rows($result);
    if($result_count>0){
        echo " <h4 class='px-3'>Subtotal: <strong class='text-primary'>$total_price</strong></strong></h4>
    <a href='index.php' class='bg-primary px-3 py-2 border-0 mx-3 text-light text-decoration-none'>Continue Shopping</a>
                <button class='bg-secondary px-3 py-2 border-0'><a href='./users_area/checkout.php' class='text-light text-decoration-none'>Checkout</a></button>
";
    }else{
        echo"<a href='index.php' class='bg-primary px-3 py-2 border-0 mx-3 text-light text-decoration-none'>Continue Shopping</a>";
    }
    if(isset($_POST['conntinue_shopping'])){
        echo "<script>window.open('index.php','_self')</script>";
    }




?>
    </div>
    </div>
 </div>
            </form>

            <!-- function to remove item -->
             <?php
             function remove_cart_item(){
                global $con;
                if(isset($_POST['remove_cart'])){
                    foreach($_POST['removeitem'] as $remove_id){
                        $delete_query="DELETE FROM `cart_details` WHERE product_id=$remove_id";
                        $run_delete=mysqli_query($con,$delete_query);
                        if($run_delete){
                            echo "<script>window.open('cart.php','_self')</script>";
                        }
                    }
                }
            }
            
           remove_cart_item(); // Call it once
            ?>
<!-- last child-->
<!--include footer -->
<?php
include("./includes/footer.php");

?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
