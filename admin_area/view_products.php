<?php
include('../includes/connect.php');

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM products WHERE product_id = $delete_id";
    $run_delete = mysqli_query($con, $delete_query);
    if ($run_delete) {
        echo "<script>alert('🗑️ Product deleted successfully!'); window.location.href='view_products.php';</script>";
        exit();
    } else {
        echo "<script>alert('❌ Failed to delete product.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product_img {
            width: 100px;
            height: auto;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h3 class="text-center text-success">All Products</h3>
        <table class="table table-bordered mt-5">
            <thead class="text-center bg-primary text-white">
                <tr>
                    <th>S.no</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Total Sold</th>
                    <th>Status</th>
                    <th>Stock</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody class="bg-secondary text-light text-center">
                <?php
                $get_products = "SELECT * FROM products";
                $result = mysqli_query($con, $get_products);
                $number = 0;

                while ($row = mysqli_fetch_assoc($result)) {
                    $product_id = $row['product_id'];
                    $product_title = $row['product_title'];
                    $product_image1 = $row['product_image1'];
                    $product_price = $row['product_price'];
                    $status = $row['status'];
                    $number++;

                    // Total sold
                    $get_count = "SELECT SUM(quantity) AS total_sold FROM orders_pending WHERE product_id = $product_id";
                    $result_count = mysqli_query($con, $get_count);
                    $row_count = mysqli_fetch_assoc($result_count);
                    $total_sold = $row_count['total_sold'] ?? 0;
                ?>
                <tr>
                    <td><?php echo $number; ?></td>
                    <td><?php echo $product_title; ?></td>
                    <td><img src="./product_images/<?php echo $product_image1; ?>" class="product_img"></td>
                    <td><?php echo $product_price; ?></td>
                    <td><?php echo $total_sold; ?></td>
                    <td><?php echo $status; ?></td>
                    <td>
                        <div class="d-flex align-items-center justify-content-center">
                            <button class="btn btn-sm btn-danger me-2 stock-btn" data-action="decrease" data-id="<?php echo $product_id; ?>">-</button>
                            <span id="stock-value-<?php echo $product_id; ?>"><?php echo $row['stock']; ?></span>
                            <button class="btn btn-sm btn-success ms-2 stock-btn" data-action="increase" data-id="<?php echo $product_id; ?>">+</button>
                        </div>
                    </td>
                    <td>
                        <a href='edit_products.php?edit_id=<?php echo $product_id; ?>' class='text-light'>
                            <i class='fa-solid fa-pen-to-square'></i>
                        </a>
                    </td>
                    <td>
                        <a href='view_products.php?delete_id=<?php echo $product_id; ?>' class='text-light' onclick="return confirm('Are you sure you want to delete this product?');">
                            <i class='fa-solid fa-trash'></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.stock-btn').click(function() {
            var btn = $(this);
            var productId = btn.data('id');
            var action = btn.data('action');
            $.ajax({
                url: 'update_stock.php',
                type: 'POST',
                data: { product_id: productId, action: action },
                success: function(response) {
                    if (response !== 'error') {
                        $('#stock-value-' + productId).text(response);
                    } else {
                        alert('Failed to update stock.');
                    }
                }
            });
        });
    });
    </script>
</body>
</html>
