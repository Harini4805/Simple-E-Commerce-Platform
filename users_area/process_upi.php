<?php
// Include the necessary files for database connection
include('../includes/connect.php');

// Check if UPI payment is submitted
if (isset($_POST['submit_upi'])) {
    // Process UPI payment logic here
    // For example, you might want to update the order status or send a confirmation email
    echo "<div class='alert alert-success'>UPI payment processed successfully!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UPI Payment</title>
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
      max-width: 800px;
    }

    .upi-payment-container {
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

    .upi-payment-container h3 {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 30px;
      color: #333;
      text-align: center;
    }

    .qr-code {
      text-align: center;
      margin-bottom: 20px;
    }

    .qr-code img {
      max-width: 200px;
      border: 1px solid #ddd;
      border-radius: 10px;
    }

    .btn-primary {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      border: none;
      padding: 14px;
      font-size: 18px;
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

    .alert-success {
      color: green;
    }

    @media (max-width: 768px) {
      .upi-payment-container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="upi-payment-container mt-5">
    <h3>UPI Payment</h3>
    <div class="qr-code">
      <img src="../images/upi_qr_code.png" alt="UPI QR Code">
    </div>
    <form action="process_upi.php" method="POST">
      <button type="submit" name="submit_upi" class="btn btn-primary w-100">Confirm Payment</button>
    </form>
    <a href="../index.php" class="btn btn-secondary w-100 mt-3">Back to Home Page</a>
  </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html> 