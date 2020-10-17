<?php
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 1);
    session_start();
    session_regenerate_id();
	if(!isset($_SESSION['myId'])){
        session_destroy();
		echo "back";
    }
?>