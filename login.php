<?php 
	include 'config/config.php'; 
	__check_index();
?>
<!DOCTYPE html>
<html>
<head>
	
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="image/acs.png" type="images/png" sizes="16x16">
	<title>Login ACS - Accounting</title>

	<script src="plugins/jQuery/jquery.min.js"></script>
	<script src="plugins/bootstrap/bootstrap.min.js"></script>
	
	<link rel="stylesheet" href="css/style_login.css">
	<link href="plugins/icofont/icofont.min.css" rel="stylesheet">
	
	<script src="js/sweetalert.min.js"></script>
</head>
<body>

	<div style="height: 100vh; background-color: rgba(0,0,0,.6); box-sizing: border-box; margin-top: -8px; margin-left: -8px">
		<div class="login" id="login">
			<h1>Login ACS</h1>

			<form id="frmLogin" name="frmLogin" method="POST" action="">
				
				<input type="text" name="username" id="username" placeholder="Username" autocomplete="off" autofocus>

				<input type="password" name="password" id="password" placeholder="Password" autocomplete="off">
				
				<div id="divAlert"></div>
				
				<button type="button" name="btnLogin" id="btnLogin" class="btn btn-primary btn-block btn-large" onclick="onLogin()">Log in</button>

			</form>
		</div>
	</div>

	<script type="text/javascript">
 		function IsJsonString(str) {
		    try {
		        JSON.parse(str);
		    } catch (e) {
		        return false;
		    }
		    return true;
		}
	
		function onLogin(){
			var form = $("#frmLogin");
			var divAlert = $("#divAlert");
			var input_username = $('#username');
			var input_password = $('#password');
			var value_username = input_username.val();
			var value_password = input_password.val();
			var btn = $("#btnLogin");
			
			var text_error = "";
			var check_input = false;
			var focus_input ="";
			if(value_username !="" &&  value_password !="") {
				check_input = true;
			}else{
				if(value_username=="" && value_password==""){
					 text_error="กรุณากรอก Username และ Password";
					 focus_input = input_username;
				}else{
					if(value_username==""){
						text_error="กรุณากรอก Username";
						focus_input = input_username;
					}else if(value_password==""){
						text_error="กรุณากรอก Password";	
						focus_input = input_password;
					}
				}
			}
			
			btn.prop("disabled",true);
			divAlert.html('<label class="alert alert-loading"></label>');
			
			if(check_input){
				$.ajax({
					type: "POST",
					url: "chk_login.php",
					data: form.serialize(),
					success: function(jsondata) {
						if(IsJsonString(jsondata)==true){
							var res = JSON.parse(jsondata);  
							
							if($.trim(res.response)=="S"){
								btn.prop("disabled",true);
								divAlert.html('<label class="alert alert-success-loader">เข้าสู่ระบบสำเร็จ กรุณารอสักครู่...</label>');
								
								swal({
								  	title: 'เข้าสู่ระบบสำเร็จ',
								  	text: 'กรุณารอสักครู่...',
								 	icon: "success",
								  	timer: 1000,
									showCancelButton: false,
									showConfirmButton: false,
									closeOnClickOutside: false,
									buttons: false,
								}).then(
								  function () {
								  	window.location.href = "index_selcomp.php";
								  },
								  function (dismiss) {
								    if (dismiss === 'timer') {
								    }
								  }
								)
							}else{
								btn.prop("disabled",false);
								divAlert.html('<label class="alert alert-error"> '+$.trim(res.message)+'</label>');
							} 
						}else{
							btn.prop("disabled",false);
							divAlert.html('<label class="alert alert-error">กรุณาลองใหม่อีกครั้ง</label>');
						}
					}
				});
			}else{
				btn.prop("disabled",false);
				divAlert.html('<label class="alert alert-error">'+text_error+'</label>');
				if(focus_input!=""){
					focus_input.focus();
				}
			}
		}
		
			
	    $(document).ready(function() {
			$('#username, #password').on('keyup', function(e) {
			    if (e.which == 13) {
					onLogin();
			    }
			});
		});
	</script>

</body>
</html>