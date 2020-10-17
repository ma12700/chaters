<?php 
    include 'test.php';
    $hint = "";
    $_POST['email'] = test_input($_POST['email']);
    $_POST['name'] = test_input($_POST['name']);
    $_POST['password'] = test_input($_POST['password']);
    $_POST['q_answer'] = test_input($_POST['q_answer']);
    $_POST['q_id'] = test_input($_POST['q_id']);
    function check(){
        return preg_match("/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$_POST['email'])
        && preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&]).*$/",$_POST['password'])
        && $_POST['name'] != "" && $_POST['q_answer'] != "" 
        && (int)$_POST['q_id'] > 0 && (int)$_POST['q_id'] < 5;
    }
    if(check()){

            include 'connectDB.php';
            $stmt = $conn->prepare("SELECT `user_id` FROM `users` WHERE `email` = ?;");
            $stmt->bind_param("s", $_POST['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row){
                $hint = 'There is user with that email you can`t register with it';
            }else{
                $hash = password_hash($_POST['password'],PASSWORD_DEFAULT);
                $hashAnswer = password_hash(strtolower($_POST['q_answer']),PASSWORD_DEFAULT);
                $_POST['q_id'] = (int)$_POST['q_id'];
                $stmt = $conn->prepare("INSERT INTO `users`(`name`, `email`, `password`, `Q_answer`, `Q_id`) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssi", $_POST['name'], $_POST['email'],$hash,$hashAnswer,$_POST['q_id']);
                $stmt->execute();
                if ($conn->connect_error) {
                    $hint = "No Connection with database now try at another time";
                }else{
                    $hint = "Registeration Succeed, try login now";
                }
            }
            $stmt->close();
            $conn->close();
    }else {
        $hint = "Registeration Failed: Please check Your inputs";
    }
	echo $hint;
?>