<?php
include('../includes/connect.php');

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete category from database
    $delete_query = "DELETE FROM `categories` WHERE category_id = $delete_id";
    $run_delete = mysqli_query($con, $delete_query);

    if ($run_delete) {
        echo "<script>alert('🗑️ Category deleted successfully!'); window.location.href='index.php?view_categories';</script>";
    } else {
        echo "<script>alert('❌ Failed to delete category.');</script>";
    }
}
?>
