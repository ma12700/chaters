<?php 
    include 'test.php';
    $hint = "";
    $_POST['email'] = test_input($_POST['email']);

    function check(){
        return preg_match("/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$_POST['email']);
    }
    if(check()){

            include 'connectDB.php';
            $stmt = $conn->prepare("SELECT `question` FROM `questions` WHERE `Q_id` = (SELECT `Q_id` from `users` where `email` = ?); ");
            $stmt->bind_param("s", $_POST['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if($row){
                $hint = $row['question'];
            }else{
                sleep(5);
                $hint = "Wrong Email";
            }
            
            $stmt->close();
            $conn->close();
    }else {
        sleep(7);
        $hint = "Send Failed: Please check if Your Email is valid";
    }
	echo $hint;
?>