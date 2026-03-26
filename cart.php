<?php
include 'db.php';
if (!isset($_GET['id'])) {
    die("No ID specified");
}
$id = intval($_GET['id']);
$sql = "SELECT product_image FROM data3 WHERE product_id = $id";
$result = $conn->query($sql);
if (!$result || $result->num_rows === 0) {
    die("No image found");
}
$row = $result->fetch_assoc();
$image = $row['product_image'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->buffer($image);
header("Content-Type: $mime");
echo $image;
exit;