<?php
include("db.php");


if ($_SERVER['REQUEST_METHOD'] === "GET") {
    
    $product_id = $_GET["product_id"];
    $price      = $_GET["price"];
    $qty        = $_GET["qty"];
    $user       = $_GET["user"];
    $contact    = $_GET["contact"];
    
    
    $total = $price * $qty;

    
    $sql_order = "INSERT INTO orders (product_id, qty, total, user, contact) 
                  VALUES ('$product_id', '$qty', '$total', '$user', '$contact')";

    
    $sql_update_stock = "UPDATE products SET stock = stock - $qty WHERE product_id = '$product_id'";

    
    if (mysqli_query($conn, $sql_order) && mysqli_query($conn, $sql_update_stock)) {
        
        echo "<script>
                alert('Order Placed Successfully! Stock has been updated.');
                window.location.href='index.php';
              </script>";
    } else {
        
        echo "Error: " . mysqli_error($conn);
    }
}
?>


