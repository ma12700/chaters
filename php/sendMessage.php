<?php
    session_start();
    include 'connectDB.php';
    include 'test.php';
    $hint = "";
    $_POST['id2'] = (int) base64_decode($_POST['id2']);
    $_POST['message'] = test_input($_POST['message']);
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $ciphertext = sodium_crypto_secretbox($_POST['message'], $nonce, $_SESSION['key']);
    $_POST['message'] = base64_encode($nonce . $ciphertext);
    $timestamp = date("Y-m-d H:i:s");
    $flag = 0;

    if($_POST['chatID'] != "" && $_POST['seen'] != ""){
        $_POST['chatID'] = (int) base64_decode($_POST['chatID']);
        $_POST['seen'] = base64_decode($_POST['seen']);
    }else{
        $stmt = $conn->prepare("SELECT * FROM `chats` WHERE (`user1` = ? AND `user2` = ?) OR (`user1` = ? AND `user2` = ?) LIMIT 1;");
        $stmt->bind_param("iiii", $_SESSION['myId'], $_POST['id2'], $_POST['id2'], $_SESSION['myId']);
        $stmt->execute();
        $result = $stmt->get_result();
        $chat = $result->fetch_assoc();
        if(!$chat){
            $flag = 1;
            $stmt = $conn->prepare("INSERT INTO `chats`(`user1`, `user2`) VALUES (?,?);");
            $stmt->bind_param("ii", $_SESSION['myId'], $_POST['id2']);
            $stmt->execute();

            $_POST['chatID'] = $conn->insert_id;
            $_POST['seen'] = "seen2";
        }else{
            $flag = 2;
            $_POST['chatID'] = $chat['chat_id'];
            $_POST['seen'] = ($chat['user1'] == $_SESSION['myId'])? "seen2" : "seen1";
        }
    }

    $stmt = $conn->prepare("INSERT INTO `messages`(`chat_id`, `sender_id`, `message`) VALUES (?,?,?);");
    $stmt->bind_param("iis", $_POST['chatID'], $_SESSION['myId'], $_POST['message']);
    $stmt->execute(); 

    $stmt = $conn->prepare("UPDATE `chats` SET ".$_POST['seen']." = 1 ,`last_message`= ? , `loaded` = 0 WHERE `chat_id` = ?;");
    $stmt->bind_param("si", $timestamp,$_POST['chatID']);
    $stmt->execute();

    $hint = "done";
    if($flag == 1){
        $hint = '{"id":"'.base64_encode($_POST['chatID']).'","seen":"'.base64_encode($_POST['seen']).'"}';
    }else if($flag == 2){
        $hint = '{"id":"'.base64_encode($_POST['chatID']).'","seen":"'.base64_encode($_POST['seen']).'","flag":"Y"}';
    }  

    $stmt->close();
    $conn->close();
    echo $hint;
?>