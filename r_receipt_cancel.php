<?php

	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]){  //check session

		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form

	}else{

		if(!empty($_POST)) {

			include 'connect.php';

            if(isset($_POST['check_data']['re_no']) || isset($_POST['re_no'])) {
                $Receiptno = isset($_POST['check_data']['re_no']) ? $_POST['check_data']['re_no'] : $_POST['re_no'];
            }
			if(isset($_POST['check_data']["re_id"]) || isset($_POST["re_id"])) {
				$Reid = isset($_POST['check_data']["re_id"]) ? $_POST['check_data']["re_id"] : $_POST["re_id"];
				$renote_cancel = isset($_POST['check_data']["ReNoteCancel"]) ? $_POST['check_data']["ReNoteCancel"] : '';
				// $sql_re_file = "SELECT re_file FROM receipt_tb WHERE re_id =$Reid";
				// $obj_re_file = mysqli_query($obj_con,$sql_re_file);
				// $obj_row_file = mysqli_fetch_assoc($obj_re_file);

				// $file_pointer = "./" . $obj_row_file["re_file"];

				// require_once __DIR__ . '/vendor/autoload.php';

				// if($compid == 'C014' || $compid == 'C009' || $compid == 'C015'){
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

                $sql_update_re = "UPDATE receipt_tb SET re_stsid = 'STS003',
										 re_note_cancel = '$renote_cancel'
									     WHERE re_id ='$Reid'";
                $re_update = mysqli_query($obj_con,$sql_update_re);
			}

			if(isset($_POST['check_data']["ir_id"]) || isset($_POST["ir_id"])) {
				$irID = isset($_POST['check_data']["ir_id"]) ? $_POST['check_data']["ir_id"] : $_POST["ir_id"];

                $sql_query = "SELECT invrcpt_balancetotal FROM invoice_rcpt_tb WHERE invrcpt_id = '$irID'";
                $obj_query = mysqli_query($obj_con,$sql_query);
                $obj_row = mysqli_fetch_assoc($obj_query);

                $amount = (float)$obj_row['invrcpt_balancetotal'] + (float)$_POST['check_data']['_val'];             

                $sql_update_invc = "UPDATE invoice_rcpt_tb SET invrcpt_stsid = 'STS001',invrcpt_reid = '', invrcpt_balancetotal = '$amount' WHERE invrcpt_id = '$irID'";
                $invc_update = mysqli_query($obj_con,$sql_update_invc);
			}

			if($invc_update || $re_update) {
				echo json_encode(array('status' => '1','Receiptno'=> $Receiptno));
			} else {
				echo json_encode(array('status' => '0','message'=> 'Error'));
			}

			mysqli_close($obj_con);

		}

	}

?>