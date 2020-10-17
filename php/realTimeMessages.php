<?php
    include 'connectDB.php';
    session_start();
    $hint = "";

    if($_POST['chatID'] == "" || $_POST['seen'] == ""){
        $stmt = $conn->prepare("SELECT * FROM `chats` WHERE ((`user1` = ? AND `user2` = ? AND `seen1` > 0) OR (`user1` = ? AND `user2` = ? AND `seen2` > 0)) LIMIT 1;");
        $stmt->bind_param("iiii", $_SESSION['myId'], $_POST['id2'], $_POST['id2'], $_SESSION['myId']);
        $stmt->execute();
        $result = $stmt->get_result();
        $chat = $result->fetch_assoc();
        if($chat){
            $_POST['chatID'] = $chat['chat_id'];
            $_POST['id2'] = base64_decode($_POST['id2']);
            $_POST['seen'] = ($chat['user1'] != $_SESSION['myId'])? 'seen1' : 'seen2';
        }
    }else{
        $_POST['chatID'] = base64_decode($_POST['chatID']);
        $_POST['id2'] = base64_decode($_POST['id2']);
        $_POST['seen'] = base64_decode($_POST['seen']);
        $_POST['seen'] = $_POST['seen'] == "seen1"? "seen2":"seen1";
    }

    $stmt = $conn->prepare("SELECT `id`,`message`,`time` FROM `messages` WHERE `chat_id` = ? AND `seen` = 0 AND `sender_id` = ? ORDER BY `id` ASC;");
    $stmt->bind_param("ii", $_POST['chatID'],$_POST['id2']);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    if($count > 0){
        $stmt = $conn->prepare("UPDATE `chats` SET ".$_POST['seen']." = 0 WHERE `chat_id` = ?;");
        $stmt->bind_param("i", $_POST['chatID']);
        $stmt->execute();
        $hint .= '<div id="scrollToHere"></div>';
    }
    for ($i=0; $i < $count; $i++) {
        $row = $result->fetch_assoc();
        $decoded = base64_decode($row['message']);
        $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
        $plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $_SESSION['key']);

        $stmt = $conn->prepare("UPDATE `messages` SET `seen`= 1 WHERE `id` = ?;");
        $stmt->bind_param("i", $row['id']);
        $stmt->execute();
        
        $hint .= '<div class="message2"><p>'. $plaintext . '</p><span class="date">'.
                $row['time'].'</span></div><div class="clear"></div>';
        
    }
    $stmt->close();
    $conn->close();
    echo $hint;
?>