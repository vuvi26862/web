

let cart = JSON.parse(localStorage.getItem("cart")) || []; //đọc giỏ hàng localStorage ,đối tượn qua ,chuỗi qua mảng 
//  Hàm hiển thị giỏ hàng
function renderCart() {
    let cartContainer = document.getElementById("cart-item"); // Lấy HTML vào  giỏ hàng
    let totalPrice = 0; //  0
    cartContainer.innerHTML = ""; //XÓA nd cũ trong giỏ hàng  

    if (cart.length === 0) {
        cartContainer.innerHTML = "<p>Giỏ hàng trống</p>"; //kc sp trong gh 
        document.getElementById("total-price").innerText = "0";
        return; 
    }

    cart.forEach((item, index) => {
        let itemTotal = item.price * item.quantity; // duyệt từng sản phẩm trong giỏ hàng
        totalPrice += itemTotal;
        
        let productHTML = ` 
        <div class="cart-item"> //tính tt sp
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

    document.getElementById("total-price").innerText = totalPrice.toLocaleString(); // Cập nhật gtri 
    localStorage.setItem("cart", JSON.stringify(cart));
}

//  kb hàm , + -
function updateQuantity(index, change) { //tso hàm   
    if (cart[index].quantity + change > 0) {  //mang arr ds  inde vtrí  mả
        cart[index].quantity += change; // + - sl sp
    } else {
        cart.splice(index, 1); // x sản phẩm  số lượng 0
    }
    renderCart();
}

// Xóa toàn bộ giỏ hàng
function cleanCart() {
    cart = [];
    localStorage.removeItem("cart"); // XÓA  TRONG LOCAL STORAGE
    renderCart();
}
window.onload = renderCart;


//sp  dùng để thêm, xoá hoặc thay thế phần tử ngay tại vị trí cụ


