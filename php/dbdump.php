<?php
    include 'connectDB.php';
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE 1;");
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    $hint = "<table><caption>Users</caption><tr><th>user_id</th><th>name</th><th>email</th><th>password</th><th>Q_id</th><th>Q_answer</th></tr>";
    for ($i=0; $i < $count; $i++) { 
        $row = $result->fetch_assoc();
        $hint .= "<tr><td>".$row['user_id']."</td><td>".$row['name']."</td><td>".$row['email'].
        "</td><td>".$row['password']."</td><td>".$row['Q_id']."</td><td>".$row['Q_answer']."</td></tr>";
    }
    $hint .= "</table>";

    $stmt = $conn->prepare("SELECT * FROM `questions` WHERE 1;");
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    $hint .= "<table><caption>Questions</caption><tr><th>Q_id</th><th>question</th></tr>";
    for ($i=0; $i < $count; $i++) { 
        $row = $result->fetch_assoc();
        $hint .= "<tr><td>".$row['Q_id']."</td><td>".$row['question']."</td></tr>";
    }
    $hint .= "</table>";

    $stmt = $conn->prepare("SELECT * FROM `chats` WHERE 1;");
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    $hint .= "<table><caption>Chats</caption><tr><th>chat_id</th><th>user1</th><th>user2</th><th>seen1</th><th>seen2</th><th>last_message</th><th>loaded</th></tr>";
    for ($i=0; $i < $count; $i++) { 
        $row = $result->fetch_assoc();
        $hint .= "<tr><td>".$row['chat_id']."</td><td>".$row['user1']."</td><td>".$row['user2'].
        "</td><td>".$row['seen1']."</td><td>".$row['seen2']."</td><td>".$row['last_message'].
        "</td><td>".$row['loaded']."</td></tr>";
    }
    $hint .= "</table>";

    $stmt = $conn->prepare("SELECT * FROM `messages` WHERE 1;");
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    $hint .= "<table><caption>Messages</caption><tr><th>id</th><th>chat_id</th><th>sender_id</th><th>message</th><th>time</th><th>seen</th></tr>";
    for ($i=0; $i < $count; $i++) { 
        $row = $result->fetch_assoc();
        $hint .= "<tr><td>".$row['id']."</td><td>".$row['chat_id']."</td><td>".$row['sender_id'].
        "</td><td>".$row['message']."</td><td>".$row['time']."</td><td>".$row['seen'].
        "</td></tr>";
    }
    $hint .= "</table>";

    $stmt->close();
    $conn->close();
    echo $hint;
?>