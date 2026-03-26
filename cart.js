(function(){
  const CART_KEY = 'plushie_cart_v1';

  function getCart(){
    try{ return JSON.parse(localStorage.getItem(CART_KEY)) || []; }catch(e){ return []; }
  }
  function saveCart(cart){ localStorage.setItem(CART_KEY, JSON.stringify(cart)); }
  
  function updateCount(){
    const cart = getCart();
    const count = cart.reduce((s,i)=>s+i.qty,0);
    const el = document.getElementById('cart-count');
    if(el) el.textContent = count;
  }

  // --- NEW: Reusable Checkout Logic ---
  function performCheckout() {
    const cart = getCart();
    if (cart.length === 0) {
      alert("Your cart is empty!");
      return;
    }

    fetch('checkout.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ cart: cart })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("Order Successful! Stock has been updated.");
        saveCart([]); // Clear storage
        location.reload(); // Refresh to show new stock on page
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch(err => {
      console.error("Checkout Error:", err);
      alert("Failed to process order.");
    });
  }

  function addItem(item){
    const cart = getCart();
    const idx = cart.findIndex(i => i.id === item.id);
    if(idx > -1){ cart[idx].qty += item.qty; } else { cart.push(item); }
    saveCart(cart); 
    updateCount();
  }

  function updateQty(idx, newQty){ 
    const cart = getCart(); 
    if(newQty <= 0){ cart.splice(idx,1); } else { cart[idx].qty = newQty; } 
    saveCart(cart); 
    updateCount(); 
    renderModal(); 
  }

  function renderModal(){
    const cart = getCart();
    const tbody = document.getElementById('cart-table-body');
    const totalEl = document.getElementById('cart-total');
    if(!tbody) return;
    tbody.innerHTML = '';
    let total = 0;
    
    if(cart.length === 0){
      tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Your cart is empty</td></tr>';
    } else {
      cart.forEach((it,idx)=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${it.name}</td>
          <td class="text-end">
            <div class="d-flex align-items-center justify-content-end">
              <button class="btn btn-sm btn-outline-light qty-dec" data-index="${idx}">−</button>
              <span class="qty-display mx-2">${it.qty}</span>
              <button class="btn btn-sm btn-outline-light qty-inc" data-index="${idx}">+</button>
            </div>
          </td>
          <td class="text-end">$${(it.price).toFixed(2)}</td>
          <td class="text-end">$${(it.price*it.qty).toFixed(2)}</td>
          <td class="text-end"><button class="btn btn-sm btn-danger remove-item" data-index="${idx}">Remove</button></td>
        `;
        tbody.appendChild(tr);
        total += it.price*it.qty;
      });

      document.querySelectorAll('.qty-inc').forEach(btn => {
        btn.onclick = () => updateQty(parseInt(btn.dataset.index), getCart()[btn.dataset.index].qty + 1);
      });
      document.querySelectorAll('.qty-dec').forEach(btn => {
        btn.onclick = () => updateQty(parseInt(btn.dataset.index), getCart()[btn.dataset.index].qty - 1);
      });
      document.querySelectorAll('.remove-item').forEach(btn => {
        btn.onclick = () => { 
            const cart = getCart();
            cart.splice(parseInt(btn.dataset.index), 1);
            saveCart(cart);
            updateCount();
            renderModal();
        };
      });
    }
    if(totalEl) totalEl.textContent = `$${total.toFixed(2)}`;
  }

  document.addEventListener('DOMContentLoaded', ()=>{
    updateCount();

    const quickAdd = (btn) => {
      addItem({
        id: btn.dataset.id, 
        name: btn.dataset.name, 
        price: parseFloat(btn.dataset.price), 
        qty: 1
      });
      const modalEl = document.getElementById('cartModal');
      if(modalEl){ 
        bootstrap.Modal.getOrCreateInstance(modalEl).show(); 
        renderModal(); 
      }
    };

    // ADD TO CART behavior
    document.querySelectorAll('.add-to-cart').forEach(btn => {
      btn.onclick = () => quickAdd(btn);
    });

    // --- UPDATED BUY NOW BEHAVIOR ---
    // Instead of opening the modal, this redirects to the confirmation page
    document.querySelectorAll('.buy-now').forEach(btn => {
      btn.onclick = () => {
        const id = btn.dataset.id;
        const name = encodeURIComponent(btn.dataset.name);
        const price = btn.dataset.price;
        
        // Redirect to confirm_order.php with product details
        window.location.href = `confirm_order.php?id=${id}&name=${name}&price=${price}`;
      };
    });

    const checkoutBtn = document.getElementById('cart-checkout');
    if(checkoutBtn) {
      checkoutBtn.onclick = () => performCheckout();
    }

    const cartModal = document.getElementById('cartModal');
    if(cartModal) cartModal.addEventListener('show.bs.modal', renderModal);
    
    const clearBtn = document.getElementById('cart-clear');
    if(clearBtn) clearBtn.onclick = () => { saveCart([]); updateCount(); renderModal(); };
  });
})();