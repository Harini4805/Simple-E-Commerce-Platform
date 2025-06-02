<?php
include('../includes/connect.php');
include('../functions/common_function.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
      background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);
      font-family: 'Roboto', sans-serif;
      overflow-x: hidden;
      height: 100vh;
    }

        .container {
            margin-top: 80px;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border-radius: 30px;
            border: 1px solid #ddd;
            padding: 15px 20px;
            font-size: 16px;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #ff6f61;
            box-shadow: 0 0 10px rgba(255, 111, 97, 0.6);
        }

        .form-label {
            font-size: 16px;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }

        .register-btn {
            background-color: #ff6f61;
            color: #fff;
            padding: 15px 25px;
            font-size: 18px;
            border: none;
            border-radius: 30px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .register-btn:hover {
            background-color: #e4574f;
            transform: scale(1.05);
        }

        .text-center {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        .small {
            font-size: 14px;
            color: #777;
        }

        .small a {
            color: #ff6f61;
            text-decoration: none;
        }

        .small a:hover {
            text-decoration: underline;
        }

        .form-outline {
            margin-bottom: 20px;
        }

        .image-preview {
            display: block;
            margin: 20px auto;
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #ddd;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .row {
            display: flex;
            justify-content: center;
        }

        .form-control {
            animation: inputAnimation 0.5s ease-out;
        }

        @keyframes inputAnimation {
            0% {
                transform: scale(0.9);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .form-control:focus {
            animation: focusAnimation 0.3s ease-out;
        }

        @keyframes focusAnimation {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.05);
            }
        }
    </style>
</head>
<body>

<div class="container-fluid my-3">
    <div class="row d-flex align-items-center justify-content-center">
        <div class="col-lg-12 col-xl-6">
            <div class="form-container">
                <h2 class="text-center">New User Registration</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-outline mb-4">
                        <label for="user_username" class="form-label">Username</label>
                        <input type="text" id="user_username" class="form-control" placeholder="Enter your username" autocomplete="off" required name="user_username"/>
                    </div>
                    <div class="form-outline mb-4">
                        <label for="user_email" class="form-label">Email</label>
                        <input type="email" id="user_email" class="form-control" placeholder="Enter your email" autocomplete="off" required name="user_email"/>
                    </div>
                    <div class="form-outline mb-4">
                        <label for="user_image" class="form-label">User Image</label>
                        <input type="file" id="user_image" class="form-control" required name="user_image"/>
                    </div>
                    <div class="form-outline mb-4">
                        <label for="user_password" class="form-label">Password</label>
                        <input type="password" id="user_password" class="form-control" placeholder="Enter your password" autocomplete="off" required name="user_password"/>
                    </div>
                    <div class="form-outline mb-4">
                        <label for="conf_user_password" class="form-label">Confirm Password</label>
                        <input type="password" id="conf_user_password" class="form-control" placeholder="Confirm password" autocomplete="off" required name="conf_user_password"/>
                    </div>
                    <div class="form-outline mb-4">
                        <label for="user_address" class="form-label">Address</label>
                        <input type="text" id="user_address" class="form-control" placeholder="Enter your address" autocomplete="off" required name="user_address"/>
                    </div>
                    <div class="form-outline mb-4">
                        <label for="user_contact" class="form-label">Contact</label>
                        <input type="text" id="user_contact" class="form-control" placeholder="Enter your mobile number" autocomplete="off" required name="user_contact"/>
                    </div>
                    <div class="mt-4 pt-2">
                        <input type="submit" value="Register" class="register-btn" name="user_register">
                        <p class="small fw-bold mt-2 pt-1 mb-0">Already have an account? <a href="user_login.php">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
if(isset($_POST['user_register'])){
    $user_username = $_POST['user_username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $hash_password = password_hash($user_password, PASSWORD_DEFAULT);
    $conf_user_password = $_POST['conf_user_password'];
    $user_address = $_POST['user_address'];
    $user_contact = $_POST['user_contact'];
    $user_image = $_FILES['user_image']['name'];
    $user_image_tmp = $_FILES['user_image']['tmp_name'];
    $user_ip = getIPAddress();

    $select_query = "SELECT * FROM `user_table` WHERE username='$user_username' OR user_email='$user_email'";
    $result = mysqli_query($con, $select_query);
    $rows_count = mysqli_num_rows($result);
    if($rows_count > 0){
        echo "<script>alert('Username and Email already exists')</script>";
    } else if($user_password != $conf_user_password){
        echo "<script>alert('Passwords do not match')</script>";
    } else {
        move_uploaded_file($user_image_tmp, "./user_images/$user_image");
        $insert_query = "INSERT INTO `user_table` (username, user_email, user_password, user_image, user_ip, user_address, user_mobile)
        VALUES ('$user_username', '$user_email', '$hash_password', '$user_image', '$user_ip', '$user_address', '$user_contact')";
        $sql_execute = mysqli_query($con, $insert_query);
        if($sql_execute){
            echo "<script>alert('User Registered Successfully')</script>";
        } else {
            die(mysqli_error($con));
        }
    }

    $select_cart_items = "SELECT * FROM `cart_details` WHERE ip_address = '$user_ip'";
    $result_cart = mysqli_query($con, $select_cart_items);
    $rows_count = mysqli_num_rows($result_cart);
    if($rows_count > 0){
        $_SESSION['username'] = $user_username;
        echo "<script>alert('You have items in your cart')</script>";
        echo "<script>window.open('checkout.php', '_self')</script>";
    } else {
        echo "<script>window.open('../index.php', '_self')</script>";
    }
}
?>
</body>
</html>
