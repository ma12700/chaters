<?php
    session_start();
    include 'test.php';
    include 'connectDB.php';
    $hint = "";
    $_POST['name'] = test_input($_POST['name']);  
    $stmt = $conn->prepare("SELECT `user_id`, `name`, `email` FROM `users` WHERE `name` LIKE ? AND `user_id` != ? ;");
    $name = "%".$_POST['name']."%";
    $stmt->bind_param("si", $name , $_SESSION['myId']);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    for ($i=0; $i < $count; $i++) {
        $user = $result->fetch_assoc();
        $id = base64_encode($user['user_id']);
        $hint .= "<li id='" .$id. "' onclick='select(this)'>" .
            "<div> <p>" .$user['name']. "</p>" .
            "<span class='email'>" .$user['email']. "</span>" .
            "</div> <div class=''></div>" .
            "<span class='date'></span></li>";
    }
    echo $hint;
?>