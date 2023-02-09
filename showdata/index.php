<?php 
include '../config/config.php'; 
__check_login();
?>
<?php
$authen_key = "11223344";
$para_view =  (isset($_GET["view"])) ? $_GET["view"]: "level";
$para_authen_key = (isset($_GET["authen_key"])) ? $_GET["authen_key"] : "";
if($authen_key!=$para_authen_key){
	exit;
}

$array_view = array(
	"level" => "Level",
	"level_user" => "Level User",
	"company" => "Company",
	"dep" => "Department",
	"menu_access_company" => "กำหนดการเข้าถึงหน้า - บริษัท",
	"menu_access_level" => "กำหนดการเข้าถึงหน้า - Level",
	"menu_access_user" => "กำหนดการเข้าถึงหน้าของ User",
	/*"view_navbar" => "ดูการแสดง Navbar",
	"view_company" =>  "ดูการเลือกบริษัท",
	"company" => "Company",
	"dep" => "Department",
	"invoice_steplevel" => "ขั้นตอนใบแจ้งหนี้",
	 * 
	 */
);
?>
	<!DOCTYPE html>
	<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link href="../plugins/icofont/icofont.min.css" rel="stylesheet">
		<link rel="stylesheet" href="../css/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		
		<script src="../js/onLoadWindows.js"></script>
		<style>
			* {
			  box-sizing: border-box;
			}
			
			body,html{
				margin: 0px;
				font-size: 11px;
			    overflow-x: hidden;
			    min-height: 100%;
			    line-height: 14px;
			}
			
			
			header {
			  background-color: #666;
			  padding: 30px;
			  text-align: center;
			  font-size: 35px;
			  color: white;
			}
			
			nav {
			  float: left;
			  width: 190px;
			  height:100%;
			  max-height: 100%;
			  background: #c4e6f5;
			  position: fixed;
			  top: 0;
			  left:0;
			  overflow-y:scroll;
			}
			
			nav ul {
			  list-style-type: none;
			  padding: 0;
			}
			
			
			nav ul {
			  list-style-type: none;
			  margin: 0;
			  padding: 0;
			  width: 100%;
			}
			
			nav li a {
			  display: block;
			  color: #000;
			  padding: 4px 8px;
			  text-decoration: none;
			}
			
			nav li a:hover {
			  background-color: #7ed8ff;
			  color: #092a5e;
			}
			
			nav li.active a {
				background-color: #092a5e;
				color:#FFFFFF;
			}
			
			nav li.active a:before {
				content: " * ";
			}
			
			article {
			  width:calc( 100% - 190px);
			  height:100%;
			  max-height: 100%;
			  background-color: #adadad;
			  position: fixed;
			  top: 0;
			  left:190px;
			  overflow:scroll;
			}
			
			table {
			  border-collapse: collapse;
			  width: 100%;
			}
			
			table td, table th {
			  border: 1px solid #ddd;
			  padding: 2px;
			  background: #FFFFFF;
			  vertical-align:top;
			}
			
			table tr th{
				text-align: center;
				position: sticky; 
				top: -1px; 
				z-index: 1;
				outline: 2px solid #ddd;
			}
			
			table tr.row-title th{
				text-align: left;
				background-color: #c4e6f5;
				color: #081541;
				overflow: hidden;
				text-overflow: ellipsis;
				white-space: nowrap
			}
			
			table tr:nth-child(even){background-color: #f2f2f2;}
			
			table tr:hover > td{background-color: #ddd;}
			
			table th {
			  padding-top: 8px;
			  padding-bottom: 8px;
			  text-align: left;
			  background-color: #4f9dc0;
			}
			
			
			.text-center{text-align: center !important}
			.text-left{text-align: left !important}
			.text-right{text-align: right !important}
			
			.font-xs{font-size: 8px !important}

			.font-red{color: red !important;}
			
			button:disabled,
		button[disabled]{
		 opacity: 0.4;
		 cursor:no-drop;
		}
		
		
		@keyframes animation-spin {
		  from {transform:rotate(0deg)}
		  to {transform:rotate(360deg)}
		}
		
		.icofont-spin {
		    animation: animation-spin 2s;
		    animation-iteration-count: infinite;
		    animation-timing-function: linear;
		}

		.alert{
			font-family: 'Sarabun', sans-serif;
			margin-top: 10px;
			margin-bottom: 10px;
			padding: 8px 12px;
			font-size: .75rem; 
		    width: 100%;
		    display: block;
		    position: relative;
		    border: 1px solid transparent;
		    border-radius: 0.25rem;
		    font-size: 11px;
		}
		
		.alert-error{
			color: #842029;
    		background-color: #f8d7da;
    		border-color: #f5c2c7;
		}
		
		.alert-error:before	{
			font-size:18px;
			font-family: IcoFont!important;
			content:"\f026";
			padding-right: 4px;
		}
		
		.alert-success{
			color: #0f5132;
		    background-color: #d1e7dd;
		    border-color: #badbcc;
		}
		
		.alert-success:before	{
			font-size:18px;
			font-family: IcoFont!important;
			content:"\f021";
			padding-right: 4px;
		}
		
		.alert-success-loader{
			color: #0f5132;
		    background-color: #d1e7dd;
		    border-color: #badbcc;
		}
		
		.alert-success-loader:before{
			font-size:18px;
			font-family: IcoFont!important;
			content:"\effa";
			padding-right: 4px;
			display:inline-block;
		    animation: animation-spin 2s;
		    animation-iteration-count: infinite;
		    animation-timing-function: linear;
		}
		
		.alert-warning{
		    color: #664d03;
		    background-color: #fff3cd;
		    border-color: #ffecb5;
		}
		
		.alert-info{
		    color: #055160;
		    background-color: #cff4fc;
		    border-color: #b6effb;
		}
		
		.alert-loading{
		    color: #664d03;
		    background-color: #fff3cd;
		    border-color: #ffecb5;
		}
		
		.alert-loading:before{
			font-size:18px;
			font-family: IcoFont!important;
			content:"\effa";
			padding-right: 4px;
			display:inline-block;
		    animation: animation-spin 2s;
		    animation-iteration-count: infinite;
		    animation-timing-function: linear;
		}
		
		.alert-loading:after{
			content:"กรุณารอสักครู่...";
		}
		
		
		.btn{
			cursor: pointer;
		}
		
		.form-control{
			font-family: 'Sarabun', sans-serif;
			font-size: 12px;
		}
		
		</style>
			
	</head>

	<body>
		<section class="home">
			  <nav>
			    <ul>
			      <?php
			        $title_view = "";
					$page_view = "";
					foreach ($array_view as $key_view => $val_view) {
						$active_class = "";
						if($key_view==$para_view){
							$title_view = $val_view;
							$page_view = $key_view.".php";
							$active_class = "active";
						}
					?>
						<li class="<?=$active_class;?>"><a href="?authen_key=<?=$para_authen_key;?>&view=<?=$key_view;?>"><?=$val_view;?></a> </li>
					<?php
					}
					?>
				</ul>
			  </nav>
				  
		  <article>
		  	<div class="container">
			    <?php include $page_view;	?>
		    </div>
		  </article>
		</section>
		
	<script>
		
 		function IsJsonString(str) {
		    try {
		        JSON.parse(str);
		    } catch (e) {
		        return false;
		    }
		    return true;
		}
	</script>
	</body>
	</html>