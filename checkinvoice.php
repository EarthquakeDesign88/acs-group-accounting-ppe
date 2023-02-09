<?php
include 'config/config.php'; 
__check_login();

$user_id = __session_user("id");
$user_level_id = __session_user("level_id");
$user_department_id = __session_user("department_id");

$paramurl_step = (isset($_GET["step"])) ?$_GET["step"] : 0;
$paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;

$count_dep = __data_company_count_department($paramurl_company_id);
$comp_name = __data_company($paramurl_company_id,"name");
$dep_name = __data_department($paramurl_department_id,"name");
$dep_name_th = __data_department($paramurl_department_id,"name_th");
$dep_name_new = ($dep_name_th!="") ? $dep_name." - ".$dep_name_th : "ฝ่าย ".$dep_name;

$invoice_step = __invoice_step_company_list($user_id,$paramurl_company_id,$paramurl_department_id);

$ck_permiss = false;
$step_name = "";
$step_icon = "";
$step_key = "checkinvoice_".$paramurl_step;
$step_getdep = "";
if(!empty($invoice_step[$step_key])){
	$array_step = $invoice_step[$step_key];
	$ck_permiss = true;
	$step_name = $array_step["name"];
	$step_icon = $array_step["icon"];
	$step_getdep = $array_step["getdep"];
}else{
	header("Location: index.php?cid=".$paramurl_company_id);
}

$authority_comp_count_dep = __authority_company_count_department($user_id,$paramurl_company_id,$step_getdep);
if($count_dep>1 || $authority_comp_count_dep>1){
	$url_back = "index.php?invoice_seldep=".$step_key."&cid=".$paramurl_company_id;
}else{
	$url_back = "index.php?cid=".$paramurl_company_id;
}
?>

<?php
$FilterByList = __invoice_filterby_list();
$FilterValList = __invoice_filterval_list();
$SearchByList = __invoice_searchby_list();

$filterby_option = "";
$filterby_option .= '<optgroup label="เรียงลำดับตาม">';
if(count($FilterByList)>=1){
	foreach ($FilterByList as $keyFilterBy => $valFilterBy) {
		$sel_filterby = ($keyFilterBy==1) ? " selected " : "";
		$filterby_option .= '<option value="'.$keyFilterBy.'" '.$sel_filterby.'>';
		$filterby_option .= $valFilterBy["name"];
		$filterby_option .= '</option>';
	}
}
$filterby_option .= '</optgroup>';

$filterval_option = "";
$filterval_option .= '<optgroup label="เรียงจาก">';
if(count($FilterValList)>=1){
	foreach ($FilterValList as $keyFilterVal => $valFilterVal) {
		$sel_filterval = ($keyFilterVal==1) ? " selected " : "";
		$filterval_option .= '<option value="'.$keyFilterVal.'" '.$sel_filterval.'>';
		$filterval_option .= $valFilterVal["name"];
		$filterval_option .= '</option>';
	}
}
$filterval_option .= '</optgroup>';


$searchby_option = "";
$searchby_option .= '<optgroup label="ค้นหาโดย">';
if(count($SearchByList)>=1){
	foreach ($SearchByList as $keySearchBy => $valSearchBy) {
		$sel_searchby = ($keySearchBy==1) ? " selected " : "";
		
		$searchby_option .= '<option value="'.$keySearchBy.'" '.$sel_searchby.'>';
		$searchby_option .= $valSearchBy["name"];
		$searchby_option .= '</option>';
	}
}
$searchby_option .= '</optgroup>';
?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'head.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/checkbox.css">
	<style type="text/css">
		.table .thead-light th {
			color: #000;
		}
		tr:nth-last-child(n) {
			border-bottom: 1px solid #dee2e6;
		}
		
		.truncate-des{
			width: auto;
			min-width: 0;
			max-width: 340px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		
	   .truncate-id{
			width: auto;
			min-width: 0;
			max-width: 180px;
			display: inline-block;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		
		.col-detail{
			border-bottom: 1px dashed #333;
		}
		
		.title-amount{
			font-size:25px; 
			font-weight: bold;
			text-align: right;
		}
		.amount{
			font-size:25px; 
			height: calc(1.5em + .75rem + 2px); 
			padding: .375rem .75rem;
			text-align: right;
			background-color: #e9ecef;
			border-bottom: 5px double #bbbbbb !important;	
		}
		
		tr.row-checked > td{
			background-color: rgb(218, 255, 204);
		}
		
		.modal-detail{
			z-index: 2001;
		}
		
		.modal-view{
			z-index: 2000;
		}
		
		tfoot tr td{
			border-top: 2px solid #bbbbbb !important;	
			border-bottom: 2px solid #bbbbbb !important;	
		}
		
		.swal-table{
			font-size:18px; 
		}
		
		.swal-count{
			background-color: #e9ecef;
			text-align:right;
		}
		
		.swal-amount{
			background-color: #e9ecef;
			text-align:right;
		}
	</style>
</head>
	<body class="loadwindow">
		<?php include 'navbar.php'; ?>
			<?php if($ck_permiss){ ?>
			<section>
			  <div class="container">
			  	<form action="javascript:void(0)" id="action_form" name="action_form" method="post" role="form">
					<div class="row"  style="background-color: #E9ECEF;border-bottom:1px solid #d4d3d3;">
						<div class="col-md-10 col-sm-12 mt-4">
							<div class="row">
								<div class="col-md-12"><h3>อนุมัติใบแจ้งหนี้: <?=$step_name;?></h3></div>
							</div>
							
							<div class="row">
								<div class="col-md-8 col-form-label text-left"><font class="font-bold"><i class="icofont-rounded-right"></i> ชื่อบริษัท: </font><font class="font-normal"><?=$comp_name;?></font></div>
								<div class="col-md-4 col-form-label text-left"><font class="font-bold"><i class="icofont-rounded-right"></i> ชื่อฝ่าย: </font><font class="font-normal"><?=$dep_name_new;?></font></div>
							</div>
						</div>
						
						<div class="col-md-2 col-sm-12 mt-4 text-right">
							<a href="<?=$url_back;?>" type="button" class="btn btn-warning mb-2" name="btnBack" id="btnBack">
								<i class="icofont-history"></i> ย้อนกลับ
							</a>
						</div>
					</div>
					
					
					<div class="row py-4 px-1" style="background-color: #f4f4f4">
						<div class="col-md-12 text-right">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-md-3 col-sm-12 col-form-label text-left">เรียงลำดับตาม: </label>
										<div class="col-md-3 col-sm-12 mb-1">
											<select class="custom-select form-control" id="s_filterby">
												<?=$filterby_option;?>
											</select>
										</div>
										<div class="col-md-6 col-sm-12 mb-1">
											<div class="input-group">
												<select class="custom-select form-control" id="s_filterval">
													<?=$filterval_option;?>
												</select>
											</div>
										</div>									
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12 text-right">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group row">
										<label class="col-md-3 col-sm-12 col-form-label text-left">ค้นหา: </label>
										<div class="col-md-3 col-sm-12 mb-1">
											<select class="custom-select form-control" id="s_searchby">
												<?=$searchby_option;?>
											</select>
										</div>
										<div class="col-md-6 col-sm-12 mb-1">
											<input type="text" name="s_keywords" id="s_keywords" class="form-control" placeholder="กรอกคำค้นหา" autocomplete="off">
										</div>									
									</div>
								</div>
							</div>
						</div>
					</div>	
					<div class="row py-4 px-1"  style="background-color: #FFFFFF;">
						<div class="col-md-12" id="divFormCheck">
							<?php include 'fetch_checkinvoice.php'; ?>
						</div>
					</div>
					
				</form>
			</div>	
		</section>
		
		<script>
			function delay(callback, ms) {
			  var timer = 0;
			  return function() {
			    var context = this, args = arguments;
			    clearTimeout(timer);
			    timer = setTimeout(function () {
			      callback.apply(context, args);
			    }, ms || 0);
			  };
			}
			
			$('#s_filterby, #s_filterval, #s_searchby').change(function() {
				onSearch();
			});

			$('#s_keywords').keyup(delay(function (e) {
				var value = $('#s_keywords').val();
					onSearch();
				}, 500));

			function onSearch(){
				var div = $('#divFormCheck');
				var s_filterby = $('#s_filterby').val();
				var s_filterval = $('#s_filterval').val();
				var s_searchby = $('#s_searchby').val();
				var s_keywords = $('#s_keywords').val();
				
				loadPage(1,s_filterby,s_filterval,s_searchby,s_keywords);
			}
			
			function loadPage(pagenumber,s_filterby,s_filterval,s_searchby,s_keywords){
				var div = $('#divFormCheck');
				var arr = new Array();
				var data = "";
				
				if(pagenumber!=""){arr.push("pagenumber="+pagenumber);}	
				if(typeof(s_filterby)  === "undefined") {}else{if(s_filterby!=""){arr.push("s_filterby="+s_filterby);}}
				if(typeof(s_filterval)  === "undefined") {}else{if(s_filterval!=""){arr.push("s_filterval="+s_filterval);}}
				if(typeof(s_searchby)  === "undefined") {}else{if(s_searchby!=""){arr.push("s_searchby="+s_searchby);}}
				if(typeof(s_keywords)  === "undefined") {}else{if(s_keywords!=""){arr.push("s_keywords="+s_keywords);}}
				
				if (arr.length >= 1) {
				    data = data;
				    data = data+arr.join("&");
				}
				
				div.html("<br><div align='center'>กรุณารอสักครู่...<br><br><i class='icofont-spinner icofont-spin' style='font-size:24px'></i></div>");
				
				$.ajax({
					url:"fetch_checkinvoice.php?step=<?=$paramurl_step;?>&cid=<?=$paramurl_company_id;?>&dep=<?=$paramurl_department_id;?>",
					method:"POST",
					data:data,
					success:function(data) {
						setTimeout(function() {
							div.html(data);
						}, 500);
						
					}
				});
			}
		</script>

		<?php }else{ ?>
			<section>
			  <div class="container">
			  	<div class="col-md-12 pt-4  pb-4">
			  	<center><h3 style="color:red">ขออภัย คุณไม่มีสิทธิ์เข้าถึงข้อมูล!</h3></center>
			  	</div>
			</div>	
		</section>
		<?php } ?>	
	</body>
</html>