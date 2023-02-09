<?php
include 'config/config.php'; 
include 'connect.php';
__check_login();

$user_id = __session_user("id");
?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'head.php'; ?>
</head>
<body class="loadwindow">
	<?php include 'navbar.php'; ?>
	<section>
		<div class="container">
			<form method="POST" name="frmSelComp" id="frmSelComp" action="">
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-company"></i>&nbsp;&nbsp;เลือกบริษัท
						</h3>
					</div>
				</div>
				<div class="row pt-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="input-group mb-3">
							<?php
							$company_list = __authority_company_list($user_id);
							$html_option = "";
							foreach ($company_list as $company_key => $company_val) {
								$html_option .= '<option value="'.$company_val["comp_id"].'">';
								$html_option .=$company_val["comp_name"];
								$html_option .= '</option>';
							}
							?>
							<select class="custom-select form-control" id="compid" name="compid" style="font-size: 20px; height: 52px;">
								<option value="" selected>กรุณาเลือกบริษัท...</option>
								<?=$html_option;?>
							</select>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 text-center">
						<input type="button" class="btn btn-success px-5 py-2" name="btnNext" id="btnNext" value="ถัดไป">
					</div>
				</div>
			</form>
		</div>
		
		
	<script type="text/javascript">
		$(document).ready(function() {
			$('#btnNext').click(function(){
				var input = $('#compid');
				if(input.val() == '') {
					swal({
						title: "กรุณาเลือกบริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					},function() {
						input.focus();
					
					});
				} else {
					var compid = input.val();
					window.location.href = "index.php?cid=" + compid;
				}
			});
		});
	</script>
	<?php include 'footer.php'; ?>
	</body>
</html>