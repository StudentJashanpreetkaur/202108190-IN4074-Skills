<?php
include 'config.php';

$name = $description = $price = $stock = '';
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Check if a new image file is uploaded
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "C:\\xampp\\htdocs\\images\\";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists and delete old image
        if (file_exists($target_file)) {
            if (!unlink($target_file)) {
                $errors['image'] = "Unable to delete existing image file.";
            }
        }

        // Upload new image
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = basename($_FILES["image"]["name"]);
        } else {
            $errors['image'] = "Sorry, there was an error uploading your file.";
        }
    }

    // Update product details including image path if provided
    $update_values = "name='$name', description='$description', price='$price', stock='$stock'";
    if (isset($image_path)) {
        $update_values .= ", image_path='$image_path'";
    }
    $sql = "UPDATE fooditems SET $update_values WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: main.php");
        exit();
    } else {
        $errors['database'] = "Error updating record: " . mysqli_error($conn);
    }
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM fooditems WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Food item not found";
        exit();
    }
} else {
    echo "Food item ID not provided";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Food Item</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Update Food Item</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"><?php echo isset($row['description']) ? $row['description'] : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo isset($row['price']) ? $row['price'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="text" class="form-control" id="stock" name="stock" value="<?php echo isset($row['stock']) ? $row['stock'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control-file" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Update Food Item</button>
        </form>
    </div>
</body>
</html>
