<?php
    include 'connectDB.php';
    session_start();
    $hint = "[";
    $stmt = $conn->prepare("SELECT * FROM `chats` WHERE ((`user1` = ? AND `seen1` = 1) OR (`user2` = ? AND `seen2` = 1)) AND `loaded` = 0 ORDER BY `last_message` DESC;");
    $stmt->bind_param("ii", $_SESSION['myId'], $_SESSION['myId']);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    for ($i=0; $i < $count; $i++) { 
        $chat = $result->fetch_assoc();
        $id = ($chat['user1'] == $_SESSION['myId'])? (int)$chat['user2']:(int)$chat['user1'];
        $stmt = $conn->prepare("SELECT `name`,`email` FROM `users` WHERE `user_id` = ?;");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result2 = $stmt->get_result();
        $user = $result2->fetch_assoc();
        $hint .='{"id":"'.base64_encode($id).'","name":"'.$user['name'].'","email":"'.$user['email'].'","date":"'.$chat["last_message"].'"}';
        if($i < $count - 1 ){
            $hint .= ",";
        }
        $stmt = $conn->prepare("UPDATE `chats` SET `loaded` = 1 WHERE `chat_id` = ?;");
        $stmt->bind_param("i", $chat['chat_id']);
        $stmt->execute();
    }
    $hint .= "]";
    $stmt->close();
    $conn->close();
    echo $hint;
?>