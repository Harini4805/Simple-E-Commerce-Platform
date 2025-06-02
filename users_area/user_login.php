<?php
include('../includes/connect.php');
include('../functions/common_function.php');
@session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);
      font-family: 'Roboto', sans-serif;
      overflow-x: hidden;
      height: 100vh;
    }

    .container {
      margin-top: 100px;
    }

    .login-box {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .login-box:hover {
      transform: scale(1.02);
    }

    .form-label {
      font-weight: 600;
      color: #333;
    }

    .form-control {
      border-radius: 30px;
      padding: 12px 20px;
      transition: border 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
      border-color: #f67280;
      box-shadow: 0 0 10px rgba(246, 114, 128, 0.3);
    }

    .btn-custom {
      background-color: #f67280;
      border: none;
      color: white;
      border-radius: 30px;
      padding: 10px 25px;
      font-size: 16px;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-custom:hover {
      background-color: #d94c63;
      transform: scale(1.05);
    }

    .text-center h2 {
      font-weight: 700;
      color: #333;
    }

    .register-link {
      color: #f67280;
      text-decoration: none;
      font-weight: 500;
    }

    .register-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container d-flex align-items-center justify-content-center">
  <div class="col-lg-6 col-md-8 login-box">
    <h2 class="text-center mb-4">User Login</h2>
    <form action="" method="POST">
      <!-- Username Field -->
      <div class="form-outline mb-4">
        <label for="user_username" class="form-label">Username</label>
        <input type="text" id="user_username" class="form-control" placeholder="Enter your username" name="user_username" required autocomplete="off">
      </div>

      <!-- Password Field -->
      <div class="form-outline mb-4">
        <label for="user_password" class="form-label">Password</label>
        <input type="password" id="user_password" class="form-control" placeholder="Enter your password" name="user_password" required autocomplete="off">
      </div>

      <!-- Login Button -->
      <div class="d-grid mb-3">
        <input type="submit" value="Login" class="btn btn-custom" name="user_login">
      </div>

      <!-- Register Link -->
      <p class="small text-center fw-bold mb-0">
        Don't have an account? 
        <a href="user_registeration.php" class="register-link">Register</a>
      </p>
    </form>
  </div>
</div>

</body>
</html>

<?php
if(isset($_POST['user_login'])){
    $user_username = $_POST['user_username'];
    $user_password = $_POST['user_password'];

    $select_query = "SELECT * FROM `user_table` WHERE username='$user_username'";
    $result = mysqli_query($con, $select_query);
    $rows_count = mysqli_num_rows($result);
    $row_data = mysqli_fetch_assoc($result);
    $user_ip = getIPAddress();

    // cart items
    $select_query_cart = "SELECT * FROM `cart_details` WHERE ip_address='$user_ip'";
    $select_cart = mysqli_query($con, $select_query_cart);
    $rows_count_cart = mysqli_num_rows($select_cart);

    if($rows_count > 0){
        $_SESSION['username'] = $user_username;
        if(password_verify($user_password, $row_data['user_password'])){
            echo "<script>alert('Login Successful')</script>";
            if($rows_count == 1 && $rows_count_cart == 0){
                echo "<script>window.open('profile.php','_self')</script>";
            } else {
                echo "<script>window.open('payment.php','_self')</script>";
            }
        } else {
            echo "<script>alert('Invalid Credentials')</script>";
        }
    } else {
        echo "<script>alert('Invalid Credentials')</script>";
    }
}
?>
