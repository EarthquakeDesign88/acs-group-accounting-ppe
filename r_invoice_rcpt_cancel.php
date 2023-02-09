<?php

	header('Content-Type: application/json');
	session_start();

	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(!empty($_POST)) {

			include 'connect.php';

            if(isset($_POST['compid'])){
                $compid = $_POST['compid'];
            }

            if(isset($_POST['depid'])){
                $depid = $_POST['depid'];
            }

            if(isset($_POST['irID'])) {
                $irID = $_POST['irID'];
				$invoiceRcpt = $_POST['irno'];
				
				$sql__invc_project = "UPDATE invoice_rcpt_desc_tb SET invrcptD_status = '0',invrcptD_irid = '' WHERE invrcptD_irid = '$irID'";
				$invc_project = mysqli_query($obj_con,$sql__invc_project);

				// $sql_invc_file = "SELECT invrcpt_file FROM invoice_rcpt_tb WHERE invrcpt_id =$irID";
				// $obj_invc_file = mysqli_query($obj_con,$sql_invc_file);
				// $obj_row_file = mysqli_fetch_assoc($obj_invc_file);

				// $file_pointer = "./" . $obj_row_file["invrcpt_file"];

				// require_once __DIR__ . '/vendor/autoload.php';
				
				// if($compid == "C009" || $compid == "C015" || $compid == "C014"){
				// 	$mpdf = new \Mpdf\Mpdf([
				// 		'format' => 'A4',
				// 	]);
				// }else{
				// 	$mpdf = new \Mpdf\Mpdf([
				// 		'format' => 'A5-L',
				// 	]);
				// }

				// $pagecount = $mpdf->setSourceFile($file_pointer);
				// $mpdf->SetWatermarkImage('./image/pdf/img_cancel.png');
				// $tplId = $mpdf->importPage(1);
				// $mpdf->UseTemplate($tplId);
				// $mpdf->showWatermarkImage = true;
				// $mpdf->Output($file_pointer);

                $sql_update_invc = "UPDATE invoice_rcpt_tb SET invrcpt_stsid = 'STS003' WHERE invrcpt_id = '$irID'";
                $invc_update = mysqli_query($obj_con,$sql_update_invc);
            }

			if($invc_update) {
				echo json_encode(array('status' => '1','compid'=> $compid,'depid'=> $depid, 'invoiceRcpt' =>  $invoiceRcpt));
			} else {
				echo json_encode(array('status' => '0','message'=> 'Error'));
			}

			mysqli_close($obj_con);

		}

	}

?>