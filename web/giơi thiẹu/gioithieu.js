
// style  user-menu"
function toggleUserMenu() {
    document.querySelector(".user").classList.toggle("active");
  }
  
  window.onclick = function (event) {
    if (!event.target.closest(".user-menu")) {
        document.querySelector(".user").classList.remove("active");
    }
  }
  