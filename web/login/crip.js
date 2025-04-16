// function check(event){
//     event.preventDefault();
//     var eamil = document.getElementById("email").value;

//     var password = document.getElementById("pwd").value;

//     if(eamil === "vitrongvu02082005@gmail.com" && password === "123"){
//         alert("login successfull");
//         window.location.href = "../index.html";
//     }else{
//         alert("Invaalid email or password.");
//     }
// }



const tname = document.querySelector("#email");
const tpassword = document.querySelector("#pwd");

function check(){
    if(tname.value === "" || tpassword.value ===""){
        alert("please enter name password!");
    }
    // chuyển từ file json 
    else{
        const user = JSON.parse(localStorage.getItem(tname.value));
        if(
            user.username === tname.value &&
            user.password === tpassword.value
        )
        {
            //alert("done");
            window.open("../index.html"); 
            
        }
        else{
            alert("error");
        }
    }
}














