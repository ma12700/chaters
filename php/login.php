<?php 
    include 'test.php';
    $hint = "";
    $_POST['email'] = test_input($_POST['email']);
    $_POST['password'] = test_input($_POST['password']);
    
    if( preg_match("/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$_POST['email'])
        && preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&]).*$/",$_POST['password'])){
            include 'connectDB.php';
            $stmt = $conn->prepare("SELECT `user_id`,`name`,`password` FROM `users` WHERE `email` = ?;");
            $stmt->bind_param("s", $_POST['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if($row){
                $hash = $row['password'];
                if(password_verify($_POST['password'],$hash)){
                    $hint = 'done';
                    session_start();
                    session_regenerate_id();
                    $myfile = fopen("fsgsyh@#15b&!c.txt", "r") or die("Unable to open file!");
                    $_SESSION['key'] = fread($myfile,filesize("fsgsyh@#15b&!c.txt"));
                    $_SESSION['myId'] = $row['user_id'];
                    fclose($myfile);
                }else{
                    sleep(5);
                    $hint = "Wrong Password";
                }
            }else{
                $hint = "Wrong Email";
            }
            
            $stmt->close();
            $conn->close();
    }else {
        $hint = "Login Failed: Please check Your inputs";
    }
	echo $hint;
?>