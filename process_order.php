<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $p_id = mysqli_real_escape_string($conn, $_POST['product_id']); // Get the ID
    $p_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $p_price = mysqli_real_escape_string($conn, $_POST['total_price']);
    $c_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $c_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $c_address = mysqli_real_escape_string($conn, $_POST['address']);

    
    mysqli_begin_transaction($conn);

    try {
        
        $sqlOrder = "INSERT INTO orders (product_name, price, customer_name, customer_email, shipping_address) 
                     VALUES ('$p_name', '$p_price', '$c_name', '$c_email', '$c_address')";
        mysqli_query($conn, $sqlOrder);

        
        $sqlStock = "UPDATE data3 SET stock = stock - 1 WHERE product_id = '$p_id'";
        mysqli_query($conn, $sqlStock);

        
        mysqli_commit($conn);

        echo "<body style='background:#121212; color:white; text-align:center; padding-top:100px;'>";
        echo "<h1>Order Success! Stock updated.</h1>";
        echo "<a href='VegetableMarket.php' style='color:#007bff;'>Back to Shop</a>";
        echo "</body>";

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}
?>