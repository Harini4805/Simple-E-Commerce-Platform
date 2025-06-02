<?php
// Database connection
include('../includes/connect.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Stock Details</h2>
        <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th>S.no</th>
                    <th>Product Title</th>
                    <th>Image</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $get_products = "SELECT product_title, product_image1, stock FROM products";
                $result = mysqli_query($con, $get_products);
                $number = 1;
                $total_stock = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $title = htmlspecialchars($row['product_title']);
                    $img = htmlspecialchars($row['product_image1']);
                    $stock = (int)$row['stock'];
                    $total_stock += $stock;
                    echo "<tr>
                        <td>$number</td>
                        <td>$title</td>
                        <td><img src='./product_images/$img' alt='$title' style='width:60px;height:60px;object-fit:contain;'></td>
                        <td>$stock</td>
                    </tr>";
                    $number++;
                }
                ?>
            </tbody>
            <tfoot>
                
            </tfoot>
        </table>
    </div>
</body>
</html>