<?php
    include 'config/config.php'; 
    
	if (!$_SESSION["user_name"]) {  //check session
		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {
		include 'connect.php';
        
        $cid = $_GET["cid"];
        $dep = $_GET["dep"];
        $data_checked = __invoice_payment_data_checked($cid,$dep);
        $arrayChecked  = $data_checked["arrayChecked"];
        $text_checked = implode("','",$arrayChecked);
        $countChk = count($arrayChecked);
        
        $n = 1;                            
        foreach ($arrayChecked as $key => $value) {
              $invid = "invid" . $n;
             $_GET["$invid"] = $key;
              $n++;
        }
        
        $_GET["countChk"] = $n;
		$str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '". $_SESSION["user_id"] ."'";
		$obj_rs_user = mysqli_query($obj_con, $str_sql_user);
		$obj_row_user = mysqli_fetch_array($obj_rs_user);
		// echo $obj_row_user["lev_name"];
		$str_sql_dep = "SELECT * FROM department_tb WHERE dep_id = '". $dep ."'";
		$obj_rs_dep = mysqli_query($obj_con, $str_sql_dep);
		$obj_row_dep = mysqli_fetch_array($obj_rs_dep);
		$str_sql_comp = "SELECT * FROM company_tb WHERE comp_id = '". $cid ."'";
		$obj_rs_comp = mysqli_query($obj_con, $str_sql_comp);
		$obj_row_comp = mysqli_fetch_array($obj_rs_comp);
		function DateThai($strDate) {
			$strYear = substr(date("Y",strtotime($strDate))+543,-2);
			$strMonth= date("n",strtotime($strDate));
			$strDay= date("j",strtotime($strDate));
			$strHour= date("H",strtotime($strDate));
			$strMinute= date("i",strtotime($strDate));
			$strSeconds= date("s",strtotime($strDate));
			$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$strMonthThai=$strMonthCut[$strMonth];
			return "$strDay $strMonthThai $strYear";
		}
		$invdesc = "";
		$invno = "";
		$invsubNoVat = 0;
		$invsubVat = 0;
		$invsubtotal = 0;
		$invvat = 0;
		$invDiffvat = 0;
		$grandtotal = 0;
		$invtax1 = 0;
		$invtax2 = 0;
		$invtax3 = 0;
		$invtaxpercent1 = 0;
		$invtaxpercent2 = 0;
		$invtaxpercent3 = 0;
		$invtaxtotal1 = 0;
		$invtaxtotal2 = 0;
		$invtaxtotal3 = 0;
		$invgrand = 0;
		$invdiff = 0;
		$invnet = 0;
		for ($i = 1; $i <= $countChk; $i++) { 
				
			$invid = "invid" . $i;
			$invid = $_GET["$invid"];
			$str_sql = "SELECT * FROM invoice_tb AS i 
						INNER JOIN company_tb AS c ON i.inv_compid = c.comp_id 
						INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
						INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
						WHERE inv_id = '" . $invid . "'";
			$obj_rs = mysqli_query($obj_con, $str_sql);
			$obj_row = mysqli_fetch_array($obj_rs);
			$invdesc = $obj_row["inv_description"] . " || " . $invdesc;
			$invno = $obj_row["inv_no"] . " || " . $invno;		
			if ($countChk > 1) {
				$invsubNoVat += $obj_row["inv_subtotalNoVat"];
				$invsubVat += $obj_row["inv_subtotal"];
				$invsubtotal += $obj_row["inv_subtotalNoVat"] + $obj_row["inv_subtotal"];
				$invvat += $obj_row["inv_vat"] + $obj_row["inv_differencevat"];
				$invtax1 += $obj_row["inv_tax1"];
				$invtax2 += $obj_row["inv_tax2"];
				$invtax3 += $obj_row["inv_tax3"];
				$invtaxpercent1 += $obj_row["inv_taxpercent1"];
				$invtaxpercent2 += $obj_row["inv_taxpercent2"];
				$invtaxpercent3 += $obj_row["inv_taxpercent3"];
				$invtaxtotal1 += $obj_row["inv_taxtotal1"] + $obj_row["inv_differencetax1"];
				$invtaxtotal2 += $obj_row["inv_taxtotal2"] + $obj_row["inv_differencetax2"];
				$invtaxtotal3 += $obj_row["inv_taxtotal3"] + $obj_row["inv_differencetax3"];
				$invgrand += $obj_row["inv_grandtotal"];
				$invdiff += $obj_row["inv_difference"];
				// $invnet += $obj_row["inv_netamount"];
				$invnet += $invsubNoVat + $invsubVat + $invvat - (($invtax1 * $invtaxpercent1) / 100) - (($invtax2 * $invtaxpercent2) / 100) - (($invtax3 * $invtaxpercent3) / 100) + $invdiff;
			} else {
				$invsubNoVat = $obj_row["inv_subtotalNoVat"];
				$invsubVat = $obj_row["inv_subtotal"];
				$invsubtotal = $invsubNoVat + $invsubVat;
				$invid = $obj_row["inv_id"];
				$invno = $obj_row["inv_no"];
				$invtax1 = $obj_row["inv_tax1"];
				$invtax2 = $obj_row["inv_tax2"];
				$invtax3 = $obj_row["inv_tax3"];
				$invtaxpercent1 = $obj_row["inv_taxpercent1"];
				$invtaxpercent2 = $obj_row["inv_taxpercent2"];
				$invtaxpercent3 = $obj_row["inv_taxpercent3"];
				$invtaxtotal1 = $obj_row["inv_taxtotal1"] + $obj_row["inv_differencetax1"];
				$invtaxtotal2 = $obj_row["inv_taxtotal2"] + $obj_row["inv_differencetax2"];
				$invtaxtotal3 = $obj_row["inv_taxtotal3"] + $obj_row["inv_differencetax3"];
				$invgrand = $invsubNoVat + $invsubVat + $obj_row["inv_vat"] + $obj_row["inv_differencevat"] - $invtaxtotal1 - $invtaxtotal2 - $invtaxtotal3;
			}
			$str_sql_ivappr = "SELECT * FROM invoice_tb WHERE inv_id = '" . $invid . "'";
			$obj_rs_ivappr = mysqli_query($obj_con, $str_sql_ivappr);
			$obj_row_ivappr = mysqli_fetch_array($obj_rs_ivappr);
			$apprCEOno = $obj_row_ivappr["inv_apprCEOno"];
			$apprMgrno = $obj_row_ivappr["inv_apprMgrno"];
			if ($obj_row["inv_statusCEO"] == 1) {
				$str_sql_ceo = "SELECT * FROM approveceo_tb AS aCEO 
								INNER JOIN invoice_tb AS i ON aCEO.apprCEO_no = i.inv_apprCEOno 
								INNER JOIN user_tb AS u ON aCEO.apprCEO_userid_create = u.user_id 
								WHERE i.inv_id = '" . $invid . "'";
				$obj_rs_ceo = mysqli_query($obj_con, $str_sql_ceo);
				$obj_row_ceo = mysqli_fetch_array($obj_rs_ceo);
				if ($obj_row_ceo["inv_apprCEOno"] != '') {
					$nameCEO = $obj_row_ceo["user_firstname"] . " " . $obj_row_ceo["user_surname"];
					$dateCEO = DateThai($obj_row_ceo["apprCEO_date"]);
				} else {
					$nameCEO = "";
					$dateCEO = "";
				}
			} else {
				$nameCEO = "";
				$dateCEO = "";
			}
			
			//Check department manager
			if ($obj_row["inv_statusMdep"] == 1) {
				$str_sql_mdep = "SELECT * FROM approvemdep_tb AS aMdep
								INNER JOIN invoice_tb AS i ON aMdep.apprMdep_no = i.inv_apprMdepno
								INNER JOIN user_tb AS u ON aMdep.apprMdep_userid_create = u.user_id 
								WHERE i.inv_id = '" . $invid . "'";
								
				$obj_rs_mdep = mysqli_query($obj_con, $str_sql_mdep);
				$obj_row_mdep = mysqli_fetch_array($obj_rs_mdep);
				if ($obj_row_mdep["inv_apprMdepno"] != '') {
					$nameMdep = $obj_row_mdep["user_firstname"] . " " . $obj_row_mdep["user_surname"];
					$dateMdep = DateThai($obj_row_mdep["apprMdep_date"]);
				} else {
					$nameMdep = "";
					$dateMdep = "";
				}
			} else {
				$nameMdep = "";
				$dateMdep = "";
			}
			$str_sql_mgr = "SELECT * FROM approvemgr_tb AS aMgr 
							INNER JOIN invoice_tb AS i ON aMgr.apprMgr_no = i.inv_apprMgrno 
							INNER JOIN user_tb AS u ON aMgr.apprMgr_userid_create = u.user_id 
							WHERE inv_id = '" . $invid . "'";
			$obj_rs_mgr = mysqli_query($obj_con, $str_sql_mgr);
			$obj_row_mgr = mysqli_fetch_array($obj_rs_mgr);
			
			if ($obj_row_mgr["inv_apprMgrno"] == '') {
				$nameMgr = '';
				$dateMgr = '';
			} else {
				$nameMgr = $obj_row_mgr["user_firstname"] . " " . $obj_row_mgr["user_surname"];
				$dateMgr = DateThai($obj_row_mgr["apprMgr_date"]);
			}
		}
?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>
	<link rel="stylesheet" type="text/css" href="css/checkbox.css">
	<style type="text/css">
		.table .thead-light th {
			color: #000;
			border: 1px solid #000;
		}
		.table-bordered.border-black {
			border: 1px solid #000;
		}
		.table td.tdborder-black {
			padding: 1rem .75rem;
			border-right: 1px solid #000;
			border-bottom: 1px dotted #000;
		}
		.table td.tdborder-black.black-left {
			border-left: 1px solid #000;
		}
		.table td.tdborder-black.black-bottom {
			border-bottom: 2px dotted #000!important;
		}
	</style>
</head>
<body>
	<?php include 'navbar.php'; ?>
	<section>
		<div class="container">
			<form method="POST" name="frmAddPayment" id="frmAddPayment" action="">
				
				<div class="row py-4 px-1" style="background-color: #E9ECEF">
					<div class="col-md-12">
						<h3 class="mb-0">
							<i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มใบสำคัญจ่าย
						</h3>
					</div>
					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="userlev" id="userlev" value="<?=$obj_row_user["user_levid"];?>">
					</div>
				</div>
				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-lg-3 col-md-3 pt-1 pb-3 pr-0">
						<label for="paymno" class="mb-1">เลขที่ใบสำคัญจ่าย</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-numbered"></i>
								</i>
							</div>
							<input type="text" class="form-control" name="paymno" id="paymno" value="" placeholder="เว้นว่างไว้เพื่อสร้างอัตโนมัติ" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3 pr-0">
						<label for="paymdate" class="mb-1">วันที่ใบสำคัญจ่าย</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="paymdate" id="paymdate" value="<?php echo date('Y-m-d'); ?>" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3 pr-0">
						<label for="paympaydate" class="mb-1">วันที่ทำจ่าย</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-ui-calendar"></i>
								</i>
							</div>
							<input type="date" class="form-control" name="paympaydate" id="paympaydate" value="">
						</div>
					</div>
					<div class="col-lg-1 col-md-3 pt-1 pb-3 pr-0">
						<label for="depname" class="mb-1">ฝ่าย</label>
						<div class="input-group mb-2">
							<!-- <div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-company"></i>
								</i>
							</div> -->
							<input type="text" class="form-control text-center px-0" name="depname" id="depname" value="<?=$obj_row_dep["dep_name"]?>" readonly>
						</div>
						<input type="text" class="form-control d-none pr-0" name="depid" id="depid" value="<?=$dep?>" readonly>
					</div>
					<div class="col-lg-2 col-md-3 pt-1 pb-3">
						<label for="paymstatus" class="mb-1">สถานะ</label>
						<div class="input-group mb-2">
							<!-- <div class="input-group-prepend">
								<i class="input-group-text">
									<i class="icofont-pay"></i>
								</i>
							</div> -->
							<input type="text" class="form-control" name="paymstatus" id="paymstatus" value="ค้างจ่าย" readonly style="background-color:#F00; color: #FFF; text-align: center; font-weight: 700;">
							<input type="text" class="form-control d-none" name="paymstatusid" id="paymstatusid" value="STS001" readonly>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 pt-1 pb-3" id="displayPay">
						<div class="row">
							<div class="col-md-4 col-sm-4">
								<label for="pay" class="mb-1"><span>เลือกการชำระเงิน</span></label>
								<div class="row">
									<div class="col-lg-5 col-md-5">
										<div class="input-group-prepend">
											<div class="checkbox">
												<input type="radio" name="paymbySel" id="paymbyCash" value="1" checked="checked">
												<label for="paymbyCash" class="mb-1"><span>เงินสด</span></label>
											</div>
										</div>
									</div>
									<!-- <div class="col-lg-5 col-md-5">
										<div class="input-group-prepend">
											<div class="checkbox">
												<input type="radio" name="paymbySel" id="paymbyCheque" value="2">
												<label for="paymbyCheque" class="mb-1"><span>เช็ค</span></label>
											</div>
										</div>
									</div> -->
								</div>
							</div>
							
							<div class="col-md-8 col-sm-12" id="detailCheque" style="display: none;">
								<div class="row">
									<div class="col-md-4 col-sm-12">
										<label for="chequeNo" class="mb-1">เลขที่เช็ค</label>
										<div class="input-group mb-2">
											<input type="text" class="form-control" name="chequeNo" id="chequeNo"  placeholder="กรุณากรอกเลขที่เช็ค" autocomplete="off" value="">
										</div>
									</div>
									<div class="col-md-4 col-sm-12">
										<label for="chequeDate" class="mb-1">ลงวันที่</label>
										<div class="input-group mb-2">
											<input type="date" class="form-control" name="chequeDate" id="chequeDate" autocomplete="off" value="">
										</div>
									</div>
									<div class="col-md-4 col-sm-12">
										<label for="SelectBank" class="mb-1">ธนาคาร</label>
										<div class="input-group mb-2">
											<select class="form-control custom-select" name="SelectBank" id="SelectBank">
												<option value="" selected>กรุณาเลือกธนาคาร</option>
												<?php
													$str_sql_b = "SELECT * FROM bank_tb";
													$obj_rs_b = mysqli_query($obj_con, $str_sql_b);
													while ($obj_row_b = mysqli_fetch_array($obj_rs_b)) {
												?>
												<option value="<?=$obj_row_b["bank_id"]?>"><?=$obj_row_b["bank_name"]?></option>
												<?php } ?>
												<input type="text" class="form-control d-none" name="chequeBankid" id="chequeBankid" value="">
											</select>
										</div>
									</div>
								</div>
							</div>
						
							<input type="text" class="form-control d-none" name="paymby" id="paymby" value="1">
							<script type="text/javascript">
								$(document).ready(function(){
									$('input[type=radio][name=paymbySel]').click(function() {
										if (this.value == '2') {
											document.getElementById("detailCheque").style.display = "block";
											document.getElementById("paymby").value = '2';
										} else if (this.value == '1') {
											document.getElementById("detailCheque").style.display = "none";
											document.getElementById("paymby").value = '1';
										}
									});
								});
								$('#SelectBank').change(function(){
									$('#chequeBankid').val($('#SelectBank').val());
									var queryDep = $('#chequeBankid').val();
								});
							</script>
						</div>
					</div>
					<div class="col-lg-6 col-md-12 pt-1 pb-3">
						<label for="compname" class="mb-1">ชื่อบริษัทในเครือ</label>
						<div style="border-bottom: 1px dotted #000;">
							&nbsp;&nbsp;<?=$obj_row_comp["comp_name"]?>
						</div>
						<input type="text" class="form-control d-none" name="compid" id="compid" value="<?=$obj_row_comp["comp_id"]?>" readonly>
					</div>
					<div class="col-lg-6 col-md-12 pt-1 pb-3">
						<label for="payaname" class="mb-1">ชื่อบริษัทผู้ให้บริการ</label>
						<?php
							$str_sql_recsel = "SELECT DISTINCT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE inv_statusPaym = 1 AND inv_NostatusPaym = '' AND inv_depid = '". $dep ."' GROUP BY inv_payaid";
							$obj_rs_recsel = mysqli_query($obj_con, $str_sql_recsel);
							$obj_row_recsel = mysqli_fetch_array($obj_rs_recsel);
							if (mysqli_num_rows($obj_rs_recsel) == 1) {
						?>
						<div style="border-bottom: 1px dotted #000;">
							&nbsp;&nbsp;<?=$obj_row_recsel["paya_name"]?>
						</div>
						<input type="text" class="form-control d-none" name="payaid" id="payaid" value="<?=$obj_row_recsel["paya_id"]?>" readonly>
						<?php } ?>
					</div>
					<div class="col-lg-12 col-md-12 pt-1 pb-3">
						<div class="row">
							<div class="col-md-1 pb-0">
								<label for="invdesc" class="mb-1">ชำระค่า</label>
							</div>
							<div class="col-lg-11 col-md-12">
								<div class="row">
									<div class="col-md-12" style="border-bottom: 1px dotted #000;">
										<style type="text/css">
											span.invdesc { display: inline;}
											span.invdesc:after { content: " || "; }
											span.invdesc:last-child:before { content: " "; }
											span.invdesc:last-child:after { content: " "; }
										</style>
									<?php 
										if ($countChk > 1) {
											for ($i = 1; $i <= $countChk; $i++) {
												$invid = "invid" . $i;
												$invid = $_GET["$invid"];
												$str_sql_desc = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE inv_id = '" . $invid . "'";
												$obj_rs_desc = mysqli_query($obj_con, $str_sql_desc);
												$obj_row_desc = mysqli_fetch_array($obj_rs_desc);
												$invdesc = $obj_row_desc["inv_description"];
									?>
												<?= $invdesc; ?>
												&nbsp;
												<span class="invdesc"></span>
												&nbsp;
									<?php 	
											} 
										} else { 
									?>
										
											<?= $obj_row["inv_description"]; ?>
									<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 pt-1 pb-3 row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-lg-3 col-md-3 pr-0">
									<label for="invno" class="mb-1">เลขที่ใบแจ้งหนี้</label>
								</div>
								<div class="col-lg-9 col-md-9">
									<div class="row">
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 25.78px; position: absolute; "></div>
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 52.56px; position: absolute;"></div>
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 79.34px; position: absolute;"></div>
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 106.12px; position: absolute;"></div>
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 132.90px; position: absolute;"></div>
										<div class="col-md-12" style="border-bottom: 1px dotted #000; margin-top: 159.68px; position: absolute;"></div>
										<div class="col-md-12 px-0">
											<style type="text/css">
												span.invIVNO { display: inline;}
												span.invIVNO:after { content: " || "; }
												span.invIVNO:last-child:before { content: " "; }
												span.invIVNO:last-child:after { content: " "; }
											</style>
											<?php 
												if ($countChk > 1) { 
												
													for ($i = 1; $i <= $countChk; $i++) { 
														$invid = "invid" . $i;
														$invid = $_GET["$invid"];
														$str_sql_ivno = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE inv_id = '" . $invid . "'";
														$obj_rs_ivno = mysqli_query($obj_con, $str_sql_ivno);
														$obj_row_ivno = mysqli_fetch_array($obj_rs_ivno);
														
														if ($obj_row_ivno["inv_type"] == 0) {
															$invno = $obj_row_ivno["inv_no"];
															$displayinvno = "display: block";
														} else {
															$invno = "";
															$displayinvno = "display: none";
														}
											?>
												<input type="text" class="form-control d-none" name="invno<?=$i;?>" id="invno<?=$i;?>" value="<?=$obj_row_ivno["inv_no"];?>">
												<input type="text" class="form-control d-none" name="invid<?=$i;?>" id="invid<?=$i;?>" value="<?=$obj_row_ivno["inv_id"];?>">
											
												<?= $invno; ?>
												&nbsp;
												<span class="invIVNO" style="<?= $displayinvno; ?>"></span>
												&nbsp;
											<?php 
													} 
												} else { 
													for ($i = 1; $i <= $countChk; $i++) { 
														$invid = "invid" . $i;
														$invid = $_GET["$invid"];
														$str_sql_ivno = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id WHERE inv_id = '" . $invid . "'";
														$obj_rs_ivno = mysqli_query($obj_con, $str_sql_ivno);
														$obj_row_ivno = mysqli_fetch_array($obj_rs_ivno);
														if ($obj_row_ivno["inv_type"] == 0) {
															$invno = $obj_row_ivno["inv_no"];
															$displayinvno = "display: block";
														} else {
															$invno = "";
															$displayinvno = "display: none";
														}
													}
											?>
												<input type="text" class="form-control d-none" name="invno<?=$countChk;?>" id="invno<?=$countChk;?>" value="<?=$invno;?>">
												<input type="text" class="form-control d-none" name="invid<?=$countChk;?>" id="invid<?=$countChk;?>" value="<?=$invid;?>">
												<?= $invno; ?>
												&nbsp;
												<span class="invIVNO" style="<?= $displayinvno; ?>"></span>
												&nbsp;
												
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invsub" class="mb-0">จำนวนเงิน :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
										<div class="col-lg-12 col-md-12 px-0 text-right">
											<?= number_format($invsubtotal,2); ?>
										</div>
										<?php } else { ?>
										<div class="col-lg-7 col-md-7 px-0 text-right">
											<?php if ($invsubNoVat == '0.00' && $invsubVat != '0.00') { ?>
											<?php } else if ($invsubNoVat != '0.00' && $invsubVat == '0.00') { ?>
											<?php } else if ($invsubNoVat != '0.00' && $invsubVat != '0.00') { ?>
												<?= number_format($invsubNoVat,2); ?>
												&nbsp;+&nbsp;
												<?= number_format($invsubVat,2); ?>
												&nbsp;=
											<?php } ?>
										</div>
										<div class="col-lg-5 col-md-5 px-0 text-right">
											<?= number_format($invsubtotal,2); ?>
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invvat" class="mb-0">ภาษีมูลค่าเพิ่ม :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
											<div class="col-lg-12 col-md-12 px-0 text-right">
												<?= number_format($invvat,2); ?>
											</div>
										<?php } else { ?>
											<?php if ($obj_row["inv_vatpercent"] == '0.00') { ?>
												<div class="col-lg-12 col-md-12 px-0 text-right" style="padding-top: 25px;"></div>
											<?php } else { ?>
												<div class="col-lg-7 col-md-7 px-0 text-left">
													<?= number_format($obj_row["inv_vatpercent"],2); ?>%
												</div>
												<div class="col-lg-5 col-md-5 px-0 text-right">
													<?= number_format($obj_row["inv_vat"] + $obj_row["inv_differencevat"],2); ?>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invtax1" class="mb-0">หักภาษี ณ ที่จ่าย :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
											<div class="col-lg-12 col-md-12 px-0 text-right">
												<?= number_format($invtaxtotal1,2); ?>
											</div>
										<?php } else { ?>
											<?php if ($obj_row["inv_taxpercent1"] == '0.00') { ?>
												<div class="col-lg-12 col-md-12 px-0 text-right" style="padding-top: 25px;"></div>
											<?php } else { ?>
												<div class="col-lg-7 col-md-7 px-0 text-left">
													<?= number_format($obj_row["inv_taxpercent1"],2); ?>%
												</div>
												<div class="col-lg-5 col-md-5 px-0 text-right">
													<?= number_format($obj_row["inv_taxtotal1"] + $obj_row["inv_differencetax1"],2); ?>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invtax2" class="mb-0" style="color: #FFF;">หักภาษี ณ ที่จ่าย :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
											<div class="col-lg-12 col-md-12 px-0 text-right">
												<?= number_format($invtaxtotal2,2); ?>
											</div>
										<?php } else { ?>
											<?php if ($obj_row["inv_taxpercent2"] == '0.00') { ?>
												<div class="col-lg-12 col-md-12 px-0 text-right" style="padding-top: 25px;"></div>
											<?php } else { ?>
												<div class="col-lg-7 col-md-7 px-0 text-left">
													<?= number_format($obj_row["inv_taxpercent2"],2); ?>%
												</div>
												<div class="col-lg-5 col-md-5 px-0 text-right">
													<?= number_format($obj_row["inv_taxtotal2"] + $obj_row["inv_differencetax2"],2); ?>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invtax3" class="mb-0" style="color: #FFF;">หักภาษี ณ ที่จ่าย :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
											<div class="col-lg-12 col-md-12 px-0 text-right">
												<?= number_format($invtaxtotal3,2); ?>
											</div>
										<?php } else { ?>
											<?php if ($obj_row["inv_taxpercent3"] == '0.00') { ?>
												<div class="col-lg-12 col-md-12 px-0 text-right" style="padding-top: 25px;"></div>
											<?php } else { ?>
												<div class="col-lg-7 col-md-7 px-0 text-left">
													<?= number_format($obj_row["inv_taxpercent3"],2); ?>%
												</div>
												<div class="col-lg-5 col-md-5 px-0 text-right">
													<?= number_format($obj_row["inv_taxtotal3"] + $obj_row["inv_differencetax3"],2); ?>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-12 pl-0 text-right">
									<label for="invtax1" class="mb-0">ยอดชำระสุทธิ :</label>
								</div>
								<div class="col-lg-8 col-md-12 text-right">
									<div class="row" style="border-bottom: 1px dotted #000">
										<?php if ($countChk > 1) { ?>
											<div class="col-lg-7 col-md-12 px-0 text-right">
												<?php if ($invdiff == '0.00') { ?>
													
												<?php } else { ?>
													<?= number_format($invgrand,2); ?>&nbsp;+&nbsp;
													<?= number_format($invdiff,2); ?>&nbsp;=
												<?php } ?>
											</div>
											<div class="col-lg-5 col-md-12 px-0 text-right">
												<?= number_format($invgrand,2); ?>
											</div>
										<?php } else { ?>
											<div class="col-lg-7 col-md-12 px-0 text-right">
												<?php if ($obj_row["inv_difference"] == '0.00') { ?>
													
												<?php } else { ?>
													<?= number_format($invgrand,2); ?>
													&nbsp;+&nbsp;
													<?= number_format($obj_row["inv_difference"],2); ?>&nbsp;=
												<?php } ?>
											</div>
											<div class="col-lg-5 col-md-12 px-0 text-right">
												<?= number_format($invgrand + $obj_row["inv_difference"],2); ?>
											</div>
										<?php } ?>
									</div>
								</div>
								<div class="col-lg-1 col-md-12">
									<b>บาท</b>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 pt-1 pb-3">
						<div class="table-responsive">
							<table class="table table-bordered border-black">
								<thead class="thead-light">
									<tr>
										<th class="text-center">รายการ</th>
										<th width="20%" class="text-center">เจ้าหนี้</th>
										<th width="20%" class="text-center">ลูกหนี้</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="tdborder-black black-left black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
									</tr>
									<tr>
										<td class="tdborder-black black-left black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
									</tr>
									<tr>
										<td class="tdborder-black black-left black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
									</tr>
									<tr>
										<td class="tdborder-black black-left black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
										<td class="tdborder-black black-bottom"></td>
									</tr>
									<tr>
										<td class="tdborder-black black-left"></td>
										<td class="tdborder-black"></td>
										<td class="tdborder-black"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 pt-1 pb-3">
						<div class="table-responsive">
							<table class="table" style="background-color: #e9ecef;">
								<tbody>
									<tr>
										<td width="50px" style="border-top: none; vertical-align: middle;"><b>หมายเหตุ</b></td>
										<td style="border-top: none;">
											<input type="text" class="form-control" name="paymNote" id="paymNote" autocomplete="off">
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 pt-1 pb-3">
						<div class="row px-1">
							<div class="col-md-3 col-sm-12 px-4">
								<div class="row mb-2">
									<div class="col-md-12 px-4 text-center" style="border-bottom: 1px dotted #000;">
										<span><?= $nameMdep != '' ? $nameMdep : '&nbsp;'?></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 text-center">
										<b>ฝ่ายอนุมัติ วันที่</b>
									</div>
									<div class="col-md-6 col-sm-12 text-center" style="border-bottom: 1px dotted #000;">
										<span><?= $dateMdep != '' ? $dateMdep : '&nbsp;'?></span>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-12 px-4">
								<div class="row mb-2">
									<div class="col-md-12 px-4 text-center" style="border-bottom: 1px dotted #000;">
										<?= $nameMgr != '' ? $nameMgr : '&nbsp;'?>
										<input type="text" class="form-control d-none" name="nameMgr" id="nameMgr" value="<?=$nameMgr;?>">
									</div>
								</div>
								<div class="row">
									<div class="col-md-7 text-center">
										<b>ผู้ตรวจจ่าย วันที่</b>
									</div>
									<div class="col-md-5 col-sm-12 text-center" style="border-bottom: 1px dotted #000;">
										<?= $dateMgr != '' ? $dateMgr : '&nbsp;'?>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-12 px-4">
								<div class="row mb-2">
									<div class="col-md-12 px-4 text-center" style="border-bottom: 1px dotted #000;">
										<?= $nameCEO != '' ? $nameCEO : '&nbsp;'?>
										<input type="text" class="form-control d-none" name="nameCEO" id="nameCEO" value="<?=$nameCEO;?>">
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 text-center">
										<b>ผู้อนุมัติ วันที่</b>
									</div>
									<div class="col-md-6 col-sm-12 text-center" style="border-bottom: 1px dotted #000;">
										<?= $dateCEO != '' ? $dateCEO : '&nbsp;'?>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-12 px-4">
								<div class="row mb-2" style="margin-top: 25px;">
									<div class="col-md-12 px-4 text-center" style="border-bottom: 1px dotted #000;">
										
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 text-center">
										<b>ผู้รับเงิน วันที่</b>
									</div>
									<div class="col-md-6 col-sm-12 text-center" style="border-bottom: 1px dotted #000;">
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row py-4 px-1 d-none" style="background-color: #FFFFFF">
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paympaydate" class="mb-1">วันทำจ่าย</label>
						<div class="input-group mb-2">
							<input type="date" class="form-control" name="paympaydate" id="paympaydate" value="" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymuseridcreate" class="mb-1">User ID Create</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymuseridcreate" id="paymuseridcreate" value="<?=$obj_row_user["user_id"];?>" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymcreatedate" class="mb-1">Create Date</label>
						<div class="input-group mb-2">
							<input type="datetime" class="form-control" name="paymcreatedate" id="paymcreatedate" value="" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymuseridedit" class="mb-1">User ID Edit</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymuseridedit" id="paymuseridedit" value="" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymeditdate" class="mb-1">Edit Date</label>
						<div class="input-group mb-2">
							<input type="datetime" class="form-control" name="paymeditdate" id="paymeditdate" value="" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paympayeename" class="mb-1">ชื่อผู้รับเงิน</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paympayeename" id="paympayeename" value="" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paympayeedate" class="mb-1">วันที่รับเงิน</label>
						<div class="input-group mb-2">
							<input type="date" class="form-control" name="paympayeedate" id="paympayeedate" value="" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymyear" class="mb-1">Year</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymyear" id="paymyear" value="" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymmonth" class="mb-1">Month</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymmonth" id="paymmonth" value="" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymrev" class="mb-1">Rev</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymrev" id="paymrev" value="0" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymfile" class="mb-1">ไฟล์</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymfile" id="paymfile" value="" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="paymchkRptpaym" class="mb-1">ChkReport</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="paymchkRptpaym" id="paymchkRptpaym" value="" readonly>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 pt-1 pb-3">
						<label for="countChk" class="mb-1">Record ที่เลือก</label>
						<div class="input-group mb-2">
							<input type="text" class="form-control" name="countChk" id="countChk" value="<?=$countChk;?>" readonly>
						</div>
					</div>
				</div>
				<div class="row py-4 px-1" style="background-color: #FFFFFF">
					<div class="col-md-12 pb-4 text-center">
						<a href="payment.php?cid=<?=$cid;?>&dep=<?=$dep;?>" type="button" class="btn btn-warning px-5 py-2 mx-1">ย้อนกลับ</a>
						<input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnInsert" id="btnInsert" value="บันทึก">
					</div>
				</div>
			</form>
		</div>
	</section>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#btnInsert").click(function() {
				var formData = new FormData(this.form);
				if($('#userlev').val() == '3' || $('#userlev').val() == '4') {
					if($('#nameCEO').val() == '') {
						if($('#paympaydate').val() == '') {
							swal({
								title: "กรุณากรอกวันที่ทำจ่าย",
								text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
								type: "warning"
							}).then(function() {
								frmAddPayment.paympaydate.focus();
							});
						} else {
							$.ajax({
								type: "POST",
								url: "r_payment_add.php",
								// data: $("#frmAddPayment").serialize(),
								data: formData,
								dataType: 'json',
								contentType: false,
								cache: false,
								processData:false,
								success: function(result) {
									if(result.status == 1) {
										window.location.href = result.url;
									} else {
										alert(result.message);
									}
								}
							});
						}
					} else {
						$.ajax({
							type: "POST",
							url: "r_payment_add.php",
							// data: $("#frmAddPayment").serialize(),
							data: formData,
							dataType: 'json',
							contentType: false,
							cache: false,
							processData:false,
							success: function(result) {
								if(result.status == 1) {
									window.location.href = result.url;
								} else {
									alert(result.message);
								}
							}
						});
					}
				} else {
					$.ajax({
						type: "POST",
						url: "r_payment_add.php",
						// data: $("#frmAddPayment").serialize(),
						data: formData,
						dataType: 'json',
						contentType: false,
						cache: false,
						processData:false,
						success: function(result) {
							if(result.status == 1) {
								window.location.href = result.url;
							} else {
								alert(result.message);
							}
						}
					});
				}
			});
		});
	</script>
	<?php include 'footer.php'; ?>
</body>
</html>
<?php } ?>