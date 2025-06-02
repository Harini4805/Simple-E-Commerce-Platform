<?php
// including connect file


// reusable product card renderer
function render_product_card($product_id, $title, $desc, $img, $price) {
    echo "<div class='col-md-4 mb-2'>
        <div class='card'>
            <img src='./admin_area/product_images/$img' class='card-img-top' alt='$title' style='height: 300px; object-fit: cover;'>
            <div class='card-body'>
                <h5 class='card-title'>$title</h5>
                <p class='card-text'>$desc</p>
                <p class='card-text'><strong>Price: ₹$price</strong></p>
                <a href='index.php?add_to_cart=$product_id' class='btn btn-primary'>Add to cart</a>
                <a href='product_detail.php?product_id=$product_id' class='btn btn-info'>View more</a>
            </div>
        </div>
    </div>";
}

// get all or random products
function getproducts() {
    global $con;
    if (!isset($_GET['category']) && !isset($_GET['brand'])) {
        $select_query = "SELECT * FROM `products` ORDER BY RAND() LIMIT 0,9";
        $result_query = mysqli_query($con, $select_query);
        while ($row = mysqli_fetch_assoc($result_query)) {
            render_product_card(
                $row['product_id'],
                $row['product_title'],
                $row['product_description'],
                $row['product_image1'],
                $row['product_price']
            );
        }
    }
}

// getting all products
function get_all_products(){
    global $con;
    if (!isset($_GET['category']) && !isset($_GET['brand'])) {
        $select_query = "SELECT * FROM `products` ORDER BY RAND()";
        $result_query = mysqli_query($con, $select_query);
        while ($row = mysqli_fetch_assoc($result_query)) {
            render_product_card(
                $row['product_id'],
                $row['product_title'],
                $row['product_description'],
                $row['product_image1'],
                $row['product_price']
            );
        }
    }
}

// get products by category
function get_unique_categories() {
    global $con;
    if (isset($_GET['category'])) {
        $category_id = $_GET['category'];
        $select_query = "SELECT * FROM `products` WHERE category_id = $category_id";
        $result_query = mysqli_query($con, $select_query);
        $num_of_rows = mysqli_num_rows($result_query);
        if ($num_of_rows == 0) {
            echo "<h2 class='text-center text-danger'>No Stock For This Category</h2>";
        }
        while ($row = mysqli_fetch_assoc($result_query)) {
            render_product_card(
                $row['product_id'],
                $row['product_title'],
                $row['product_description'],
                $row['product_image1'],
                $row['product_price']
            );
        }
    }
}

// get products by brand
function get_unique_brands() {
    global $con;
    if (isset($_GET['brand'])) {
        $brand_id = $_GET['brand'];
        $select_query = "SELECT * FROM `products` WHERE brand_id = $brand_id";
        $result_query = mysqli_query($con, $select_query);
        $num_of_rows = mysqli_num_rows($result_query);
        if ($num_of_rows == 0) {
            echo "<h2 class='text-center text-danger'>This Brand Is Not Available For Services</h2>";
        }
        while ($row = mysqli_fetch_assoc($result_query)) {
            render_product_card(
                $row['product_id'],
                $row['product_title'],
                $row['product_description'],
                $row['product_image1'],
                $row['product_price']
            );
        }
    }
}

// display brands
function getbrands() {
    global $con;
    $select_brands = "SELECT * FROM `brands`";
    $result_brands = mysqli_query($con, $select_brands);
    while ($row_data = mysqli_fetch_assoc($result_brands)) {
        $brand_title = $row_data['brand_title'];
        $brand_id = $row_data['brand_id'];
        echo "<li class='nav-item'>
                <a href='index.php?brand=$brand_id' class='nav-link text-light'>$brand_title</a>
              </li>";
    }
}

// display categories
function getcategories() {
    global $con;
    $select_categories = "SELECT * FROM `categories`";
    $result_categories = mysqli_query($con, $select_categories);
    while ($row_data = mysqli_fetch_assoc($result_categories)) {
        $category_title = $row_data['category_title'];
        $category_id = $row_data['category_id'];
        echo "<li class='nav-item'>
                <a href='index.php?category=$category_id' class='nav-link text-light'>$category_title</a>
              </li>";
    }
}

// searching products
function search_product(){
    global $con;
    if(isset($_GET['search_data_product'])){ 
        $search_data_value = $_GET['search_data'];
        $search_query = "SELECT * FROM `products` WHERE product_keywords LIKE '%$search_data_value%'";
        $result_query = mysqli_query($con, $search_query);
        $num_of_rows = mysqli_num_rows($result_query);
        if ($num_of_rows == 0) {
            echo "<h2 class='text-center text-danger'>No Products Found For This Search!</h2>";
        }
        while ($row = mysqli_fetch_assoc($result_query)) {
            render_product_card(
                $row['product_id'],
                $row['product_title'],
                $row['product_description'],
                $row['product_image1'],
                $row['product_price']
            );
        }
    }
}

// view details
function view_details(){
    global $con;
    if(isset($_GET['product_id'])){ 
        if (!isset($_GET['category']) && !isset($_GET['brand'])) {
            $select_query = "SELECT * FROM `products` ORDER BY RAND() LIMIT 0,9";
            $result_query = mysqli_query($con, $select_query);
            while ($row = mysqli_fetch_assoc($result_query)) {
                render_product_card(
                    $row['product_id'],
                    $row['product_title'],
                    $row['product_description'],
                    $row['product_image1'],
                    $row['product_price']
                );
            }
        }
    }
}

// get IP address
function getIPAddress(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// cart function
function cart(){
    if(isset($_GET['add_to_cart'])){
        global $con;
        $get_ip_address = getIPAddress();
        $get_product_id = $_GET['add_to_cart'];

        $select_query = "SELECT * FROM `cart_details` WHERE ip_address='$get_ip_address' AND product_id=$get_product_id";
        $result_query = mysqli_query($con, $select_query);

        if (!$result_query) {
            die("Query Failed: " . mysqli_error($con));
        }

        $num_of_rows = mysqli_num_rows($result_query);

        if ($num_of_rows > 0) {
            echo "<script>alert('This item is already present inside the cart')</script>";
            echo "<script>window.open('index.php','_self')</script>";
        } else {
            $insert_query = "INSERT INTO `cart_details` (product_id, ip_address, quantity) VALUES ($get_product_id, '$get_ip_address', 0)";
            $insert_result = mysqli_query($con, $insert_query);
            echo "<script>alert('Item is added to the cart')</script>";
            echo "<script>window.open('index.php','_self')</script>";
        }
    }
}

// function to get cart item numbers
function cart_item(){
    global $con;
    $get_ip_address = getIPAddress();
    $select_query = "SELECT * FROM `cart_details` WHERE ip_address='$get_ip_address'";
    $result_query = mysqli_query($con, $select_query);
    if (!$result_query) {
        die("Query Failed: " . mysqli_error($con));
    }
    $count_cart_items = mysqli_num_rows($result_query);
    echo $count_cart_items;
}

//total price function
function total_cart_price(){
    global $con;
    $get_ip_address = getIPAddress();
    $total_price=0;
    $cart_query = "SELECT * FROM `cart_details` WHERE ip_address='$get_ip_address'";
    $result=mysqli_query($con,$cart_query);
    while($row=mysqli_fetch_array($result)){
        $product_id=$row['product_id'];
        $select_products="Select * FROM `products` WHERE product_id='$product_id'";
        $result_products=mysqli_query($con,$select_products);
        while($row_product_price=mysqli_fetch_array($result_products)){
            $product_price=array($row_product_price['product_price']);
            $product_values=array_sum($product_price);
            $total_price+=$product_values;
        }
    }
    echo  $total_price;
}

function get_user_order_details(){
    global $con;
    $username=$_SESSION['username'];
    $get_details="select * from `user_table` where username='$username' ";
    $result_query=mysqli_query($con,$get_details);
    while($row_query=mysqli_fetch_array($result_query)){
        $user_id=$row_query['user_id'];
        if(!isset($_GET['edit_account'])){
            if(!isset($_GET['my_orders'])){
                if(!isset($_GET['delete_account'])){
                    $get_orders="select * from `user_orders` where user_id= $user_id and order_status='pending' ";
                    $result_orders_query=mysqli_query($con,$get_orders);
                    //$row_count=mysqli_num_rows($result_orders_query);
                    $row_count = mysqli_num_rows($result_orders_query);
                    if($row_count>0){
                        echo "<h3 class='text-center'>You have<span class='text-danger'>$row_count</span>Pending orders</h3>";
                        echo "<p class='text-center'><a href='profile.php?my_orders' class='text-dark'>View Orders</a></p>";

                    }else {
                        echo "<h3 class='text-center text-success'>You have no pending orders</h3>";
                        echo "<p class='text-center'><a href='../index.php' class='text-dark'>Explore Products</a></p>";
                    }
        }
    }
}
    }
}

?>
