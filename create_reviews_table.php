<?php
include('./includes/connect.php');

// SQL query to create reviews table
$create_table_query = "CREATE TABLE IF NOT EXISTS reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user_table(user_id) ON DELETE CASCADE
)";

// Execute the query
if (mysqli_query($con, $create_table_query)) {
    echo "Reviews table created successfully";
} else {
    echo "Error creating table: " . mysqli_error($con);
}

// Close the connection
mysqli_close($con);
?> 