<?php
include 'config/config.php'; 
__check_login();

$user_id = __session_user("id");
$user_level_id = __session_user("level_id");
$user_department_id = __session_user("department_id");

$paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;

$authority_comp_count_dep = __authority_company_count_department($user_id,$paramurl_company_id);
$authority_dep_text_list = __authority_department_text_list($user_id,$paramurl_company_id);
$authority_dep_check = __authority_department_check($user_id,$paramurl_company_id,$paramurl_department_id);
$arrDepAll = __authority_department_list($user_id,$paramurl_company_id);

$page = 'payment_seepdf.php';
if($authority_comp_count_dep==1){
	header("Location: ".$page."?cid=".$paramurl_company_id."&dep=".$authority_dep_text_list);
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'head.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/checkbox.css">
</head>
	<body>
		<?php include 'navbar.php'; ?>
		<section>
			<div class="container">
				<?php
					$html_title = 'แฟ้มข้อมูล <i class="icofont-caret-right"></i> รายจ่าย  <i class="icofont-caret-right"></i> ใบสำคัญจ่าย <i class="icofont-caret-right"></i> เลือกฝ่าย';
					
					$html_dep_box = __html_dep_box2($html_title,$page);
				?>
				<?=$html_dep_box;?>
			</div>
		</section>

	</body>
</html>