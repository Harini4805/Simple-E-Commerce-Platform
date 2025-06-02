<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../includes/connect.php'); // Ensure your DB connection works

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please log in first'); window.location.href='../user_login.php';</script>";
    exit();
}

// Fetch user details if edit_account is set
if (isset($_GET['edit_account'])) {
    $user_session_name = $_SESSION['username'];
    $select_query = "SELECT * FROM user_table WHERE username='$user_session_name'";
    $result_query = mysqli_query($con, $select_query);
    $row_fetch = mysqli_fetch_assoc($result_query);

    if ($row_fetch) {
        $user_id = $row_fetch['user_id'];
        $username = $row_fetch['username'];
        $user_email = $row_fetch['user_email'];
        $user_address = $row_fetch['user_address'];
        $user_mobile = $row_fetch['user_mobile'];
        $user_image = $row_fetch['user_image'];
    }
}

// Handle form submission
if (isset($_POST['user_update'])) {
    $update_id = $user_id;
    $username = $_POST['user_username'];
    $user_email = $_POST['user_email'];
    $user_address = $_POST['user_address'];
    $user_mobile = $_POST['user_mobile'];

    $user_image_new = $_FILES['user_image']['name'];
    $user_image_tmp = $_FILES['user_image']['tmp_name'];

    // Handle image update
    if (!empty($user_image_new)) {
        move_uploaded_file($user_image_tmp, "./user_images/$user_image_new");
    } else {
        $user_image_new = $user_image; // Keep old image
    }

    // Update query
    $update_data = "UPDATE user_table SET 
        username='$username', 
        user_email='$user_email', 
        user_image='$user_image_new', 
        user_address='$user_address', 
        user_mobile='$user_mobile' 
        WHERE user_id=$update_id";

    $result_query_update = mysqli_query($con, $update_data);

    if ($result_query_update) {
        echo "<script>alert('Account details updated successfully');</script>";
        echo "<script>window.open('logout.php','_self');</script>"; // Refresh session
    } else {
        echo "<script>alert('Failed to update. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .edit_image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-left: 10px;
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Edit Account</h3>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="user_username" value="<?php echo htmlspecialchars($username ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="user_email" value="<?php echo htmlspecialchars($user_email ?? '') ?>" required>
                        </div>
                        <div class="mb-3 d-flex align-items-center">
                            <div class="flex-grow-1">
                                <label class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="user_image">
                            </div>
                            <div>
                                <?php if (!empty($user_image)): ?>
                                    <img src="./user_images/<?php echo htmlspecialchars($user_image) ?>" alt="User Image" class="edit_image">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="user_address" value="<?php echo htmlspecialchars($user_address ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mobile</label>
                            <input type="text" class="form-control" name="user_mobile" value="<?php echo htmlspecialchars($user_mobile ?? '') ?>" required>
                        </div>
                        <div class="text-center">
                            <input type="submit" value="Update" class="btn btn-primary w-50" name="user_update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
