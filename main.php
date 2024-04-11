<?php
include 'config.php';

// Check if the form is submitted for deleting a product
if(isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM fooditems WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        echo '<div class="alert alert-success" role="alert">Food item deleted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error deleting food item: ' . mysqli_error($conn) . '</div>';
    }
    mysqli_stmt_close($stmt);
}

// Retrieve food items from the database
$sql = "SELECT * FROM fooditems";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Menu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Navbar styling */
        .navbar {
            background-color: #343a40; /* Dark gray background color */
            padding: 10px 0; /* Add padding to the top and bottom */
        }

        .navbar ul {
            list-style-type: none; /* Remove bullet points */
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
        }

        .navbar ul li {
            display: inline; /* Display list items horizontally */
            margin-right: 20px; /* Add space between list items */
        }

        .navbar ul li a {
            color: white; /* Text color */
            text-decoration: none; /* Remove underline */
        }

        .navbar ul li a:hover {
            color: lightgray; /* Text color on hover */
        }
    </style>
</head>
<body>
    <header class="navbar">
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="#">History</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1 class="mt-4">Food Menu</h1>
        <a href="create.php" class="btn btn-primary mb-4">Add New Food Item</a>
        
        <div class="row">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="col-md-4">';
                    echo '<div class="card mb-4">';
                    
                    // Check if image_path key exists
                    $imageSrc = isset($row["image_path"]) ? $row["image_path"] : "placeholder.png";
                    echo '<img src="' . $imageSrc . '" class="card-img-top" alt="' . $row["name"] . '">';
                    
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row["name"] . '</h5>';
                    
                    // Check if price key exists
                    $priceText = isset($row["price"]) ? '$' . $row["price"] : 'Not available';
                    echo '<p class="card-text">Price: ' . $priceText . '</p>';
                    
                    echo '<a href="read.php?id=' . $row["id"] . '" class="btn btn-primary mr-2">View</a>';
                    echo '<a href="update.php?id=' . $row["id"] . '" class="btn btn-secondary mr-2">Edit</a>';
                    echo '<a href="index.php?delete_id=' . $row["id"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this food item?\')">Delete</a>';
                    echo '<a href="order.php?id=' . $row["id"] . '" class="btn btn-success">Order</a>';
                    echo '</div></div></div>';
                }
            } else {
                echo '<p>No food items available.</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
