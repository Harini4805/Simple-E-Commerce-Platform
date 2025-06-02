<?php
include('../includes/connect.php');

if (isset($_POST['insert_product'])) {
  $product_title = $_POST['product_title'];
  $description = $_POST['description'];
  $product_keywords = $_POST['product_keywords'];
  $product_category = $_POST['product_category'];
  $product_brands = $_POST['product_brands'];
  $product_price = $_POST['product_price'];
  $product_status = 'true';

  $product_image1 = $_FILES['product_image1']['name'];
  $product_image2 = $_FILES['product_image2']['name'];
  $product_image3 = $_FILES['product_image3']['name'];

  $temp_image1 = $_FILES['product_image1']['tmp_name'];
  $temp_image2 = $_FILES['product_image2']['tmp_name'];
  $temp_image3 = $_FILES['product_image3']['tmp_name'];

  if (
    empty($product_title) || empty($description) || empty($product_keywords) ||
    empty($product_category) || empty($product_brands) || empty($product_price) ||
    empty($product_image1) || empty($product_image2) || empty($product_image3)
  ) {
    echo "<div class='alert alert-danger text-center'>Please fill all the fields.</div>";
  } else {
    move_uploaded_file($temp_image1, "./product_images/$product_image1");
    move_uploaded_file($temp_image2, "./product_images/$product_image2");
    move_uploaded_file($temp_image3, "./product_images/$product_image3");

    $insert_products = "INSERT INTO `products` 
      (product_title, product_description, product_keywords, category_id, brand_id, 
      product_image1, product_image2, product_image3, product_price, date, status) 
      VALUES 
      ('$product_title','$description','$product_keywords','$product_category','$product_brands',
      '$product_image1','$product_image2','$product_image3','$product_price',NOW(),'$product_status')";

    $result_query = mysqli_query($con, $insert_products);
    if ($result_query) {
      echo "<div class='alert alert-success text-center'>Product inserted successfully!</div>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insert Products</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #dbe9f4, #ffffff);
      font-family: 'Segoe UI', sans-serif;
      padding-bottom: 40px;
    }

    .container {
      max-width: 750px;
      margin-top: 50px;
    }

    .form-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 50px 35px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      border: none;
      transition: all 0.3s ease-in-out;
    }

    .form-card:hover {
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.15);
    }

    h1 {
      font-weight: 800;
      margin-bottom: 35px;
      color: #212529;
      text-align: center;
      font-size: 2.2rem;
    }

    .form-group label {
      font-weight: 600;
      color: #212529;
      font-size: 0.95rem;
    }

    .form-control {
      border-radius: 10px;
      border: 1px solid #ced4da;
      transition: border-color 0.25s, box-shadow 0.25s;
    }

    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 8px rgba(0, 123, 255, 0.4);
    }

    .custom-file-input {
      cursor: pointer;
    }

    .custom-file-label {
      border-radius: 10px;
      background: #f8f9fa;
      transition: background 0.2s;
    }

    .custom-file:hover .custom-file-label {
      background-color: #e9ecef;
    }

    .btn-primary {
      width: 100%;
      padding: 14px;
      font-size: 18px;
      border-radius: 10px;
      font-weight: 600;
      letter-spacing: 0.5px;
      background-color: #007bff;
      border-color: #007bff;
      transition: background 0.3s, transform 0.2s;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      transform: scale(1.03);
    }

    .alert {
      max-width: 700px;
      margin: 20px auto;
      font-weight: 500;
      font-size: 1rem;
    }

    .form-group {
      margin-bottom: 25px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="form-card">
      <h1>Insert Product</h1>
      <form action="" method="post" enctype="multipart/form-data">

        <div class="form-group">
          <label for="product_title">Product Title</label>
          <input type="text" name="product_title" id="product_title" class="form-control" required>
        </div>

        <div class="form-group">
          <label for="description">Product Description</label>
          <input type="text" name="description" id="description" class="form-control" required>
        </div>

        <div class="form-group">
          <label for="product_keywords">Product Keywords</label>
          <input type="text" name="product_keywords" id="product_keywords" class="form-control" required>
        </div>

        <div class="form-group">
          <label for="product_category">Select a Category</label>
          <select name="product_category" id="product_category" class="form-control" required>
            <option value="">-- Select a Category --</option>
            <?php
            $select_query = "SELECT * FROM `categories`";
            $result_query = mysqli_query($con, $select_query);
            while ($row = mysqli_fetch_assoc($result_query)) {
              echo "<option value='{$row['category_id']}'>{$row['category_title']}</option>";
            }
            ?>
          </select>
        </div>

        <div class="form-group">
          <label for="product_brands">Select a Brand</label>
          <select name="product_brands" id="product_brands" class="form-control" required>
            <option value="">-- Select a Brand --</option>
            <?php
            $select_query = "SELECT * FROM `brands`";
            $result_query = mysqli_query($con, $select_query);
            while ($row = mysqli_fetch_assoc($result_query)) {
              echo "<option value='{$row['brand_id']}'>{$row['brand_title']}</option>";
            }
            ?>
          </select>
        </div>

        <div class="form-group">
          <label for="product_image1">Product Image 1</label>
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="product_image1" name="product_image1" required>
            <label class="custom-file-label" for="product_image1">Choose image</label>
          </div>
        </div>

        <div class="form-group">
          <label for="product_image2">Product Image 2</label>
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="product_image2" name="product_image2" required>
            <label class="custom-file-label" for="product_image2">Choose image</label>
          </div>
        </div>

        <div class="form-group">
          <label for="product_image3">Product Image 3</label>
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="product_image3" name="product_image3" required>
            <label class="custom-file-label" for="product_image3">Choose image</label>
          </div>
        </div>

        <div class="form-group">
          <label for="product_price">Product Price</label>
          <input type="text" name="product_price" id="product_price" class="form-control" required>
        </div>

        <button type="submit" name="insert_product" class="btn btn-primary">Insert Product</button>
      </form>
      <a href="index.php" class="btn btn-secondary mt-3">Back to Admin Panel</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelectorAll('.custom-file-input').forEach(input => {
      input.addEventListener('change', function () {
        this.nextElementSibling.innerText = this.files[0].name;
      });
    });
  </script>
</body>
</html>
