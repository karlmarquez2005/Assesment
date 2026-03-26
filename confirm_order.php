<?php
include 'db.php';
$p_id = $_GET['id'] ?? '';
$p_name = $_GET['name'] ?? '';
$p_price = $_GET['price'] ?? '';

if (!$p_id) { header("Location: VegetableMarket.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: white; padding-top: 50px; }
        .form-container { max-width: 500px; margin: auto; background: #1e1e1e; padding: 30px; border-radius: 10px; }
        
        .product-preview {
            width: 100%;
            max-height: 250px;
            object-fit: contain; 
            border-radius: 8px;
            background-color: #2a2a2a;
            margin-bottom: 20px;
            border: 1px solid #333;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2 class="text-center">Confirm Your Purchase</h2>
        
        <div class="text-center">
            <img src="showimage.php?id=<?php echo htmlspecialchars($p_id); ?>" 
                 alt="<?php echo htmlspecialchars($p_name); ?>" 
                 class="product-preview">
        </div>

        <p class="mb-1">Item: <strong><?php echo htmlspecialchars($p_name); ?></strong></p>
        <p>Price: <strong class="text-success">$<?php echo number_format((float)$p_price, 2); ?></strong></p>
        <hr>
        
        <form action="process_order.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($p_id); ?>">
            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($p_name); ?>">
            <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($p_price); ?>">

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="customer_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="customer_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Shipping Address</label>
                <textarea name="address" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100">Confirm Order</button>
            <a href="VegetableMarket.php" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>