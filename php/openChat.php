<?php
    session_start();
    include 'connectDB.php';
    $_POST['id2'] = (int) base64_decode($_POST['id2']);
    $hint = "";
    $stmt = $conn->prepare("SELECT * FROM `chats` WHERE (`user1` = ? AND `user2` = ?) OR (`user1` = ? AND `user2` = ?) LIMIT 1;");
    $stmt->bind_param("iiii", $_SESSION['myId'], $_POST['id2'], $_POST['id2'], $_SESSION['myId']);
    $stmt->execute();
    $result = $stmt->get_result();
    $chat = $result->fetch_assoc();
    if($chat){
        $user2 = ($chat['user1'] != $_SESSION['myId'])? 'seen1' : 'seen2';
        $user = ($chat['user1'] == $_SESSION['myId'])? 'seen1' : 'seen2';
        if($chat[$user] > 0){
            $stmt = $conn->prepare("UPDATE `chats` SET ".$user." = 0 WHERE `chat_id` = ?;");
            $stmt->bind_param("i", $chat['chat_id']);
            $stmt->execute();
        }
        $hint .= "<div id='".base64_encode($chat['chat_id'])."'></div>";
        $hint .= "<div id='".base64_encode($user2)."'></div>";
        $stmt = $conn->prepare("SELECT `id`,`sender_id`,`message`,`time`,`seen` FROM `messages` WHERE `chat_id` = ? ORDER BY `id` ASC;");
        $stmt->bind_param("i", $chat['chat_id']);
        $stmt->execute();
        $result2 = $stmt->get_result();
        $flag = 0;
        $count = $result2->num_rows;
        for ($i=0; $i < $count; $i++) {
            $row = $result2->fetch_assoc();
            $decoded = base64_decode($row['message']);
            $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
            $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
            $plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $_SESSION['key']);
            $class = $row['sender_id'] == $_SESSION['myId']? "message":"message2";
            if($flag == 0 && $row['seen'] == 0){
                $hint .= '<div id="scrollToHere"></div>';
                $flag = 1;
            }
            if($row['seen'] == 0){
                $stmt = $conn->prepare("UPDATE `messages` SET `seen`= 1 WHERE `id` = ?;");
                $stmt->bind_param("i", $row['id']);
                $stmt->execute();
            }
            if($flag == 0 && $i == $count - 1){
                $hint .= '<div id="scrollToHere"></div>';
            }
            $hint .= '<div class="'.$class.'"><p>'. $plaintext . '</p><span class="date">'.
                    $row['time'].'</span></div><div class="clear"></div>';
            
        }
        
    }

    $stmt->close();
    $conn->close();
    echo $hint;
?>