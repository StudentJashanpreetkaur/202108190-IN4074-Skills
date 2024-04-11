<?php
include 'config.php';

$sql = "SELECT * FROM fooditems";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Food Items</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Food Items</h1>
        <div class="row">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="col-md-4">';
                    echo '<div class="card mb-4">';
                    echo '<img src="' . $row["image_path"] . '" class="card-img-top" alt="' . $row["name"] . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row["name"] . '</h5>';
                    echo '<p class="card-text">Price: $' . $row["price"] . '</p>';
                    echo '<p class="card-text">' . $row["description"] . '</p>';
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
