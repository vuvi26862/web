let slideIndex = 0;
function showSlides() {
    let slides = document.getElementsByClassName('mySlides');
    let dots = document.getElementsByClassName('dot');
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = 'none';  
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}    
    for (let i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(' active', '');
    }
    slides[slideIndex-1].style.display = 'block';  
    dots[slideIndex-1].className += ' active';
    setTimeout(showSlides, 7000); 
}
showSlides();
function plusSlides(n) {
    slideIndex += n - 1;
    showSlides();
}

function currentSlide(n) {
    slideIndex = n - 1;
    showSlides();
}

//cart

let cart = JSON.parse(localStorage.getItem('cart')) || [];

let cart_stg = JSON.parse(localStorage.getItem('cart')) || [];

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart_stg));
}

//  Hàm thêm sản phẩm vào giỏ hàng
function addToCart(event) {
    event.preventDefault(); // Ngăn trang bị reload

    let product = this.closest('.round-box3,.round-box4,.round-box2,.round-box5,.round-box6,.round-box7');
    let productName = product.querySelector('.product-name').innerText;
    let productPrice = parseInt(product.querySelector('.price').innerText.replace(/\D/g, '')); // Lấy số từ giá
    let productImg = product.querySelector('img').src;

    let existingProduct = cart_stg.find(item => item.name === productName);

    if (existingProduct) {
        existingProduct.quantity++; // Nếu đã có sản phẩm, tăng số lượng
    } else {
        cart_stg.push({
            img: productImg,
            name: productName,
            price: productPrice,
            quantity: 1
        });
    }

    saveCart(); // Lưu vào localStorage
    alert('Đã thêm vào giỏ hàng');

    // Cập nhật giao diện giỏ hàng ngay sau khi thêm
    renderCart();
}


let buttons = document.querySelectorAll('.cart a');
buttons.forEach(button => {
    button.addEventListener('click', addToCart);
});


function toggleUserMenu() {
    document.querySelector(".user").classList.toggle("active");
  }
  
  window.onclick = function (event) {
    if (!event.target.closest(".user-menu")) {
        document.querySelector(".user").classList.remove("active");
    }
  }
  




// // search
// document.getElementById("searchInput").addEventListener("input", function() {
//   let searchValue = this.value.toLowerCase();
//   let products = document.querySelectorAll(".product");

//   products.forEach(product => {
//       let name = product.getAttribute("data-name").toLowerCase();
//       let description = product.getAttribute("data-description").toLowerCase();

//       if (name.includes(searchValue) || description.includes(searchValue)) {
//           product.classList.remove("hidden");
//       } else {
//           product.classList.add("hidden");
//       }
//   });
// });