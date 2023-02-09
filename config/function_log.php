<?php
if (!function_exists('__log_action')) {
	function __log_action($action="",$status="",$ref=0,$data=array(),$sub_folder="") {
		$user = (isset($_SESSION["user_id"])) ? $_SESSION["user_id"]: "";
		$date = date("Y-m-d");
		$datetime = date("Y-m-d H:i:s");
		
        if($sub_folder!=""){
             $folder = "logs/".$sub_folder;
            @mkdir($folder);
        }else{
            $folder = "logs";
        }
        
		$file = "logs_".$date.".txt"; 
		
		if(count($data)>=1){
			$detail = @serialize($data);
		}else{
			$detail = "";
		}
		
		$get = (isset($_GET)) ? $_GET : array();
		if(count($get)>=1){
			$data_get = @serialize($get);
		}else{
			$data_get = "";
		}
		
		$post= (isset($_POST)) ? $_POST: array();
		if(count($post)>=1){
			$data_post = @serialize($post);
		}else{
			$data_post = "";
		}
			
		@mkdir($folder);
		$log  =  "Datetime: ".$datetime.PHP_EOL.
		"IP Address: ".$_SERVER['REMOTE_ADDR'].PHP_EOL.
		"User: ".$user.PHP_EOL.
        "Action: ".$action.PHP_EOL.
        "Status: ".$status.PHP_EOL.
        "Ref: ".$ref.PHP_EOL.
        "Detail: ".$detail.PHP_EOL.
        "Data GET: ".$data_get.PHP_EOL.
        "Data POST: ".$data_post.PHP_EOL.
        "-------------------------".PHP_EOL;
		file_put_contents($folder.'/'.$file, $log, FILE_APPEND);
		
	}
}
if (!function_exists('__log_clearfile')) {
	function __log_clearfile() {
		$date = date('Y-m-d');
		$day = 60;
		$backdate_end = date('Y-m-d', strtotime('-'.$day.' day', strtotime($date)));
		
		$folder = "logs";
		$file = "logs_".$date.".txt"; 
		
		$folderpath = $folder;
		$filepath = $folderpath."/".$file;
		
		$folders = glob($folder."/*");
		
		if(count($folders)>=1){
			foreach ($folders as $key => $val) {
				$fileslogs = glob($val."/*");
				
				if(count($fileslogs)>=1){
					foreach ($fileslogs as $key2 => $val2) {
						$file2 = $val2;
						$val2 = explode("/", $val2);
						$val2 = explode("_", end($val2));
						$val2 = str_replace(".txt", "", end($val2));
						
						if ($val2 <= $backdate_end) {
						   unlink($file2);
						}
					}
				}else{
					$file = $val;
					$val = str_replace($folderpath."/logs_", "", $val);
					$val = str_replace(".txt", "", $val);
					
					if ($val <= $backdate_end) {
					   unlink($file);
					}
					
				}
			}
		}
	}
}
if (!function_exists('__log_logging')) {
	function __log_logging($action="",$user_name="",$password="",$status="", $status_message="") {
		$date = date("Y-m-d");
		$datetime = date("Y-m-d H:i:s");
		$folder = "logs";
		$sub_folder = "logs_logging";
		$file = $sub_folder."_" .$date." .txt"; 
		@mkdir($folder.'/'.$sub_folder);
			
		if($action=="login"){
			$log  = "Datetime: ".$datetime.PHP_EOL.
			"IP Address: ".$_SERVER['REMOTE_ADDR'].PHP_EOL.
			"Action: ".$action.PHP_EOL.
			"Status: ".$status.PHP_EOL.
			"Status Message: ".$status_message.PHP_EOL.
			"Username: ".$user_name.PHP_EOL.
			"Password: ".__encode($password).PHP_EOL.
			"-------------------------".PHP_EOL;
			file_put_contents($folder.'/'.$sub_folder.'/'.$file, $log, FILE_APPEND);
		}else 	if($action=="logout"){
			$log  = "Datetime: ".$datetime.PHP_EOL.
			"IP Address: ".$_SERVER['REMOTE_ADDR'].PHP_EOL.
			"Action: ".$action.PHP_EOL.
			"Username: ".$user_name.PHP_EOL.
			"-------------------------".PHP_EOL;
			file_put_contents($folder.'/'.$sub_folder.'/'.$file, $log, FILE_APPEND);
		}
		
	}
}
?>