<?php

	session_start();
	if (!$_SESSION["user_name"]) {  //check session
		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
	} else {
		include 'connect.php';

        $cid = $_GET["cid"];
		$dep = $_GET["dep"];

		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);

?>
<!DOCTYPE html>
<html>
<head>

	<?php include 'head.php'; ?>

</head>
<body>

	<?php include 'navbar.php'; ?>

	<section>
		<div class="container">
			
			<form method="POST" name="selectProject_form" id="selectProject_form" action="">
				<div class="row py-4 px-1" style="background-color: #65f8ea">
					<div class="col-md-12">
						<h3 class="mb-0">
                            <i class="icofont-paper"></i>&nbsp;&nbsp;รวมงวดงาน
						</h3>
					</div>
		
				</div>
				<div class="row pt-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="input-group mb-3">
							<select class="custom-select form-control" id="select_project" style="font-size: 20px; height: 52px;">
                                <option value="" selected disabled="">กรุณาเลือกบริษัท...</option>
                                <?php
                                    $sql_project = "SELECT * FROM project_tb WHERE proj_total = '1' ORDER BY proj_name ASC";
                                    $obj_project = mysqli_query($obj_con, $sql_project);
                                
                                    while($obj_row_project = mysqli_fetch_array($obj_project)) { ?>
                                    <option value="<?=$obj_row_project['proj_id']?>" >
                                        <?=$obj_row_project['proj_name']?>
                                    </option>
                                <?php } ?>

							</select>
						</div>
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 text-center">
						<a id="selectBtn" class="btn btn-success px-5 py-2">ถัดไป</a>
					</div>
				</div>
			</form>

		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {
			$("#selectBtn").click(function() {
				let projectID = $("#select_project").val();
				let url = `invoice_rcpt_total_project_desc.php?cid=<?=$cid?>&dep=<?=$dep?>&projid=${projectID}` ;

				if(projectID == null) {
					swal({
						title: "กรุณาเลือกบริษัท",
						text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
						type: "warning",
						closeOnClickOutside: false
					});
				}
				else {
					window.location.href = url;
				}

			});

		});
	</script>


</body>
</html>
<?php } ?>