<?php
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marquez Market</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <style>
        .card-img-top { width: 130px; height: 120px; object-fit: cover; display: block; margin: 15px auto 0; }
        .btn, .btn-close { background-color: pink; }
        body { background: linear-gradient(180deg,#fff8fb 0%,#fff0f6 100%); font-family: system-ui, Arial; }
        .product-card { border-radius: 20px; border: 1px solid #333; margin-top: 50px; background: #fff; }
    </style>
</head>
<body class="bg-danger-subtle">

<nav class="navbar bg-body-tertiary border border-dark">
    <div class="container">
        <span class="navbar-brand mb-0 h1">Aali Market</span>
    </div>
</nav>

<div class="container">
    <div class="row">
        <?php
        $sql = "SELECT * FROM products";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card product-card">
                <img src="images/<?= $row["img"] ?>" class="card-img-top">
                <div class="card-body d-flex justify-content-between">
                    <span class="fs-5">$<?= $row["price"] ?></span>
                    <span class="fs-5"><?= $row["name"] ?></span>
                </div>
                <div class="container">
                    <button class="btn w-100 mb-3 rounded-5" data-bs-toggle="modal" data-bs-target="#prod-<?= $row["product_id"] ?>">Buy</button>
                </div>
            </div>

            <div class="modal fade" id="prod-<?= $row["product_id"] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger-subtle">
                            <h5 class="modal-title">Order Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="insert.php" method="GET">
                                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                                <div class="text-center mb-3">
                                    <img src="images/<?= $row['img'] ?>" style="width:100px;">
                                    <h4><?= $row['name'] ?></h4>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Price</span>
                                    <input type="text" class="form-control" name="price" value="<?= $row['price'] ?>" readonly>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Quantity</span>
                                    <input type="number" class="form-control" name="qty" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Username</span>
                                    <input type="text" class="form-control" name="user" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Contact</span>
                                    <input type="text" class="form-control" name="contact" required>
                                </div>
                                <button type="submit" class="btn w-100 rounded-5">Confirm Purchase</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>


