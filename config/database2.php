<?php
if (!class_exists('database')) {
	class database {
	
		public $conn;
		
		function __construct() {
			
			 if($_SERVER["SERVER_NAME"] == 'localhost' || $_SERVER["SERVER_NAME"] == '127.0.0.1'){
		 		$hostname = "localhost";
				$username  = "root";
				$password = "";
				$database  = "eptgacsc_accounting_ppe";
		    }else{
				$hostname = "localhost";
				$username = "cp261186_acsdev";
				$password = "88Acsdev@88@88";
				$database = "cp261186_accounting-ppe";
		    }
	
			$conn = mysqli_connect($hostname,$username,$password,$database) or die ("could not connect to mysql 2"); 
			mysqli_set_charset($conn,"utf8");	
			$this->conn = $conn;
			
		}

		function query($sql) {
			$this->query = mysqli_query($this->conn, $sql);
			return $this;
		}

		function result() {
			while ($result = mysqli_fetch_assoc($this->query)) {
				$resultset[] = $result;
			}
			if (!empty($resultset)) {
				return $resultset;
			}else{
				return array();
			}
		}
		
		function row() {
			$row = mysqli_fetch_assoc($this->query);

			if (!empty($row)) {
				return $row;
			}
		}

		function num_rows() {
			$num_rows = mysqli_num_rows($this->query);
			return $num_rows;
		}
		
		function insert_id() {
			$insert_id = mysqli_insert_id($this->conn);
			return $insert_id;
		}
		
		function real_escape_string($html=""){
			$real_escape_string = mysqli_real_escape_string($this->conn,$html);
			return $real_escape_string;
		}
		
		function error($show=1){
			if(mysqli_error($this->conn)) {
				if($show==2){
					return true;
				}else{
				  	echo ("Error description: " . mysqli_error($this->conn));
					return true;
				}
			 }
		}

	}

}
?>	
