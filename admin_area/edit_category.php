<?php
include('../includes/connect.php');

// Check if edit_id is set
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    // Fetch category data
    $get_category = "SELECT * FROM `categories` WHERE category_id = $edit_id";
    $result = mysqli_query($con, $get_category);
    $row = mysqli_fetch_assoc($result);
    $category_title = $row['category_title'];
}

// Handle update form submission
if (isset($_POST['update_category'])) {
    // Sanitize the input
    $new_title = mysqli_real_escape_string($con, $_POST['category_title']);

    // Update query
    $update_query = "UPDATE `categories` SET category_title = '$new_title' WHERE category_id = $edit_id";
    $update_result = mysqli_query($con, $update_query);

    if ($update_result) {
        echo "<script>alert('✅ Category updated successfully!'); window.location.href='index.php?view_categories';</script>";
    } else {
        echo "<script>alert('❌ Failed to update category.');</script>";
    }
}

// Handle delete action
if (isset($_POST['delete_category'])) {
    // Delete query
    $delete_query = "DELETE FROM `categories` WHERE category_id = $edit_id";
    $delete_result = mysqli_query($con, $delete_query);

    if ($delete_result) {
        echo "<script>alert('✅ Category deleted successfully!'); window.location.href='index.php?view_categories';</script>";
    } else {
        echo "<script>alert('❌ Failed to delete category.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 10px;
        }

        .text-success {
            color: #28a745 !important;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .btn-primary, .btn-danger {
            border-radius: 20px;
            font-size: 16px;
            padding: 10px 20px;
            margin-top: 15px;
        }

        .btn-primary:hover, .btn-danger:hover {
            transform: scale(1.05);
            transition: all 0.3s;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .back-btn {
            font-size: 16px;
            margin-bottom: 15px;
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .back-btn:hover {
            background-color: #138496;
            border-color: #117a8b;
        }

        .header, .footer {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
        }

        .footer {
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header text-center">
        <h2>Edit Category</h2>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">Category Management</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php?view_categories">View Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content Section -->
    <div class="container mt-5">
        <!-- Back Button -->
        <a href="index.php?view_categories" class="btn back-btn"><i class="fa-solid fa-arrow-left"></i> Back to Categories List</a>

        <form method="post" class="w-50 mx-auto mt-4">
            <div class="form-group">
                <label for="category_title">Category Title</label>
                <input type="text" name="category_title" id="category_title" class="form-control" value="<?php echo $category_title; ?>" required>
            </div>

            <!-- Update Category Button -->
            <button type="submit" name="update_category" class="btn btn-primary mr-2">Update Category</button>

            <!-- Delete Category Button -->
            <button type="submit" name="delete_category" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete Category</button>
        </form>
    </div>

    <!-- Footer Section -->
    <footer class="footer text-center">
        <p>&copy; 2025 Category Management. All Rights Reserved.</p>
    </footer>

    <!-- FontAwesome CDN -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
