const tname = document.querySelector('.email');
const tpassword = document.querySelector('.Password ');

function singup() {
    if(
        tname.value ==="" ||
        tpassword.value ==="")
    {
        alert("please enter username or password!");
    }
    else{
        const user ={
            username:tname.value,
            password:tpassword.value,
        };
        let json =JSON.stringify(user);

        localStorage.setItem(tname.value,json);

        alert("dang ky thanh cong");
        window.open("login.html");
    }
}