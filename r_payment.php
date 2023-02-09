<?php 
	header('Content-Type: application/json');
	session_start();
	if (!$_SESSION["user_name"]) {  //check session
		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
	} else {
		if(!empty($_POST)) {
			include 'connect.php';
			$cid = $_POST["compid"];
			$dep = $_POST["depid"];
			$CountChkAll = $_POST["CountChkAll"];
			$countChk = 0;
			// if ($dep == 'D003' || $dep == 'D004' || $dep == 'D016') {
			// 	$str_sql_Chk = "SELECT * FROM invoice_tb AS i 
			// 					INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
			// 					INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
			// 					INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id 
			// 					WHERE inv_statusMgr = 0 AND inv_apprMgrno = '' AND inv_paymid = '' AND inv_compid = '". $cid ."' AND inv_depid = '".$dep."' 
			// 					ORDER BY inv_id DESC";
			// } else {
				$str_sql_Chk = "SELECT * FROM invoice_tb AS i 
								INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
								INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
								INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id 
								WHERE inv_statusMgr = 1 AND inv_apprMgrno <> '' AND inv_paymid = '' AND inv_compid = '". $cid ."' AND inv_depid = '".$dep."' 
								ORDER BY inv_id DESC";
			// }
			$obj_rs_Chk = mysqli_query($obj_con, $str_sql_Chk);
			while($obj_row_Chk = mysqli_fetch_array($obj_rs_Chk)) {
				$countChk++;
			}
			for ($m = 1; $m <= $countChk; $m++) {
				$invpayaid = 'invpayaid'. $m;
				$invpayaid = $_POST["$invpayaid"];
				// echo "Paya : " . $invpayaid . "<br>";
				$stsPaym = 'stsPaym' . $m;
				$stsPaym = $_POST["$stsPaym"];
				// echo "stsPaym : " . $stsPaym . "<br>";
			}
			// if ($dep == 'D003' || $dep == 'D004' || $dep == 'D016') {
			// 	$str_sql_paya = "SELECT * FROM invoice_tb AS i 
			// 					INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
			// 					INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
			// 					INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id 
			// 					WHERE inv_statusPaym = 1 AND inv_statusMgr = 0 AND inv_apprMgrno = '' AND inv_paymid = '' AND inv_compid = '". $cid ."' AND inv_depid = '".$dep."' 
			// 					ORDER BY inv_id DESC";
			// } else {
				$str_sql_paya = "SELECT * FROM invoice_tb AS i 
								INNER JOIN payable_tb AS p ON i.inv_payaid = p.paya_id 
								INNER JOIN department_tb AS d ON i.inv_depid = d.dep_id 
								INNER JOIN user_tb AS u ON i.inv_userid_create = u.user_id 
								WHERE inv_statusPaym = 1 AND inv_statusMgr = 1 AND inv_apprMgrno <> '' AND inv_paymid = '' AND inv_compid = '". $cid ."' AND inv_depid = '".$dep."' 
								ORDER BY inv_id DESC";
			// }
			$obj_rs_paya = mysqli_query($obj_con, $str_sql_paya);
			$countSel = 0;
			$i = 0;
			$ouput = "";
			$ouput .= "payment_add.php?cid=" . $cid . "&dep=" . $dep . "&countChk=" . $CountChkAll . "&";
			$urlinv = "";
			while ($obj_row_paya = mysqli_fetch_array($obj_rs_paya)) {
				
				$countSel++;
				$i++;
                
				$urlinv .= 'invid' . $i . '=' . $obj_row_paya["inv_id"] . "&";
			}
			$ouput .= $urlinv;
			$ouput .= "stsPaym=1&nostsPaym=";
			$url = $ouput;
            
            if($urlinv!=""){
            	if ($obj_rs_paya) {
    				echo json_encode(array('status' => '1','url'=> $url));
    
    			} else {
    
    				echo json_encode(array('status' => '0','message'=> $str_sql));
    
    			}
            }else{
              echo json_encode(array('status' => '4','message'=> "กรุณาเลือกใบแจ้งหนี้ใหม่อีกครั้ง"));
            }
		
			mysqli_close($obj_con);
		}
	}
?>