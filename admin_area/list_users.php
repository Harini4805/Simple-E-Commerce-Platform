<?php
// Database connection
include('../includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
</head>
<body>

<div class="container mt-5">
    <h3 class="text-center text-success mb-4">All Registered Users</h3>

    <?php
    $get_users = "SELECT * FROM `user_table`";
    $result = mysqli_query($con, $get_users);
    $row_count = mysqli_num_rows($result);

    if ($row_count == 0) {
        echo "<h4 class='text-center bg-danger text-light p-3'>No Users Found</h4>";
    } else {
        echo "
        <table class='table table-bordered'>
            <thead class='bg-primary text-light'>
                <tr>
                    <th>S.No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Image</th>
                    <th>Address</th>
                    <th>Mobile</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody class='bg-secondary text-light'>
        ";

        $number = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $number++;
            $username = $row['username'];
            $user_email = $row['user_email'];
            $user_image=$row['user_image'];
            $user_address = $row['user_address'];
            $user_mobile = $row['user_mobile'];

            echo "
                <tr>
                    <td>$number</td>
                    <td>$username</td>
                    <td>$user_email</td>
                    <td><img src='../users_area/user_images/$user_image' alt='$username' class='product_img'/></td>
                    <td>$user_address</td>
                    <td>$user_mobile</td>
                     <td><a href='' class='text-light'><i class='fa-solid fa-trash'></i></a>
                </td>
                </tr>
            ";
        }

        echo "</tbody></table>";
    }
    ?>
</div>

</body>
</html>
