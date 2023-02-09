<?php
	
	header('Content-Type: application/json');


    if(!empty($_POST)) {

        include 'connect.php';

        $compid = $_POST["compid"];
        $depid = $_POST["depid"];
        $projid = $_POST["projid"];
        $CountChkAll = $_POST["CountChkAll"];


        $str_sql = "SELECT * FROM invoice_rcpt_desc_tb WHERE invrcptD_status = 1 AND invrcptD_irid = '' 
                    AND invrcptD_compid = '$compid' AND invrcptD_depid = '". $depid ."' AND invrcptD_projid = '". $projid ."'";
        $obj_rs = mysqli_query($obj_con, $str_sql);
        $i = 1;
        
        $ouput = "";
        $ouput .= "invoice_rcpt_project_merge.php?cid=" . $compid . "&dep=" . $depid . "&projid=" . $projid . "&countChk=" . $CountChkAll . "&";

        while ($obj_row = mysqli_fetch_array($obj_rs)) {
            $ouput .= "irDid".$i."=" . $obj_row["invrcptD_id"]."&";
            $i++;
        }

        $url = $ouput;

        if ($obj_rs) {
            echo json_encode(array('status' => '1','url'=> $url));
        } else {
            echo json_encode(array('status' => '0','message'=> $str_sql));
        }

        mysqli_close($obj_con);

    }

	

?>

