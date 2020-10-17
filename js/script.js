var login, register, loginButton, registerButton,
    loginEmail, loginPassword, registerName, registerEmail,
    registerPassword, registerConfirmPassword,
    question, q_answer;

const regexPass = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&]).*$/;
const regexEmail = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;

function checkSession(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText == "go"){
                window.location.href="inbox.html";
            }
        }
    };
    xmlhttp.open("POST", "php/index.php", false);
    xmlhttp.send();
}

function checkRegister() {
    if (registerName.val() != "" && regexEmail.test(registerEmail.val()) &&
        regexPass.test(registerPassword.val()) && registerPassword.val() == registerConfirmPassword.val() &&
        q_answer.val() != "") {
        registerButton.attr("disabled", false);
        registerButton.css("cursor", "pointer");
    } else {
        registerButton.attr("disabled", true);
        registerButton.css("cursor", "not-allowed");
    }
}

function checkLogin() {
    if (regexEmail.test(loginEmail.val()) && regexPass.test(loginPassword.val())) {
        loginButton.attr("disabled", false);
        loginButton.css("cursor", "pointer");
    } else {
        loginButton.attr("disabled", true);
        loginButton.css("cursor", "not-allowed");
    }
}

function loadQuestions() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            $("select").html(this.responseText);
        }
    };
    xmlhttp.open("GET", "php/loadQ.php", true);
    xmlhttp.send();
}

$(document).ready(function () {
    checkSession();
    login = $("#login");
    register = $("#register");
    loginButton = $("#loginButton");
    registerButton = $("#registerButton");
    loginEmail = $("#loginEmail");
    loginPassword = $("#loginPassword");
    registerName = $("#registerName");
    registerEmail = $("#registerEmail");
    registerPassword = $("#registerPassword");
    registerConfirmPassword = $("#registerConfirmPassword");
    question = $("#question");
    q_answer = $("#q_answer");
    checkLogin();
    checkRegister();
    loadQuestions();

    loginEmail.on("keyup change click", function () {
        checkLogin();
    });
    loginPassword.on("keyup  change click", function () {
        checkLogin();
    });
    registerName.on("keyup  change click", function () {
        checkRegister();
    });
    registerEmail.on("keyup  change click", function () {
        checkRegister();
    });
    registerPassword.on("keyup  change click", function () {
        checkRegister();
    });
    registerConfirmPassword.on("keyup  change click", function () {
        checkRegister();
    });
    q_answer.on("keyup  change click", function () {
        checkRegister();
    });
    $("#goRegister").on("click", function () {
        login.css("display", "none");
        register.css("display", "block");
    });
    $("#goLogin").on("click", function () {
        register.css("display", "none");
        login.css("display", "block");
    });

    loginButton.on("click", function (event) {
        event.preventDefault();
        var xmlhttp = new XMLHttpRequest();
        var data = new FormData();
        data.append('password', loginPassword.val());
        data.append('email', loginEmail.val());
        loginButton.css("cursor", "wait");
        loginButton.attr("disabled", true);
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if(this.responseText == "done"){
                    window.location.href="inbox.html";
                }else{
                    $("#loginError").css("visibility", "visible");
                    $("#loginError").html(this.responseText);
                    loginButton.css("cursor", "pointer");
                    loginButton.attr("disabled", false);
                }
                
            }
        };
        xmlhttp.open("POST", "php/login.php", false);
        xmlhttp.send(data);
    });
    registerButton.on("click", function (event) {
        event.preventDefault();
        registerButton.css("cursor", "wait");
        registerButton.attr("disabled", true);
        var xmlhttp = new XMLHttpRequest();
        var data = new FormData();
        data.append('name', registerName.val());
        data.append('password', registerPassword.val());
        data.append('email', registerEmail.val());
        data.append('q_id', question.val());
        data.append('q_answer', q_answer.val());
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText.search("Succeed") > 0) {
                    $("#registerFeedback").css("background-color", "#90f290");
                    registerName.val("");
                    registerEmail.val("");
                    registerPassword.val("");
                    registerConfirmPassword.val("");
                    q_answer.val("");
                } else {
                    $("#registerFeedback").css("background-color", "#ff0000c2");
                }
                $("#registerFeedback").css("visibility", "visible");
                $("#registerFeedback").html(this.responseText);
                registerButton.css("cursor", "pointer");
                registerButton.attr("disabled", false);
            }
        };
        xmlhttp.open("POST", "php/register.php", false);
        xmlhttp.send(data);
    });
})