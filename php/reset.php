<?php 
    include 'test.php';
    $hint = "";
    $_POST['email'] = test_input($_POST['email']);
    $_POST['password'] = test_input($_POST['password']);
    $_POST['q_answer'] = test_input($_POST['q_answer']);

    function check(){
        return preg_match("/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$_POST['email'])
        && preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&]).*$/",$_POST['password'])
        && $_POST['q_answer'] != "";
    }
    if(check()){

        include 'connectDB.php';
        $stmt = $conn->prepare("SELECT `Q_answer` FROM `users` WHERE `email` = ?;");
        $stmt->bind_param("s", $_POST['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row){
            if(password_verify(strtolower($_POST['q_answer']),$row['Q_answer'])){
                $hash = password_hash($_POST['password'],PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE `users` SET `password`= ? WHERE `email` = ?");
                $stmt->bind_param("ss", $hash, $_POST['email']);
                $stmt->execute();
                $hint = "Resetting Succeed, try login now";
            }else{
                sleep(5);
                $hint = "Wrong Answer";
            }
        }else{
            $hint = 'That email is Wrong';
        }
        $stmt->close();
        $conn->close();
    }else {
        $hint = "Resetting Failed: Please check Your inputs";
    }
    echo $hint;
?>