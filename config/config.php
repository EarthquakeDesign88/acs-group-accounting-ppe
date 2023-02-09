<?php
    date_default_timezone_set("Asia/Bangkok");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $path = realpath(dirname(__FILE__). '/../');
	$path_logs = $path."/logs/logs_error/";
	
	 if($_SERVER["SERVER_NAME"] == 'localhost' || $_SERVER["SERVER_NAME"] == '127.0.0.1'){
       error_reporting(-1);
        $ENVIRONMENT_KEY = "development";
    }else{
        if(!empty($test)){
           error_reporting(-1);
        }else{
            error_reporting(0);
        }
       
       $ENVIRONMENT_KEY = "production";
    }
    
    error_reporting(-1);
	
	@mkdir($path_logs);
	ini_set("display_errors", "1"); 
	ini_set("log_errors", 1);
	ini_set("error_log", $path_logs."/logs_error_".date('Y-m-d').".txt");

	ini_set('memory_limit', '-1');
	
    if($_SERVER["SERVER_NAME"] == 'localhost' || $_SERVER["SERVER_NAME"] == '127.0.0.1'){
       error_reporting(-1);
        $ENVIRONMENT_KEY = "development";
    }else{
       error_reporting(0);
       $ENVIRONMENT_KEY = "production";
    }
   	
     if (!defined('ENVIRONMENT_KEY')) define('ENVIRONMENT_KEY', $ENVIRONMENT_KEY); 
    
	 include 'database2.php';
	 include 'function.php';
	 
	 $db = new database();
	 
	 
	 __pdf_cleartmp();
	 __log_clearfile();
?>