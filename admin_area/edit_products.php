<?php
// connect file
include('../includes/connect.php');
include('../functions/common_function.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        body { overflow-x: hidden; }
        .admin_image { width: 100px; object-fit: contain; }
        .product_img { width: 100px; object-fit: contain; }
        .edit-box {
            background-color: #f9f9fc;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }
        .edit-box h3 { font-weight: 600; letter-spacing: 1px; }
        .card { border: none; border-radius: 8px; }
        .card img { border-top-left-radius: 8px; border-top-right-radius: 8px; }
        .logo {
            height: 50px;
            object-fit: contain;
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <nav class="navbar navbar-expand-lg navbar-light bg-primary">
        <div class="container-fluid">
            <img src="../images/logo.png" alt="Logo" class="logo">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">Welcome guest</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="bg-light">
        <h3 class="text-center p-2">Manage Details</h3>
    </div>

    <div class="row">
        <div class="col-md-12 bg-secondary p-1 d-flex align-items-center">
            <div class="p-5">
                <a href="#"><img src="../images/pineapplejuice.png" alt="" class="admin_image"></a>
                <p class="text-light text-center"> Admin Name</p>
            </div>
            <div class="button text-center">
                <button class="my-3"><a href="insert_product.php" class="nav-link text-light bg-info my-1">Insert Products</a></button>
                <button><a href="index.php?view_products" class="nav-link text-light bg-info my-1">View Products</a></button>
                <button><a href="index.php?insert_category" class="nav-link text-light bg-info my-1">Insert Category</a></button>
                <button><a href="#" class="nav-link text-light bg-info my-1">View Category</a></button>
                <button><a href="index.php?insert_brand" class="nav-link text-light bg-info my-1">Insert Brands</a></button>
                <button><a href="#" class="nav-link text-light bg-info my-1">View Brands</a></button>
                <button><a href="#" class="nav-link text-light bg-info my-1">All Orders</a></button>
                <button><a href="#" class="nav-link text-light bg-info my-1">All Payments</a></button>
                <button><a href="#" class="nav-link text-light bg-info my-1">List Users</a></button>
                <button><a href="#" class="nav-link text-light bg-info my-1">Logout</a></button>
            </div>
        </div>
    </div>

    <div class="container mt-5 mb-5">
        <?php
        if (isset($_GET['edit_id'])) {
            $edit_id = $_GET['edit_id'];

            // Handle delete request
            if (isset($_GET['delete']) && $_GET['delete'] == "true") {
                $delete_query = "DELETE FROM products WHERE product_id = $edit_id";
                $run_delete = mysqli_query($con, $delete_query);
                if ($run_delete) {
                    echo "<script>alert('🗑️ Product deleted successfully!'); window.location.href='index.php?view_products';</script>";
                    exit();
                } else {
                    echo "<script>alert('❌ Failed to delete product.');</script>";
                }
            }

            $get_product = "SELECT * FROM `products` WHERE product_id = $edit_id";
            $result = mysqli_query($con, $get_product);

            if ($row = mysqli_fetch_assoc($result)) {
                $product_title = $row['product_title'];
                $product_description = $row['product_description'];
                $product_keywords = $row['product_keywords'];
                $product_image1 = $row['product_image1'];
                $product_image2 = $row['product_image2'];
                $product_image3 = $row['product_image3'];
                $product_price = $row['product_price'];
                $product_category_id = $row['category_id'];
                $product_brand_id = $row['brand_id'];

                $sold_query = "SELECT SUM(quantity) AS total_sold FROM orders_pending WHERE product_id = $edit_id";
                $sold_result = mysqli_query($con, $sold_query);
                $sold_row = mysqli_fetch_assoc($sold_result);
                $total_sold = $sold_row['total_sold'] ?? 0;

                $categories = mysqli_query($con, "SELECT * FROM categories");
                $brands = mysqli_query($con, "SELECT * FROM brands");
        ?>
        <div class="edit-box p-4 mx-auto" style="max-width: 850px;">
            <h3 class="text-center text-primary mb-4">✏️ Edit Product</h3>
            <h5 class="text-center mb-3">Total Sold: <span class="text-success font-weight-bold"><?php echo $total_sold; ?></span></h5>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label><strong>Product Title</strong></label>
                    <input type="text" name="product_title" value="<?php echo $product_title; ?>" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label><strong>Product Description</strong></label>
                    <textarea name="product_description" class="form-control" rows="3" required><?php echo $product_description; ?></textarea>
                </div>
                <div class="form-group mb-3">
                    <label><strong>Product Keywords</strong></label>
                    <input type="text" name="product_keywords" value="<?php echo $product_keywords; ?>" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label><strong>Product Category</strong></label>
                    <select name="product_category" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        <?php while ($cat = mysqli_fetch_assoc($categories)) {
                            $selected = ($cat['category_id'] == $product_category_id) ? "selected" : "";
                            echo "<option value='{$cat['category_id']}' $selected>{$cat['category_title']}</option>";
                        } ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label><strong>Product Brand</strong></label>
                    <select name="product_brand" class="form-control" required>
                        <option value="">-- Select Brand --</option>
                        <?php while ($brand = mysqli_fetch_assoc($brands)) {
                            $selected = ($brand['brand_id'] == $product_brand_id) ? "selected" : "";
                            echo "<option value='{$brand['brand_id']}' $selected>{$brand['brand_title']}</option>";
                        } ?>
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label><strong>Product Images</strong></label>
                    <div class="row">
                        <?php
                        $images = [
                            ['field' => 'product_image1', 'src' => $product_image1],
                            ['field' => 'product_image2', 'src' => $product_image2],
                            ['field' => 'product_image3', 'src' => $product_image3]
                        ];
                        foreach ($images as $img) {
                            echo '<div class="col-md-4 mb-3">
                                    <div class="card shadow-sm">
                                        <img src="./product_images/' . $img['src'] . '" class="card-img-top" style="height: 180px; object-fit: contain;">
                                        <div class="card-body p-2">
                                            <label class="form-label">Change Image</label>
                                            <input type="file" name="' . $img['field'] . '" class="form-control">
                                        </div>
                                    </div>
                                </div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label><strong>Product Price</strong></label>
                    <input type="text" name="product_price" value="<?php echo $product_price; ?>" class="form-control" required>
                </div>
                <div class="text-center d-flex justify-content-between mt-4">
                    <input type="submit" name="update_product" value="Update Product" class="btn btn-success px-4 py-2">
                    <a href="edit_product.php?edit_id=<?php echo $edit_id; ?>&delete=true" onclick="return confirm('Are you sure you want to delete this product?');" class="btn btn-danger px-4 py-2">Delete Product</a>
                </div>
            </form>
        </div>
        <?php
            } else {
                echo "<h5 class='text-danger text-center'>Product not found.</h5>";
            }
        } else {
            echo "<h5 class='text-center text-muted'>Select a product to edit.</h5>";
        }

        if (isset($_POST['update_product']) && isset($_GET['edit_id'])) {
            $edit_id = $_GET['edit_id'];
            $product_title = $_POST['product_title'];
            $product_description = $_POST['product_description'];
            $product_keywords = $_POST['product_keywords'];
            $product_category = $_POST['product_category'];
            $product_brand = $_POST['product_brand'];
            $product_price = $_POST['product_price'];

            $image_fields = ['product_image1', 'product_image2', 'product_image3'];
            $image_updates = [];

            foreach ($image_fields as $field) {
                if ($_FILES[$field]['name'] != '') {
                    $image_name = $_FILES[$field]['name'];
                    $image_tmp = $_FILES[$field]['tmp_name'];
                    move_uploaded_file($image_tmp, "./product_images/$image_name");
                    $image_updates[] = "$field = '$image_name'";
                }
            }

            $update_query = "UPDATE products SET 
                product_title = '$product_title',
                product_description = '$product_description',
                product_keywords = '$product_keywords',
                category_id = '$product_category',
                brand_id = '$product_brand',
                product_price = '$product_price'";

            if (!empty($image_updates)) {
                $update_query .= ", " . implode(', ', $image_updates);
            }

            $update_query .= " WHERE product_id = $edit_id";

            $run_update = mysqli_query($con, $update_query);

            if ($run_update) {
                echo "<script>alert('✅ Product updated successfully!'); window.location.href = 'index.php?view_products';</script>";
                exit();
            } else {
                echo "<script>alert('❌ Failed to update product.');</script>";
            }
        }
        ?>
    </div>

    <?php include("../includes/footer.php"); ?>
</div>
</body>
</html>
