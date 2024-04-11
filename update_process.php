<?php
include 'config.php';

// Check if the 'id' parameter is passed in the URL
if(isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    // Check if id is valid
    if ($id === false || $id === null) {
        // If id is invalid, display error message
        echo '<div class="container">';
        echo '<p>Invalid food item ID.</p>';
        echo '</div>';
        exit(); // Exit the script
    }

    // Prepare and execute SQL query to fetch the food item with the given id
    $sql = "SELECT * FROM fooditems WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Check if preparation of SQL statement is successful
    if (!$stmt) {
        // If preparation fails, display error message
        echo '<div class="container">';
        echo '<p>Database error.</p>';
        echo '</div>';
        exit(); // Exit the script
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    
    // Check if the food item exists
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Display food item details
        echo '<div class="container">';
        echo '<h2>' . htmlspecialchars($row['name']) . '</h2>'; // Sanitize output
        echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['name']) . '" style="max-width: 100%;">'; // Sanitize output
        echo '<p>Price: $' . htmlspecialchars($row['price']) . '</p>'; // Sanitize output
        echo '<p>Description: ' . htmlspecialchars($row['description']) . '</p>'; // Sanitize output
        echo '</div>';
    } else {
        // If food item not found, display error message
        echo '<div class="container">';
        echo '<p>Food item not found.</p>';
        echo '</div>';
    }
    
    // Close the prepared statement
    mysqli_stmt_close($stmt);
} else {
    // If 'id' parameter is not provided, display error message
    echo '<div class="container">';
    echo '<p>Invalid request.</p>';
    echo '</div>';
}
?>
