<h3 class="text-center text-success">All Brands</h3>
<table class="table table-bordered mt-5 text-center">
    <thead class="bg-primary text-light">
        <tr>
            <th>Sno</th>
            <th>Brand Title</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody class="bg-secondary text-light">
        <?php
        include('../includes/connect.php'); // Make sure this is included if not already
        $select_brands = "SELECT * FROM `brands`";
        $result = mysqli_query($con, $select_brands);
        $number = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $brand_id = $row['brand_id'];
            $brand_title = $row['brand_title'];
            $number++;
            ?>
            <tr>
                <td><?php echo $number; ?></td>
                <td><?php echo $brand_title; ?></td>
                <td>
                    <a href='edit_brand.php?edit_id=<?php echo $brand_id; ?>' class='text-light'>
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
                <td>
                    <a href='delete_brand.php?delete_id=<?php echo $brand_id; ?>' class='text-light' onclick="return confirm('Are you sure you want to delete this brand?');">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
