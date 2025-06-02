<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../includes/connect.php'); // Include your DB connection

$username_session = $_SESSION['username'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $delete_query = "DELETE FROM user_table WHERE username = '$username_session'";
        $result = mysqli_query($con, $delete_query);
        if ($result) {
            session_destroy();
            echo "<script>alert('Account deleted successfully');</script>";
            echo "<script>window.open('../index.php', '_self');</script>";
        }
    }
    if (isset($_POST['dont_delete'])) {
        echo "<script>window.open('profile.php', '_self');</script>";
    }
}
?>

<h3 class="text-danger mb-4">Delete Account</h3>
<form action="" method="post" class="mt-5">
    <div class="form-outline mb-4">
        <input type="submit" class="form-control w-50 m-auto bg-danger text-white" name="delete" value="Delete Account">
    </div>
    <div class="form-outline mb-4">
        <input type="submit" class="form-control w-50 m-auto bg-secondary text-white" name="dont_delete" value="Don't Delete Account">
    </div>
</form>
