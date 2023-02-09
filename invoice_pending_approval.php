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


$html_title = '<i class="icofont-papers"></i>  รายงาน  <i class="icofont-caret-right"></i> <u>ใบแจ้งหนี้รอตรวจสอบ</u>';
$icon = '<i class="icofont-paper"></i>';
$sql = " invoice_tb AS i ";
$con_where = " AND   inv_statusMgr = 0 ";

$html_dep_box = __html_dep_box($html_title,"invoice_pending_approval.php",$icon,$con_where,$arrDepAll,$sql,"i.inv_depid");
__page_seldep($html_dep_box);
?>
<?php
   if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
   
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


        $str_sql = "SELECT * FROM invoice_tb AS i INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
                                                  INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
                                                  INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id 
                                                  WHERE inv_apprMgrno = '' AND inv_depid = '" . $dep . "' AND inv_compid = '" . $cid . "' ORDER BY inv_id DESC";
        $obj_rs = mysqli_query($obj_con, $str_sql);

        $totalsApproval = mysqli_num_rows($obj_rs);
        

        //Fiunction
        function MonthThaiShort($strDate) {
            $strYear = substr(date("Y",strtotime($strDate))+543,-2);
            $strMonth= date("n",strtotime($strDate));
            $strDay= date("j",strtotime($strDate));
            $strHour= date("H",strtotime($strDate));
            $strMinute= date("i",strtotime($strDate));
            $strSeconds= date("s",strtotime($strDate));
            $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
            $strMonthThai = $strMonthCut[$strMonth];
            return "$strDay $strMonthThai $strYear";
        }
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
            <form id="ivPendingApprovalForm" action="invoice_pending_approval_pdf.php?cid=<?=$cid?>&dep=<?=$dep?>" method="POST">
				<div class="row py-4 px-1" style="background-color: #ffdd77">
					<div class="col-md-10 pb-4">
						<h3 class="mb-0">
                            <i class="icofont-papers"></i> รายงาน <u>ใบแจ้งหนี้รอตรวจสอบ</u> <span class="badge bg-light"><?=$totalsApproval?></span>
						</h3>
					</div>

                    <div class="col-md-2 pb-2">
                        <?php if($cid == 'C001') {?>
                            <button onclick="window.history.go(-1); return false;" class="btn btn-info float-left">ย้อนกลับ</button>
                        <?php }?>
                    </div>

					<div class="col-md-12 d-none">
						<input type="text" class="form-control" name="compid" id="compid" value="<?=$_GET["cid"];?>">
						<input type="text" class="form-control" name="depid" id="depid" value="<?=$_GET["dep"];?>">
					</div>
				</div>

                <div class="row py-4 px-2">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th width="20%">เลขที่ใบแจ้งหนี้</th>
                                <th width="38%">รายละเอียด</th>
                                <th width="12%" class="text-center">วันที่ครบชำระ</th>
                                <th width="15%" class="text-center">จำนวนเงิน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $obj_rs = mysqli_query($obj_con, $str_sql);
                                $i = 1;
                                $subtotal = 0;
                                $tax1 = 0;
                                $tax2 = 0;
                                $tax3 = 0;
                                $netamount = 0;

                                while ($obj_row = mysqli_fetch_array($obj_rs)) { 
                                    $subtotal = $obj_row["inv_subtotal"] + $obj_row["inv_vat"] + $obj_row["inv_differencevat"];
                                    $tax1 = (($obj_row["inv_tax1"] * $obj_row["inv_taxpercent1"]) / 100) + $obj_row["inv_differencetax1"];
                                    $tax2 = (($obj_row["inv_tax2"] * $obj_row["inv_taxpercent2"]) / 100) + $obj_row["inv_differencetax2"];
                                    $tax3 = (($obj_row["inv_tax3"] * $obj_row["inv_taxpercent3"]) / 100) + $obj_row["inv_differencetax3"];
                                    $netamount = $obj_row["inv_subtotalNoVat"] + $subtotal + $obj_row["inv_difference"] - $tax1 - $tax2 - $tax3;
                                ?>
                                    <tr>
                                        <td> <?= $obj_row['inv_no'] ?></td>
                                        <td>
                                            <b>บริษัท : </b> <?= $obj_row['paya_name'] ?> <br>
                                            <b>รายการ : </b> <?= $obj_row['inv_description'] ?>
                                        </td>
                                        <td><?= MonthThaiShort($obj_row['inv_duedate']) ?></td>
                                        <td><span class="float-right"><?= number_format($netamount, 2) ?></span></td>
                                    </tr>
                            <?php $i++; }?>
                        </tbody>
                    </table>
                </div>
                

				<div class="row pb-5 px-1" style="background-color: #FFFFFF;">
					<div class="col-md-12 text-center mb-4">
                        <input type="submit" class="btn btn-danger px-5 py-2 mx-1" name="exportPDF" id="exportPDF" value="Export PDF"></input>
					</div>
				</div>

			</form>

		</div>
	</section>


	<script type="text/javascript">
		$(document).ready(function() {
			$('#ivPendingApprovalForm').submit(function(){
                $('#exportPDF').val("Exporting...");
            });
		});
	</script>


	<?php include 'footer.php'; ?>
 
</body>
</html>
<?php } ?>