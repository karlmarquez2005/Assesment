<?php
include 'db.php';
$products = mysqli_query($conn, "SELECT * FROM data3");
if (!$products) {
    die('Database query failed: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Market</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
      :root {
        --accent-color: #d4a373; 
        --text-dark: #2b2d42;
        --soft-bg: #faf9f6;
      }

      body { 
        font-family: 'Inter', sans-serif; 
        background-color: #ffffff; 
        color: var(--text-dark);
        scroll-behavior: smooth;
        padding-top: 80px;
      }

      h1, h2, h3, .navbar-brand { font-family: 'Playfair Display', serif; }

      
      .navbar {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1rem 0;
      }
      .navbar-brand { font-weight: 700; font-size: 1.5rem; color: var(--text-dark) !important; }
      .nav-link { font-weight: 500; color: var(--text-dark) !important; margin: 0 10px; }

     
      
      .product-card {
        border: none;
        background: transparent;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        margin-bottom: 40px;
      }
      .product-card:hover { transform: translateY(-10px); }
      
      .product-img-container { 
        width: 100%; 
        aspect-ratio: 1 / 1; 
        border-radius: 24px;
        overflow: hidden; 
        background-color: var(--soft-bg);
        border: none;
        position: relative;
        box-shadow: 0 10px 20px rgba(0,0,0,0.02);
      }
      .product-img-container img { 
        width: 100%; height: 100%; object-fit: contain; padding: 20px; 
        transition: transform 0.5s ease;
      }
      .product-card:hover img { transform: scale(1.1); }
      
      .price-tag { font-size: 1.25rem; color: var(--accent-color); font-weight: 600; }
      .btn-buy-now-trigger { border-radius: 10px; font-weight: 600; padding: 8px 20px; }

      
      .modal-content { border-radius: 30px; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.15); }
      #cartModal .modal-content { background-color: #ffffff !important; color: var(--text-dark) !important; }
      #cartModal .table { color: var(--text-dark) !important; }
      #cartModal .btn-close { filter: none; }
      
      .form-pop { 
        border-radius: 12px; 
        border: 1px solid #eee; 
        padding: 12px; 
        background: #fdfdfd; 
        margin-bottom: 12px;
      }

      .total-price-box { 
        background-color: var(--soft-bg); 
        padding: 20px; 
        border-radius: 20px; 
        border: 1px dashed var(--accent-color);
      }
    </style>
</head>
<body>
    <header>
      <nav class="navbar navbar-expand-md fixed-top">
        <div class="container">
          <a class="navbar-brand" href="#myCarousel">Kiosk Market</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ms-auto mb-2 mb-md-0">
              <li class="nav-item"><a class="nav-link" href="#myCarousel">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="#products">Shop</a></li>
              <li class="nav-item"><a class="nav-link" href="#about">Story</a></li>
              <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
            <button class="btn btn-dark rounded-pill px-4 ms-lg-3" type="button" data-bs-toggle="modal" data-bs-target="#cartModal">
              Cart <span id="cart-count" class="badge bg-light text-dark ms-1">0</span>
            </button>
          </div>
        </div>
      </nav>
    </header>

    

      <div id="products" class="container mt-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Product</h2>
            <p class="text-muted">Fresh Fruits and Vegetable</p>
        </div>
        <div class="row">
          <?php if ($products && mysqli_num_rows($products) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($products)): ?>
              <div class="col-lg-3 col-md-6 mb-4">
                <div class="product-card">
                  <div class="product-img-container">
                    <img src="showimage.php?id=<?php echo $row['product_id']; ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                  </div>
                  <h3 class="h5 mt-3 mb-1"><?php echo htmlspecialchars($row['product_name']); ?></h3>
                  <p class="price-tag">$<?php echo number_format($row['price'], 2); ?></p>
                  <?php if($row['stock'] > 0): ?>
                    <p class="text-muted small mb-2">In stock: <?php echo $row['stock']; ?></p>
                  <?php else: ?>
                    <p class="text-danger small mb-2">Sold out</p>
                  <?php endif; ?>
                  <div class="d-flex gap-2">
                      <button class="btn btn-sm btn-outline-dark flex-grow-1 add-to-cart" 
                              data-id="<?php echo $row['product_id']; ?>" 
                              data-name="<?php echo htmlspecialchars($row['product_name']); ?>" 
                              data-price="<?php echo $row['price']; ?>">+ Cart</button>
                      
                      <?php if($row['stock'] > 0): ?>
                          <button class="btn btn-sm btn-dark flex-grow-1 btn-buy-now-trigger" 
                                  data-id="<?php echo $row['product_id']; ?>" 
                                  data-name="<?php echo htmlspecialchars($row['product_name']); ?>" 
                                  data-price="<?php echo $row['price']; ?>">Buy Now</button>
                      <?php else: ?>
                          <button class="btn btn-sm btn-light flex-grow-1" disabled>Sold Out</button>
                      <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>

      

      
      <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="cartModalLabel">Your cart</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <table class="table">
                <thead>
                  <tr><th>Product</th><th class="text-end">Qty</th><th class="text-end">Price</th><th class="text-end">Subtotal</th><th></th></tr>
                </thead>
                <tbody id="cart-table-body"></tbody>
              </table>
              <div class="text-end fw-bold">Total: <span id="cart-total">$0.00</span></div>
            </div>
            <div class="modal-footer">
              <button id="cart-clear" type="button" class="btn btn-outline-danger">Clear cart</button>
              <button id="cart-checkout" type="button" class="btn btn-primary">Checkout</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="buyNowModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
          <div class="modal-content p-4">
            <div class="modal-body text-center">
              <img id="pop-img" src="" class="mb-4" style="width: 140px; height: 140px; object-fit: contain;">
              
              <h4 id="pop-name" class="fw-bold mb-1"></h4>
              <p class="text-muted mb-4">Price: $<span id="pop-price"></span></p>

              <div class="d-flex justify-content-center align-items-center mb-4">
                <button type="button" class="btn btn-outline-secondary rounded-circle" onclick="updatePopQty(-1)" style="width:40px;height:40px;">-</button>
                <input type="text" id="pop-qty" value="1" readonly class="form-control text-center mx-3 fw-bold border-0 bg-transparent" style="width: 50px; font-size: 1.2rem;">
                <button type="button" class="btn btn-outline-secondary rounded-circle" onclick="updatePopQty(1)" style="width:40px;height:40px;">+</button>
              </div>

              <div class="total-price-box mb-4">
                <div class="small text-uppercase tracking-wider text-muted">Subtotal</div>
                <div class="h2 fw-bold text-dark">$<span id="pop-total">0.00</span></div>
              </div>

              <form action="process_order.php" method="POST" class="text-start">
                <input type="hidden" name="product_id" id="pop-id-input">
                <input type="hidden" name="quantity" id="pop-qty-input" value="1">
                
                <input type="text" name="customer_name" class="form-control form-pop" placeholder="Your Name" required>
                <input type="email" name="customer_email" class="form-control form-pop" placeholder="Email Address" required>
                <textarea name="address" class="form-control form-pop" rows="2" placeholder="Full Shipping Address" required></textarea>

                <button type="submit" class="btn btn-dark w-100 py-3 mt-2 rounded-pill fw-bold">Complete Purchase</button>
              </form>
              
              <button type="button" class="btn btn-link text-muted mt-3 small text-decoration-none" data-bs-dismiss="modal">I'll keep looking</button>
            </div>
          </div>
        </div>
      </div>

      <footer class="container py-5 border-top">
        <div class="row">
            <div class="col-md-6">
                <p class="text-muted">&copy; 2026 Kiosk Market &middot; Harvested with care.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-dark text-decoration-none me-3">Privacy</a>
                <a href="#" class="text-dark text-decoration-none">Terms</a>
            </div>
        </div>
      </footer>
    </main>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/java_script/cart.js"></script>
    <script>
        let unitPrice = 0;
        document.querySelectorAll('.btn-buy-now-trigger').forEach(btn => {
            btn.addEventListener('click', function() {
                unitPrice = parseFloat(this.getAttribute('data-price'));
                document.getElementById('pop-id-input').value = this.getAttribute('data-id');
                document.getElementById('pop-name').innerText = this.getAttribute('data-name');
                document.getElementById('pop-price').innerText = unitPrice.toFixed(2);
                document.getElementById('pop-img').src = 'showimage.php?id=' + this.getAttribute('data-id');
                document.getElementById('pop-qty').value = 1;
                document.getElementById('pop-qty-input').value = 1;
                refreshPopTotal(1);
                new bootstrap.Modal(document.getElementById('buyNowModal')).show();
            });
        });

        function updatePopQty(amt) {
            let qty = parseInt(document.getElementById('pop-qty').value) + amt;
            if (qty >= 1) {
                document.getElementById('pop-qty').value = qty;
                document.getElementById('pop-qty-input').value = qty;
                refreshPopTotal(qty);
            }
        }

        function refreshPopTotal(qty) {
            let total = unitPrice * qty;
            document.getElementById('pop-total').innerText = total.toFixed(2);
        }
    </script>
</body>
</html>