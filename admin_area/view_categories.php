<h3 class="text-center text-success">All Categories</h3>
<table class="table table-bordered mt-5 text-center">
    <thead class="bg-primary text-light">
        <tr>
            <th>Sno</th>
            <th>Category Title</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody class="bg-secondary text-light"> 
        <?php
        include('../includes/connect.php'); // Ensure DB connection is included
        $select_cat = "SELECT * FROM `categories`";
        $result = mysqli_query($con, $select_cat);
        $number = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $category_id = $row['category_id'];
            $category_title = $row['category_title'];
            $number++;
            ?>
            <tr>
                <td><?php echo $number; ?></td>
                <td><?php echo $category_title; ?></td>
                <td>
                    <a href='edit_category.php?edit_id=<?php echo $category_id; ?>' class='text-light'>
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
                <td>
                    <a href='delete_category.php?delete_id=<?php echo $category_id; ?>' class='text-light' onclick="return confirm('Are you sure you want to delete this category?');">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
