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

		// echo $obj_row_user["lev_name"];

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

			<form method="POST" name="" id="" action="">
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-listing-number"></i>&nbsp;&nbsp;รายละเอียดโครงการ
						</h3>
					</div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$cid;?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$dep;?>">
					</div>
				</div>

				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12">
						<div class="table-responsive" id="ContractLessonTable"></div>
					</div>
				</div>
			</form>
			
		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function() {

			load_data(1);
			function load_data(page, query = '', queryComp = '', queryDep = '') {
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				$.ajax({
					url:"fetch_project.php",
					method:"POST",
					data:{page:page, query:query, queryComp:queryComp, queryDep:queryDep},
					success:function(data) {
						$('#ProjectTable').html(data);
					}
				});				
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				load_data(page, query, queryComp, queryDep);
			});

			$('#search_box').keyup(function(){
				var query = $('#search_box').val();
				var queryComp = $('#compid').val();
				var queryDep = $('#depid').val();
				load_data(1, query, queryComp, queryDep);
			});

		});
	</script>

	<?php include 'footer.php'; ?>

</body>
</html>
<?php } ?>