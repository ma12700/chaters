<?php
    session_start();
    include 'connectDB.php';
    $hint = "";
    $stmt = $conn->prepare("SELECT * FROM `chats` WHERE `user1` = ? OR `user2` = ? ORDER BY `last_message` DESC;");
    $stmt->bind_param("ii", $_SESSION['myId'], $_SESSION['myId']);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    for ($i=0; $i < $count; $i++) { 
        $chat = $result->fetch_assoc();
        if($chat['user1'] == $_SESSION['myId']){
            $id = (int)$chat['user2'];
            $seen = $chat['seen1'] == 0? "" : "seen";
        }else{
            $id = (int)$chat['user1'];
            $seen = $chat['seen2'] == 0? "" : "seen";
        }
        if($chat['loaded'] == 0){
            $stmt = $conn->prepare("UPDATE `chats` SET `loaded`= 1 WHERE `chat_id` = ?;");
            $stmt->bind_param("i", $chat['chat_id']);
            $stmt->execute();
        }
        $stmt = $conn->prepare("SELECT `name`,`email` FROM `users` WHERE `user_id` = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result2 = $stmt->get_result();
        $user = $result2->fetch_assoc();
        $id = base64_encode($id);
        $hint .= "<li id=" .$id. " onclick='select(this)'>" .
            "<div> <p>" .$user['name']. "</p>" .
            "<span class='email'>" .$user['email']. "</span>" .
            "</div> <div class='" .$seen. "'></div>" .
            "<span class='date'>" .$chat["last_message"]. "</span></li>";
        
    }

    $stmt->close();
    $conn->close();
    echo $hint;
?>