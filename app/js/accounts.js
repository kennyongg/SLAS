var emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
//validate Login credentials
let login = document.getElementById("login_formBox");
let loginMessageBox = document.getElementById("login_messageBox");

if (login) {
    login.addEventListener('submit', function(event){
        event.preventDefault();
        let loginMessage = document.getElementById("login_message");
        if (loginMessage) {
            loginMessage.parentNode.removeChild(loginMessage);
        }
        let email = document.getElementById("login_email").value;
        let password = document.getElementById("login_pass").value;
    
        if (email === "" || password === "") {
            event.preventDefault();
            let message = "<p id='login_message' class='text-warning error_message'>Please fill in all of the required fields !<p>";
            loginMessageBox.innerHTML=message;
        } else {
            if (!emailRegex.test(email)) {
                event.preventDefault();
                let message = "<p id='login_message' class='text-warning error_message'>Incorrect Email Format entered !</span>";
                loginMessageBox.innerHTML=message;
            } else {
                // Check to see if the user exists through AJAX
                let param = "loginEmail=" + email + "&loginPass=" + password;
                let loginCheck = new XMLHttpRequest;
                loginCheck.open("POST","./php/login.php",true);
                loginCheck.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               
                loginCheck.onload = function(){
                    let res = JSON.parse(loginCheck.response);
                    if (res["error"] == "Account is deactivated") {
                        event.preventDefault();
                        let message = "<p id='login_message' class='text-warning error_message'>Account is deactivated ! Please contact admin</span>"
                        loginMessageBox.innerHTML=message;
                    } else if (res["error"]) {
                        event.preventDefault();
                        let message = "<p id='login_message' class='text-warning error_message'>Invalid Credentials, incorrect password or email !</span>";
                        loginMessageBox.innerHTML=message;
                    } else if (res["success"]) {
                        sessionStorage.setItem('userName', res["name"]["name"]);
                        sessionStorage.setItem('userEmail', res["id"])
                        sessionStorage.setItem('userAcc', res["acc_type"])
                        window.location.href = "app/landing.html";
                    }
                }
                //Alert if requests fail
                loginCheck.onerror = function(){
                    alert("Error conecting with server");
                };
                loginCheck.send(param)
            }
        } 
    })
}


// Recovery Stuff
let recoverPassForm = document.getElementById("recover_formBox");
let recoverMessageBox = document.getElementById("recover_messageBox");
let recoveryPassBlock = document.getElementById("recovery_PasswordBlock");
let recoverySuccess= document.getElementById('recovery_successBlock');

recoverPassForm.addEventListener('submit', function(){
    event.preventDefault();
    let recoverMessage = document.getElementById("recover_message");
    if (recoverMessage) {
        recoverMessage.parentNode.removeChild(recoverMessage);
    }
    let email = document.getElementById("recover_email").value;
    if (email == "") {
        event.preventDefault();
        let message = "<p id='recover_message' class='text-warning error_message'>Email Field is empty !</p>";
        recoverMessageBox.innerHTML = message;
    } else if (! emailRegex.test(email)) {
        event.preventDefault();
        let message = "<p id='recover_message' class='text-warning error_message'>Email entered is incorrect !</p>"
        recoverMessageBox.innerHTML = message;
    } else {
        // To check if the email exists
        let recover = new XMLHttpRequest;
        let param = "recoverPassword=" + email;
        recover.open("POST","../php/recoverPassword.php",true);
        recover.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        recover.onload = function(){
            //Respond modal
            let respond = JSON.parse(this.response);
            console.log(respond);
            if (respond["error"]) {
                event.preventDefault();
                let message = "Error ! "+ respond["error"];
                recoverMessageBox.innerHTML = message;
            } else if (respond["success"]) {
                let message = "<p id='recovery_successMessage' class='text-center'><br> Please check your inbox for our email.</p>";
                recoverySuccess.innerHTML += message;
                recoverySuccess.style.display = "block";
                recoveryPassBlock.style.display = "none";                   
            }
        };
        recover.send(param);
    }
})

let currentUrl = new URL(window.location.href);
let recoverID = currentUrl.searchParams.get("id");
let newPasswordBlock = document.getElementById("newPasswordBlock");
if (recoverID && (recoverID.length == 30)) {
    let auth = new XMLHttpRequest();
    auth.open("GET","../php/recoverPassword.php?recoveryId="+recoverID,true);
    auth.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    auth.onload = function(){
        let res = JSON.parse(this.response);
        if (res["success"]) {
            newPasswordBlock.style.display = "block";
            recoveryPassBlock.style.display = "none";
            sessionStorage.setItem("recoverEmail",res["success"]);
            sessionStorage.setItem("recoverID",res["recoveryId"]);
        } else if (res["error"]) {
            let messsage = "<p id='expired_errorMessage' class='text-warning error_message'>The link provided has expired, please make another password recovery request</p>";
            document.getElementById("recovery_ExpiredMessageBlock").innerHTML = messsage;
            document.getElementById("recovery_ExpiredBlock").style.display = "block";
            recoveryPassBlock.style.display= "none";
        }
    };
    auth.send()  
}

let passwordForm = document.getElementById("newPassword_formBox");
passwordForm.addEventListener("submit", function(){
    event.preventDefault();
    let password1 = document.getElementById("recover_pass").value
    let password2 = document.getElementById("recover_pass2").value

    if (password1 !== password2) {
        let message = "Password entered does not match";
        document.getElementById("newPassword_messageBox").innerHTML = message;
        document.getElementById("recover_pass").classList.add("errorInput");
        document.getElementById("recover_pass2").classList.add("errorInput");
    } else {
        let changePass = new XMLHttpRequest();
        let param = "recoveryId="+sessionStorage.getItem("recoverID")+"&email="+sessionStorage.getItem("recoverEmail")+"&newPassword="+password2;
        changePass.open("POST","../php/recoverPassword.php",true);
        changePass.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        changePass.onload = function() {
            let res = JSON.parse(this.response);
            if (res["success"]) {
                let message = "<p class='text-success success_message'>Success, you can login with the new Password now !</p>";
                document.getElementById("newPassword_messageBox").innerHTML=message;
                setTimeout(function(){
                    window.location.href="../index.html"
                }, 6000);
            } else if (res["error"]) {
                let message = "<p id='' class='text-warning error_message'>Error, password change unsuccesfull</p>"
                document.getElementById("newPassword_messageBox").innerHTML=message;
            }
        };
        changePass.send(param);
    }
});
