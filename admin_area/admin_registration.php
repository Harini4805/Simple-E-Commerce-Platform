<?php
// Start session and DB connection
include('../includes/connect.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            overflow: hidden;
            background: #f4f6f9;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container-fluid {
            background-color: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 30px;
            max-width: 800px;
            width: 100%;
        }
        h2 {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            text-align: center;
        }
        .form-outline {
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .form-control {
            border-radius: 10px;
            padding: 1rem;
            font-size: 1rem;
        }
        .bg-primary {
            background-color: #007bff;
            border-radius: 10px;
            padding: 10px 30px;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
        }
        .bg-primary:hover {
            background-color: #0056b3;
        }
        .link-danger {
            color: #e74a3b;
        }
        .link-danger:hover {
            text-decoration: underline;
        }
        .img-fluid {
            max-height: 400px;
            border-radius: 15px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .row {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .col-lg-6 {
            padding: 15px;
        }
        .small {
            font-size: 0.9rem;
        }
        p {
            font-size: 0.9rem;
        }
        .btn {
            border: none;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container-fluid m-3">
        <h2 class="mb-5">Admin Registration</h2>
        <div class="row d-flex justify-content-center">
            <div class="col-lg-6 col-xl-5 text-center">
                <img src="../images/adminreg.jpg" alt="Admin Registration" class="img-fluid">
            </div>
            <div class="col-lg-6 col-xl-4">
                <form action="" method="post">
                    <div class="form-outline mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required class="form-control">
                    </div>
                    <div class="form-outline mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required class="form-control">
                    </div>
                    <div class="form-outline mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required class="form-control">
                    </div>
                    <div class="form-outline mb-4">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required class="form-control">
                    </div>
                    <div>
                        <input type="submit" class="bg-primary py-2 px-3 border-0 text-white" name="admin_registration" value="Register">
                        <p class="small fw-bold mt-2 pt-1">Already have an account? <a href="admin_login.php" class="link-danger">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
if (isset($_POST['admin_registration'])) {
    $admin_name = $_POST['username'];
    $admin_email = $_POST['email'];
    $admin_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($admin_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match')</script>";
        exit();
    }

    // Hash the password
    $hash_password = password_hash($admin_password, PASSWORD_DEFAULT);

    // Check if username or email already exists
    $select_query = "SELECT * FROM `admin_table` WHERE admin_name='$admin_name' OR admin_email='$admin_email'";
    $result = mysqli_query($con, $select_query);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username or Email already exists')</script>";
    } else {
        // Insert into database
        $insert_query = "INSERT INTO `admin_table` (admin_name, admin_email, admin_password) 
                         VALUES ('$admin_name', '$admin_email', '$hash_password')";
        $sql_execute = mysqli_query($con, $insert_query);

        if ($sql_execute) {
            echo "<script>alert('Admin Registered Successfully')</script>";
        } else {
            echo "<script>alert('Error: Could not register admin.')</script>";
        }
    }
}
?>
