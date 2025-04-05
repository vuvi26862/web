

let cart = JSON.parse(localStorage.getItem("cart")) || [];
//  Hàm hiển thị giỏ hàng
function renderCart() {
    let cartContainer = document.getElementById("cart-item");
    let totalPrice = 0;
    cartContainer.innerHTML = "";

    if (cart.length === 0) {
        cartContainer.innerHTML = "<p>Giỏ hàng trống</p>";
        document.getElementById("total-price").innerText = "0";
        return;
    }

    cart.forEach((item, index) => {
        let itemTotal = item.price * item.quantity;
        totalPrice += itemTotal;
        
        let productHTML = ` 
        <div class="cart-item">
            <img src="${item.img}" width="50">
            <div>
               <p><strong>${item.name}</strong></p>
               <p>${item.price.toLocaleString()}đ</p>
            </div>
            <div class="quantity-controls"> 
                <button onclick="updateQuantity(${index}, -1)">-</button>
                <span>${item.quantity}</span>
                <button onclick="updateQuantity(${index}, +1)">+</button>
            </div>
            <p><strong>${itemTotal.toLocaleString()}đ</strong></p>
        </div>
        `;
        cartContainer.innerHTML += productHTML;
    });

    document.getElementById("total-price").innerText = totalPrice.toLocaleString();
    localStorage.setItem("cart", JSON.stringify(cart));
}

//  Cập nhật số lượng sản phẩm trong giỏ hàng
function updateQuantity(index, change) {
    if (cart[index].quantity + change > 0) {
        cart[index].quantity += change;
    } else {
        cart.splice(index, 1); // Xóa sản phẩm nếu số lượng = 0
    }
    renderCart();
}

// Xóa toàn bộ giỏ hàng
function cleanCart() {
    cart = [];
    localStorage.removeItem("cart");
    renderCart();
}
window.onload = renderCart;





