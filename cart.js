// Carrito de compras
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Función para disminuir cantidad
function decreaseQuantity(id) {
    const itemId = String(id);
    const item = cart.find(item => String(item.id) === itemId);
    if (item) {
        item.quantity -= 1;
        if (item.quantity <= 0) {
            removeItem(itemId);
        } else {
            updateCartCount();
            saveCart();
            showCartModal();
        }
    }
}

// Función para aumentar cantidad
function increaseQuantity(id) {
    const itemId = String(id);
    const item = cart.find(item => String(item.id) === itemId);
    if (item) {
        item.quantity += 1;
        updateCartCount();
        saveCart();
        showCartModal();
    }
}

// Función para remover item
function removeItem(id) {
    const itemId = String(id);
    cart = cart.filter(item => String(item.id) !== itemId);
    updateCartCount();
    saveCart();
    showCartModal();
}

// Función para agregar producto al carrito
function addToCart(product) {
    const newProduct = { ...product, quantity: 1, id: `${Date.now()}-${Math.random()}` };
    cart.push(newProduct);
    updateCartCount();
    saveCart();
}

// Función para actualizar el contador del carrito
function updateCartCount() {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'block' : 'none';
    }
}

// Función para guardar el carrito en localStorage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Función para mostrar el modal del carrito
function showCartModal() {
    const modal = document.getElementById('cart-modal');
    const cartItems = document.getElementById('cart-items');
    const totalPrice = document.getElementById('total-price');

    cartItems.innerHTML = '';
    let total = 0;

    cart.forEach(item => {
        const itemId = String(item.id);
        const itemElement = document.createElement('div');
        itemElement.className = 'cart-item';
        itemElement.setAttribute('data-id', itemId);
        itemElement.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="cart-item-image">
            <div class="cart-item-details">
                <h4>${item.name}</h4>
                <p>Precio: $${item.price.toFixed(2)}</p>
                <div class="quantity-controls">
                    <button class="qty-btn decrease-qty">-</button>
                    <span class="quantity">${item.quantity}</span>
                    <button class="qty-btn increase-qty">+</button>
                    <button class="remove-btn">Eliminar</button>
                </div>
            </div>
        `;
        cartItems.appendChild(itemElement);
        total += item.price * item.quantity;
    });

    totalPrice.textContent = `Total: $${total.toFixed(2)}`;
    
    // Agregar botón de Ordenar si no existe
    if (!document.getElementById('order-btn')) {
        const orderBtn = document.createElement('button');
        orderBtn.id = 'order-btn';
        orderBtn.className = 'order-btn';
        orderBtn.textContent = 'Ordenar';
        orderBtn.addEventListener('click', goToDelivery);
        modal.querySelector('.modal-content').appendChild(orderBtn);
    }
    
    modal.style.display = 'block';
    document.body.style.pointerEvents = 'none';
    modal.style.pointerEvents = 'auto';
}

// Función para ir a Delivery
function goToDelivery() {
    if (cart.length === 0) {
        alert('El carrito está vacío');
        return;
    }
    window.location.href = 'Delivery.html';
}

// Función para cerrar el modal
function closeCartModal() {
    const modal = document.getElementById('cart-modal');
    modal.style.display = 'none';
    document.body.style.pointerEvents = 'auto';
}

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();

    // Agregar event listeners a los botones de agregar al carrito
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', (e) => {
            const card = e.target.closest('.product-card');
            const image = card.querySelector('.product-image').src;
            const name = card.querySelector('.product-name').textContent;
            const priceText = card.querySelector('.product-price').textContent;
            const price = parseFloat(priceText.replace('$', ''));

            addToCart({ image, name, price });
        });
    });

    // Event listener para el botón del carrito
    const cartButton = document.querySelector('.cart');
    if (cartButton) {
        cartButton.addEventListener('click', showCartModal);
    }

    // Event listener para cerrar el modal
    const closeButton = document.getElementById('close-cart-modal');
    if (closeButton) {
        closeButton.addEventListener('click', closeCartModal);
    }

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', (e) => {
        const modal = document.getElementById('cart-modal');
        if (e.target === modal) {
            closeCartModal();
        }
    });

    // Event listeners para botones en el modal (delegation)
    const modal = document.getElementById('cart-modal');
    modal.addEventListener('click', (e) => {
        const target = e.target.nodeType === Node.TEXT_NODE ? e.target.parentElement : e.target;
        const itemElement = target.closest('.cart-item');
        if (!itemElement) return;
        const id = itemElement.getAttribute('data-id');

        if (target.classList.contains('decrease-qty')) {
            decreaseQuantity(id);
        } else if (target.classList.contains('increase-qty')) {
            increaseQuantity(id);
        } else if (target.classList.contains('remove-btn')) {
            removeItem(id);
        }
    });
    
    // Dropdown Productos: toggle en click (útil para móviles)
    document.querySelectorAll('.dropdown-toggle').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const parent = btn.closest('.dropdown');
            if (!parent) return;
            parent.classList.toggle('open');
            const expanded = parent.classList.contains('open');
            btn.setAttribute('aria-expanded', expanded);
            e.stopPropagation();
        });
    });

    // Cerrar dropdown al hacer clic fuera
    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
    });
});