<?php
// Include the necessary files for database connection
include('../includes/connect.php');

// Check if search data is provided
if (isset($_GET['search_data']) && isset($_GET['search_data_product'])) {
    $search_data = mysqli_real_escape_string($con, $_GET['search_data']);
    $search_query = "SELECT * FROM products WHERE product_name LIKE '%$search_data%' OR product_description LIKE '%$search_data%'";
    $result = mysqli_query($con, $search_query);
} else {
    $result = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Search Results</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      font-family: 'Roboto', sans-serif;
      padding: 40px 0;
    }

    .container {
      max-width: 1200px;
    }

    .search-results-container {
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
      from {
        transform: translateY(20px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .search-results-container h3 {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 30px;
      color: #333;
      text-align: center;
    }

    .product-card {
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      transition: all 0.3s ease;
    }

    .product-card:hover {
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .product-card h4 {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .product-card p {
      font-size: 16px;
      color: #666;
    }

    .btn-primary {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      border: none;
      padding: 10px 20px;
      font-size: 16px;
      border-radius: 30px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(to right, #00f2fe, #4facfe);
      transform: scale(1.03);
    }

    .alert {
      font-size: 16px;
      margin-top: 20px;
      text-align: center;
    }

    .alert-info {
      color: #0c5460;
      background-color: #d1ecf1;
      border-color: #bee5eb;
    }

    @media (max-width: 768px) {
      .search-results-container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="search-results-container mt-5">
    <h3>Search Results</h3>
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="product-card">';
            echo '<h4>' . $row['product_name'] . '</h4>';
            echo '<p>' . $row['product_description'] . '</p>';
            echo '<a href="product_detail.php?id=' . $row['product_id'] . '" class="btn btn-primary">View Details</a>';
            echo '</div>';
        }
    } else {
        echo '<div class="alert alert-info">No products found matching your search criteria.</div>';
    }
    ?>
    <a href="../index.php" class="btn btn-secondary w-100 mt-3">Back to Home Page</a>
  </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html> 