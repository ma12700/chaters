var resetButton,resetEmail,resetAnswer,
    resetPassword,resetConfirmPassword;

const regexPass = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&]).*$/;
const regexEmail = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;

function checkReset() {
    if ((resetButton.text() == "Send" && regexEmail.test(resetEmail.val()) ) || (regexEmail.test(resetEmail.val()) && regexPass.test(resetPassword.val()) && 
        resetPassword.val() == resetConfirmPassword.val() && resetAnswer.val() != "")) {

        resetButton.attr("disabled", false);
        resetButton.css("cursor", "pointer");
    } else {
        resetButton.attr("disabled", true);
        resetButton.css("cursor", "not-allowed");
    }
}

$(document).ready(function () {
    resetButton = $("#resetButton");
    resetEmail = $("#resetEmail");
    resetAnswer = $("#resetAnswer");
    resetPassword = $("#resetPassword");
    resetConfirmPassword = $("#resetConfirmPassword");
    checkReset();

    resetEmail.on("keyup change click", function () {
        checkReset();
    });
    resetAnswer.on("keyup  change click", function () {
        checkReset();
    });
    resetPassword.on("keyup  change click", function () {
        checkReset();
    });
    resetConfirmPassword.on("keyup  change click", function () {
        checkReset();
    });
    
    resetButton.on("click", function (event) {
        event.preventDefault();
        resetButton.css("cursor", "wait");
        resetButton.attr("disabled", true);
        $("#resetFeedback").css("visibility", "hidden");
        var xmlhttp = new XMLHttpRequest();
        var data = new FormData();
        var url = "php/getQ.php";
        data.append('email', resetEmail.val());
        if(resetButton.text() != "Send"){
            data.append('password', resetPassword.val());
            data.append('q_answer', resetAnswer.val());
            url = "php/reset.php";
        }

        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (resetButton.text() == "Send"){
                    if(this.responseText.search("Email") > 0) {
                        $("#resetFeedback").css("background-color", "#ff0000c2");
                        $("#resetFeedback").css("visibility", "visible");
                        $("#resetFeedback").html(this.responseText);
                    }else{
                        resetButton.text("Reset Password");
                        $(".resetQusetion").text(this.responseText);
                        $("#resetFeedback").css("visibility", "hidden");
                        $("#more").css("display","block");
                        resetEmail.attr("disabled", true);
                    }
                } else {
                    if (this.responseText.search("Succeed") > 0) {
                        $("#resetFeedback").css("background-color", "#90f290");
                        resetPassword.val("");
                        resetAnswer.val("");
                        resetConfirmPassword.val("");
                    } else {
                        $("#resetFeedback").css("background-color", "#ff0000c2");
                    }
                    $("#resetFeedback").css("visibility", "visible");
                    $("#resetFeedback").html(this.responseText);
                    resetButton.css("cursor", "pointer");
                    resetButton.attr("disabled", false);
                }
                checkReset();
            }
        };
        xmlhttp.open("POST", url, false);
        xmlhttp.send(data);
    });
})