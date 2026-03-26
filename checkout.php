<?php
include 'db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['cart']) || empty($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty']);
    exit;
}

$cart = $data['cart'];
$total = 0;

$order_id = rand(100000, 999999);


$conn->begin_transaction();

try {
    foreach ($cart as $item) {
        
        $product_id = intval($item['id']); 
        $qty = intval($item['qty']);
        $price = floatval($item['price']);
        $subtotal = $qty * $price;
        $total += $subtotal;
        $update_stock = "UPDATE data3 SET stock = stock - ? WHERE product_id = ?";
        $stmt = $conn->prepare($update_stock);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ii", $qty, $product_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to update stock: " . $stmt->error);
        }
        
        
        $p_name = $item['name'];
        $sql_order = "INSERT INTO orders (product_name, price, customer_name, customer_email, shipping_address) 
                      VALUES (?, ?, 'Cart Customer', 'N/A', 'Checkout from Cart')";
        $stmt_ord = $conn->prepare($sql_order);
        $stmt_ord->bind_param("sd", $p_name, $subtotal);
        $stmt_ord->execute();

        $stmt->close();
    }
    
    
    $conn->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Order placed successfully and stock updated!',
        'order_id' => $order_id,
        'total' => $total
    ]);
    
} catch (Exception $e) {
    
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
