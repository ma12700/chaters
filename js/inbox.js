var ul, search, send, ULHTML = "",
    nameChat, email, chat,
    newMessage, newMessageText, messages, chatID = "",
    receiverID = "",
    seen = "";

function checkSession() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "back") {
                window.location.href = "index.html";
            }
        }
    };
    xmlhttp.open("POST", "php/inbox.php", false);
    xmlhttp.send();
}

function loadChats() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "") {
                ul.html("<center>No chats yet<br>search about users and start chatting</center>");
                ULHTML = "<center>No chats yet<br>search about users and start chatting</center>";
            } else {
                ULHTML = this.responseText;
                ul.html(ULHTML);
            }
        }
    };
    xmlhttp.open("POST", "php/loadChats.php", false);
    xmlhttp.send();
}

function searchUsers() {
    var xmlhttp = new XMLHttpRequest();
    var data = new FormData();
    data.append('name', search.val());
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "") {
                ul.html("<center>No matches found</center>");
            } else {
                ul.html(this.responseText);
            }
        }
    };
    xmlhttp.open("POST", "php/search.php", true);
    xmlhttp.send(data);
}

function openChat(id) {
    var xmlhttp = new XMLHttpRequest();
    var data = new FormData();
    data.append('id2', id);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText != "") {
                messages.html(this.responseText);
                chatID = messages.children("div")[0].id;
                seen = messages.children("div")[1].id;
                messages.children("div")[0].remove();
                messages.children("div")[0].remove();
                document.getElementById(id).lastElementChild.innerHTML = messages.children("div:nth-last-child(2)").children("span").text();
                document.getElementById(id).firstElementChild.nextElementSibling.className = "";
                ULHTML = ul.html();
            }
            chat.css("display", "block");
            newMessage.css("display", "flex");
            if(document.getElementById("scrollToHere")){
                messages.animate({scrollTop: $("#scrollToHere").offset().top});
            }
        }
    };
    xmlhttp.open("POST", "php/openChat.php", true);
    xmlhttp.send(data);
}

function select(v) {
    receiverID = v.id;
    chatID = "";
    seen = "";
    if (search.val() != "") {
        search.val("");
        ul.html(ULHTML);
        if (document.getElementById(receiverID)) {
            $("li").css("background-color", "white");
            $(v).css("background-color", "#c4ecf9");
            openChat(v.id);
            ULHTML = ul.html();
            document.getElementById(v.id).scrollIntoView();
        } else {
            messages.html("");
            chat.css("display", "block");
            newMessage.css("display", "flex");
        }
    } else {
        openChat(v.id);
        $("li").css("background-color", "white");
        $(v).css("background-color", "#c4ecf9");
        ULHTML = ul.html();
    }
    nameChat.html(v.firstElementChild.firstElementChild.textContent);
    email.html(v.firstElementChild.firstElementChild.nextElementSibling.textContent);
}

function realTimeChats() {
    setInterval(function () {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText != "[]") {
                    var chats = JSON.parse(this.responseText);
                    var html = "";
                    var seenFlag = "";
                    for (let index = 0; index < chats.length; index++) {
                        if (document.getElementById(chats[index].id)) {
                            document.getElementById(chats[index].id).remove();
                            ULHTML = ul.html();
                        }
                        seenFlag = chats[index].id == receiverID? "":"seen";
                        html += "<li id=" + chats[index].id + " onclick='select(this)'>" +
                            "<div> <p>" + chats[index].name + "</p>" +
                            "<span class='email'>" + chats[index].email + "</span>" +
                            "</div> <div class='"+seenFlag+"'></div>" +
                            "<span class='date'>" + chats[index].date + "</span></li>";
                    }
                    if (ULHTML.search("center") == 1) {
                        ULHTML = "";
                    }
                    ULHTML = html + ULHTML;
                    if(search.val() == ""){
                        ul.html(ULHTML);
                        if (receiverID != ""){
                            $("li").css("background-color", "white");
                            document.getElementById(receiverID).style["background-color"] = "#c4ecf9";
                            ULHTML = ul.html();
                        }
                        
                    }
                    if (receiverID != "" && chatID == "") {
                        openChat(receiverID);
                    }
                }
            }
        };
        xmlhttp.open("POST", "php/realTimeChats.php", true);
        xmlhttp.send();

    }, 3000);
}

function realTimeMessages() {
    setInterval(function () {
        if(receiverID != ""){
            var xmlhttp = new XMLHttpRequest();
            var data = new FormData();
            data.append('id2', receiverID);
            data.append('chatID', chatID);
            data.append('seen', seen);
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText != "") {
                        if(document.getElementById("scrollToHere")){
                            document.getElementById("scrollToHere").remove();
                        }
                        messages.append(this.responseText);
                        messages.animate({scrollTop: messages.prop('scrollHeight')});
                    }
                }
            };
            xmlhttp.open("POST", "php/realTimeMessages.php", true);
            xmlhttp.send(data);
        }
    }, 3000);
}
$(document).ready(function () {
    checkSession();
    ul = $("ul");
    search = $("#search");
    send = $("#send");
    nameChat = $("#name");
    email = $("#email");
    chat = $(".chat");
    newMessage = $("#newMessage");
    newMessageText = $("#newMessageText");
    messages = $("#messages");
    $("article").height(window.innerHeight - 116);
    ul.height(window.innerHeight - 190);
    search.val("");
    loadChats();

    realTimeChats();
    realTimeMessages();
    $("#logOut").on("click", function () {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                window.location.href = "index.html";
            }
        };
        xmlhttp.open("GET", "php/logOut.php", false);
        xmlhttp.send();
    });

    search.on("keyup click", function () {
        if ($(this).val() == "") {
            ul.html(ULHTML);
        } else {
            searchUsers();
        }
    });

    send.on("click", function () {
        if (newMessageText.val() != "") {
            var d = new Date(Date.now());
            var date = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
            var xmlhttp = new XMLHttpRequest();
            var data = new FormData();
            data.append('message', newMessageText.val());
            data.append('id2', receiverID);
            data.append('chatID', chatID);
            data.append('seen', seen);
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var messageHTML = '<div id="scrollToHere"></div><div class="message"><p>' + newMessageText.val() + '</p><span class="date">' +
                    date + '</span></div><div class="clear"></div>';
                    newMessageText.val("");
                    if (this.responseText == "done") {
                        document.getElementById(receiverID).lastElementChild.innerHTML = date;
                        if(document.getElementById("scrollToHere")){
                            document.getElementById("scrollToHere").remove();
                        }
                    }else {
                        var chat = JSON.parse(this.responseText);
                        chatID = chat.id;
                        seen = chat.seen;
                        if(chat.flag){
                            if(document.getElementById(receiverID)){
                                document.getElementById(receiverID).lastElementChild.innerHTML = date;
                            }
                            if(document.getElementById("scrollToHere")){
                                document.getElementById("scrollToHere").remove();
                            }
                        }else{
                            var html = "<li id='" + receiverID + "' onclick='select(this)'>" +
                                "<div> <p>" + nameChat.text() + "</p>" +
                                "<span class='email'>" + email.text() + "</span>" +
                                "</div> <div class=''></div>" +
                                "<span class='date'>" + date + "</span></li>";
                            if (ULHTML.search("center") == 1) {
                                ULHTML = "";
                            }
                            ULHTML = html + ULHTML;
                            if(search.val() == ""){
                                ul.html(ULHTML);
                            }
                            $("li").css("background-color", "white");
                            document.getElementById(receiverID).style["background-color"] = "#c4ecf9";
                            ULHTML = ul.html();
                            openChat(receiverID);
                        }
                    }
                    messages.append(messageHTML);
                    messages.animate({scrollTop: messages.prop('scrollHeight')});
                }
            };
            xmlhttp.open("POST", "php/sendMessage.php", true);
            xmlhttp.send(data);
        }
    });


})