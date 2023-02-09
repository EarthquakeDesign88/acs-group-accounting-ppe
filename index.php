<?php
include 'config/config.php'; 
__check_login();


$user_id = __session_user("id");
$user_level_id = __session_user("level_id");
$user_department_id = __session_user("department_id");

$paramurl_company_id = (isset($_GET["cid"])) ?$_GET["cid"] : 0;
$paramurl_department_id = (isset($_GET["dep"])) ?$_GET["dep"] : 0;

$paramurl_invoice_seldep = (isset($_GET["invoice_seldep"])) ?$_GET["invoice_seldep"] : "";
$paramurl_purchasereq_seldep = (isset($_GET["purchasereq_seldep"])) ?$_GET["purchasereq_seldep"] : "";

$invoice_step = __invoice_step_company_list($user_id,$paramurl_company_id,$paramurl_department_id,true);

$authority_comp_count_dep = 0;
$authority_dep_text_list = "";
$authority_dep_check = false;
$arrDepAll = array();
if($paramurl_invoice_seldep!=""){
	if(!empty($invoice_step[$paramurl_invoice_seldep])){
		$getdep = (isset($invoice_step[$paramurl_invoice_seldep]["getdep"])) ? $invoice_step[$paramurl_invoice_seldep]["getdep"] : "";
		$authority_comp_count_dep = __authority_company_count_department($user_id,$paramurl_company_id,$getdep);
		$authority_dep_text_list = __authority_department_text_list($user_id,$paramurl_company_id,$getdep);
		$authority_dep_check = __authority_department_check($user_id,$paramurl_company_id,$paramurl_department_id,$getdep);
		$arrDepAll = __authority_department_list($user_id,$paramurl_company_id,$getdep);
	}
}else{
	$authority_comp_count_dep = __authority_company_count_department($user_id,$paramurl_company_id);
	$authority_dep_text_list = __authority_department_text_list($user_id,$paramurl_company_id);
	$authority_dep_check = __authority_department_check($user_id,$paramurl_company_id,$paramurl_department_id);
	$arrDepAll = __authority_department_list($user_id,$paramurl_company_id);
}


if($authority_comp_count_dep==1){
	if(!$authority_dep_check){
		header("Location: index.php?cid=".$paramurl_company_id."&dep=".$authority_dep_text_list);
		exit;
	}
}else{
	if(empty($paramurl_department_id)){
		if(!$authority_dep_check){
			if($paramurl_invoice_seldep!=""){
				if(empty($invoice_step[$paramurl_invoice_seldep])){
					header("Location: index.php?cid=".$paramurl_company_id);
					exit;
				}
			}else if($paramurl_purchasereq_seldep!=""){
				if(empty($purchasereq_step[$paramurl_purchasereq_seldep])){
					header("Location: index.php?cid=".$paramurl_company_id);
					exit;
				}
			}
		}else{
			header("Location: index.php?cid=".$paramurl_company_id);
			exit;
		}
	}else{
 		header("Location: index.php?cid=".$paramurl_company_id);
		exit;
	}	
}
?>
<!DOCTYPE html>
	<html>
	<head>
		<?php include 'head.php'; ?>
		<style type="text/css">
			article {
				max-width: 100%;
				overflow: hidden;
				margin: 0 auto;
			}

			.subtitle {
				margin: 0 0 2em 0;
			}

			.fancy span {
				display: inline-block;
				position: relative;
				font-size: 1.75rem;
				font-weight: 600;
				padding: 12px 8px;
			}

			.fancy span:after {
				content: "";
				position: absolute;
				height: 3px;
				background-color: #195f5f;
				border-top: 2px solid #195f5f;
				top: 50%;
				width: 1366px;
			}

			.fancy span:after {
				left: 100%;
				margin-left: 15px;
			}

			
			
			section.home{
				margin-bottom: 100px !important;
			}
		</style>
	</head>

	<body class="loadwindow">
		<?php include 'navbar.php'; ?>
		<section class="<?= ($paramurl_invoice_seldep=="") ? "home": "";?>">
			<div class="container">
				<?php
				//ใบแจ้งหนี้ ( รายจ่าย )
				$html_invoice_title = "";
				if($paramurl_invoice_seldep!=""){
					$arrInvoiceStepMain = $invoice_step;
					$arrInvoiceStep[$paramurl_invoice_seldep] = $arrInvoiceStepMain[$paramurl_invoice_seldep];
					$arrDep = $arrDepAll;
					
					if(!empty($arrInvoiceStepMain[$paramurl_invoice_seldep]["name"])){
						$html_invoice_title .= '<b>ใบแจ้งหนี้ (รายจ่าย) </b><i class="icofont-caret-right"></i> '.$arrInvoiceStepMain[$paramurl_invoice_seldep]["name"];
					}
				}else{
					if($paramurl_purchasereq_seldep==""){
						$arrInvoiceStep = $invoice_step;
						$arrDep = array();
						
						$html_invoice_title .= "ใบแจ้งหนี้ (รายจ่าย)";
					}
				}
				
				if(!empty($arrInvoiceStep)){
					$html_invoice_box = "";
					foreach ($arrInvoiceStep as $keyInvoiceStep => $valueInvoiceStep) {
						$invoice_step_name = $valueInvoiceStep["name"];
						$invoice_step_class_box = $valueInvoiceStep["class_box"];
						$invoice_step_icon = $valueInvoiceStep["icon"];
						$invoice_step_page = $valueInvoiceStep["page"];
						$invoice_step_con_where = $valueInvoiceStep["query_where"];
						$invoice_step_getdep = $valueInvoiceStep["getdep"];
						$invoice_step_authority_comp_count_dep = __authority_company_count_department($user_id,$paramurl_company_id,$invoice_step_getdep);
						
						if($invoice_step_con_where!=""){
							$sql_invoice_count = "SELECT COUNT(i.inv_compid) AS count ".__invoice_query_from()." WHERE i.inv_depid IN ('".$authority_dep_text_list."')";
							$sql_invoice_count .= " AND ".$invoice_step_con_where;
							$row_invoice_count= $db->query($sql_invoice_count)->row();
							$invoice_step_count = $row_invoice_count["count"];
							
						}else{
							$invoice_step_count = 0;
						}
						
						
						if(count($arrDep)>=1){
							
						}else{
							if($invoice_step_authority_comp_count_dep==1){
								$href_invoice = "";
								if($invoice_step_page!=""){
									if (strpos($invoice_step_page, "?") !== false) {
										$href_invoice = $invoice_step_page."&";
									}else{
										$href_invoice = $invoice_step_page."?";
									}
									
									$href_invoice .= "cid=".$paramurl_company_id."&dep=".__authority_company_department_main($user_id,$paramurl_company_id,$invoice_step_getdep);
								}else{
									$href_invoice =  "javascript:alert('null');";
								}
							}else{
								if($invoice_step_authority_comp_count_dep>=2){
									$href_invoice = "index.php?invoice_seldep=".$keyInvoiceStep."&cid=".$paramurl_company_id;
								}else{
									if($invoice_step_page!=""){
										if (strpos($invoice_step_page, "?") !== false) {
											$href_invoice = $invoice_step_page."&";
										}else{
											$href_invoice = $invoice_step_page."?";
										}
										
										$href_invoice .= "cid=".$paramurl_company_id."&dep=".$paramurl_department_id;
									}else{
										$href_invoice =  "javascript:alert('null');";
									}
								}
							}
							
							$html_invoice_box .= __box_style($href_invoice,$invoice_step_class_box,$invoice_step_name,$invoice_step_icon,$invoice_step_count);
						}
						
						$html_invoice_dep_box = __html_dep_box($html_invoice_title,$invoice_step_page,$invoice_step_icon," AND ".$invoice_step_con_where,$arrDep,str_replace("FROM", "", __invoice_query_from()),"i.inv_depid");
						$html_invoice_box .= $html_invoice_dep_box;
					}
				
						if($paramurl_invoice_seldep==""){
							$html_invoice = "";
							$html_invoice .= '<div style="margin-bottom:100px;display:block;">';
								$html_invoice .= '<div class="row">';
									$html_invoice .= '<div class="col-md-12 mr-auto">';
										$html_invoice .= '<article><p class="subtitle fancy"><span>'.$html_invoice_title.'</span></p></article>';
									$html_invoice .= '</div>';
								$html_invoice .= '</div>';
								
								 $html_invoice .= '<div class="row">';
									$html_invoice .=  $html_invoice_box;
								  $html_invoice .= '</div>';
							  $html_invoice .= '</div>';	
						}else{
							$html_invoice = $html_invoice_box;	
						}
						
					}
				?>
				
				<?=$html_invoice;?>
			</div>
		</section>
		<?php include 'footer.php'; ?>
	</body>
</html>