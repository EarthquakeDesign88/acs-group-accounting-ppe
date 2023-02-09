<?php
	include 'config/config.php'; 
	session_destroy();
	
	__log_logging("logout",__session_user("id"));
	
	header("Location: login.php ");	
?>