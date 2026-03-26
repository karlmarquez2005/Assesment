<?php
include 'db.php';

// 1. Clear any accidental spaces or previous output
ob_clean(); 

if (!isset($_GET['id'])) {
    die("Error: No ID provided in the URL.");
}

$id = intval($_GET['id']);

// 2. Double check your table name 'data3'
$sql = "SELECT product_image FROM data3 WHERE product_id = $id";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    $image = $row['product_image'];

    if (!empty($image)) {
        // 3. Force the header for JPG
        header("Content-Type: image/jpeg");
        echo $image;
        exit;
    } else {
        echo "Error: The image data for ID $id is empty in the database.";
    }
} else {
    echo "Error: Could not find product with ID $id.";
}
?>