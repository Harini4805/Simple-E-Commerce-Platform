<?php
include('./includes/connect.php');
include('./functions/common_function.php');
session_start();

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    
    $select_query = "SELECT * FROM products WHERE product_id = $product_id";
    $result_query = mysqli_query($con, $select_query);

    if ($row = mysqli_fetch_assoc($result_query)) {
        $title = $row['product_title'];
        $desc = $row['product_description'];
        $price = $row['product_price'];
        $img1 = $row['product_image1'];
        $img2 = $row['product_image2'];
        $img3 = $row['product_image3'];
    } else {
        echo "<h2 class='text-center text-danger'>Product not found.</h2>";
        exit();
    }

    // Handle review submission
    if (isset($_POST['submit_review']) && isset($_SESSION['username'])) {
        $rating = mysqli_real_escape_string($con, $_POST['rating']);
        $review_text = mysqli_real_escape_string($con, $_POST['review_text']);
        
        // Get user_id from username
        $username = $_SESSION['username'];
        $user_query = "SELECT user_id FROM user_table WHERE username = '$username'";
        $user_result = mysqli_query($con, $user_query);
        $user_data = mysqli_fetch_assoc($user_result);
        $user_id = $user_data['user_id'];
        
        $insert_review = "INSERT INTO reviews (product_id, user_id, rating, review_text, created_at) 
                         VALUES ($product_id, $user_id, $rating, '$review_text', NOW())";
        
        if (mysqli_query($con, $insert_review)) {
            $_SESSION['review_success'] = true;
        } else {
            $_SESSION['review_error'] = "Error submitting review: " . mysqli_error($con);
        }
        
        // Redirect to prevent form resubmission
        header("Location: product_detail.php?product_id=$product_id");
        exit();
    }

    // Get reviews for this product
    $reviews_query = "SELECT r.*, u.username 
                     FROM reviews r 
                     LEFT JOIN user_table u ON r.user_id = u.user_id 
                     WHERE r.product_id = $product_id 
                     ORDER BY r.created_at DESC";
    $reviews_result = mysqli_query($con, $reviews_query);
} else {
    echo "<h2 class='text-center text-danger'>No product selected.</h2>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?> - Product Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .fixed-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 8px;
        }

        .related-image {
            height: 160px;
            object-fit: cover;
            border-radius: 6px;
        }

        /* Rating Stars Styles */
        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .rating input {
            display: none;
        }

        .rating label {
            cursor: pointer;
            font-size: 30px;
            color: #ddd;
            padding: 5px;
        }

        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            color: #ffd700;
        }

        .rating-display {
            font-size: 20px;
        }

        .rating-display span {
            margin: 0 2px;
        }

        /* Review Section Styles */
        .reviews-list .card {
            border: 1px solid #e0e0e0;
            transition: transform 0.2s;
        }

        .reviews-list .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .fixed-image {
                height: 250px;
            }

            .related-image {
                height: 120px;
            }
        }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
        }
        
        .notification .alert {
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Notifications -->
<div class="notification">
    <?php if (isset($_SESSION['review_success'])) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your review has been submitted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['review_success']); ?>
    <?php } ?>

    <?php if (isset($_SESSION['review_error'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?php echo $_SESSION['review_error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['review_error']); ?>
    <?php } ?>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="./images/logo.png" alt="Logo" style="height: 50px; margin-right: 10px;">
            Chintu
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="display_all.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="./users_area/user_registeration.php">Register</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contacts</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php"><i class="fa fa-shopping-cart"></i><sup><?php cart_item(); ?></sup></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Total Price: <?php total_cart_price(); ?></a>
                </li>
            </ul>
            <form class="d-flex ms-3" action="search_product.php" method="get">
                <input class="form-control me-2" type="search" placeholder="Search" name="search_data">
                <input type="submit" value="Search" class="btn btn-outline-light" name="search_data_product">
            </form>
        </div>
    </div>
</nav>

<!-- Sub Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <ul class="navbar-nav me-auto">
        <?php
        if (!isset($_SESSION['username'])) {
            echo "<li class='nav-item'><a class='nav-link' href='#'>Welcome Guest</a></li>";
        } else {
            echo "<li class='nav-item'><a class='nav-link' href='#'>Welcome " . $_SESSION['username'] . "</a></li>";
        }

        if (!isset($_SESSION['username'])) {
            echo "<li class='nav-item'><a class='nav-link' href='./users_area/user_login.php'>Login</a></li>";
        } else {
            echo "<li class='nav-item'><a class='nav-link' href='./users_area/logout.php'>Logout</a></li>";
        }
        ?>
    </ul>
</nav>

<!-- Header -->
<div class="bg-light py-3 text-center">
    <h3>BuyNest</h3>
    <p>Where Smart Shoppers Build Their Nest.</p>
</div>

<!-- Product + Right Sidebar Layout -->
<div class="container-fluid my-4">
    <div class="row">
        <!-- Product Details (Left Side) -->
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-5">
                    <img src="./admin_area/product_images/<?php echo $img1; ?>" class="fixed-image mb-3" alt="Main Image">
                    <div class="d-flex justify-content-between">
                        <?php if (!empty($img2)) echo "<img src='./admin_area/product_images/$img2' class='related-image me-2' width='49%'>"; ?>
                        <?php if (!empty($img3)) echo "<img src='./admin_area/product_images/$img3' class='related-image' width='49%'>"; ?>
                    </div>
                </div>
                <div class="col-md-7">
                    <h2 class="text-primary"><?php echo $title; ?></h2>
                    <p><strong>Description:</strong> <?php echo $desc; ?></p>
                    <p><strong>Price:</strong> ₹<?php echo $price; ?></p>
                    <a href="index.php?addtocart=<?php echo $product_id; ?>" class="btn btn-primary me-2">Add to Cart</a>
                    <a href="index.php" class="btn btn-secondary">Back to Home</a>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="mb-4">Customer Reviews</h3>
                    
                    <!-- Review Form -->
                    <?php if (isset($_SESSION['username'])) { ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Write a Review</h5>
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label">Rating</label>
                                        <div class="rating">
                                            <?php for($i = 5; $i >= 1; $i--) { ?>
                                                <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" required>
                                                <label for="star<?php echo $i; ?>">☆</label>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="review_text" class="form-label">Your Review</label>
                                        <textarea class="form-control" id="review_text" name="review_text" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" name="submit_review" class="btn btn-primary">Submit Review</button>
                                </form>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-info">
                            Please <a href="./users_area/user_login.php">login</a> to write a review.
                        </div>
                    <?php } ?>

                    <!-- Display Reviews -->
                    <div class="reviews-list">
                        <?php
                        if (mysqli_num_rows($reviews_result) > 0) {
                            while ($review = mysqli_fetch_assoc($reviews_result)) {
                                ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <?php echo htmlspecialchars($review['username']); ?>
                                            </h6>
                                            <div class="rating-display">
                                                <?php
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $review['rating']) {
                                                        echo '<span class="text-warning">★</span>';
                                                    } else {
                                                        echo '<span class="text-muted">☆</span>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <p class="card-text"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                                        <small class="text-muted">
                                            Posted on <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                                        </small>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<div class="alert alert-info">No reviews yet. Be the first to review this product!</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (Right Side) -->
        <div class="col-md-2 bg-secondary p-0 order-md-last">
            <ul class="navbar-nav me-auto text-center">
                <li class="nav-item bg-primary">
                    <a href="#" class="nav-link text-light"><h4>Delivery Brands</h4></a>
                </li>
                <?php getbrands(); ?>
            </ul>

            <ul class="navbar-nav me-auto text-center">
                <li class="nav-item bg-primary">
                    <a href="#" class="nav-link text-light"><h4>Categories</h4></a>
                </li>
                <?php getcategories(); ?>
            </ul>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include("./includes/footer.php"); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Add this before closing body tag -->
<script>
// Auto-hide notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
</body>
</html>
