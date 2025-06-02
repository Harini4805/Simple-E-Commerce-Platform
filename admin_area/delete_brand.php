<?php
include('../includes/connect.php');

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete brand from the database
    $delete_query = "DELETE FROM `brands` WHERE brand_id = $delete_id";
    $run_delete = mysqli_query($con, $delete_query);

    if ($run_delete) {
        echo "<script>alert('🗑️ Brand deleted successfully!'); window.location.href='index.php?view_brands';</script>";
    } else {
        echo "<script>alert('❌ Failed to delete brand.');</script>";
    }
}
?>
