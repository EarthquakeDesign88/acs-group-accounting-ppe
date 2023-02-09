<?php
    if(!empty($_GET["test"])){
       error_reporting(-1);
    }else{
        error_reporting(0);
    }

	//Test
    // 	$str_hostname = "localhost";
    // 	$str_username = "root";
    // 	$str_password = "";
    // 	$str_dbname = "eptgacsc_accounting_ppe";

	//PPE
	$str_hostname = "localhost";
	$str_username = "cp261186_acsdev";
	$str_password = "88Acsdev@88@88";
	$str_dbname = "cp261186_accounting-ppe";

	//PROD
	// $str_hostname = "localhost";
	// $str_username = "eptgacsc_accuser";
	// $str_password = "AccAcsc3300";
	// $str_dbname = "eptgacsc_accounting";

	$obj_con = mysqli_connect($str_hostname, $str_username, $str_password, $str_dbname);

	if(!$obj_con){
		header("location:error.php");
		exit();
	}
	
	mysqli_query($obj_con, "SET NAMES UTF8");
	date_default_timezone_set('Asia/Bangkok');

?>