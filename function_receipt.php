<?php if (session_status() == PHP_SESSION_NONE) {    session_start();}$res = array();$res["data"] = array();$data = array();function get_bank(){    include 'connect.php';    $sql = "SELECT * FROM bank_tb";    $query = mysqli_query($obj_con,$sql);    while ($row = mysqli_fetch_assoc($query)) {        $data['bank_id'] = $row['bank_id'];        $data['bank_name'] = $row['bank_name'];        $res["data"][] = $data;    }    return $res;}function get_branch($obj){    include 'connect.php';    $id = $obj['id'];    $sql = "SELECT * FROM branch_tb WHERE brc_bankid = '$id'";    $query = mysqli_query($obj_con,$sql);    while ($row = mysqli_fetch_assoc($query)) {        $data['brc_id'] = $row['brc_id'];        $data['brc_name'] = $row['brc_name'];        $res["data"][] = $data;    }    return $res;}function save_pay($obj){    include 'connect.php';    $id = isset($obj['check_data']['re_id']) ? $obj['check_data']['re_id'] : '';    $re_typepay = isset($obj['check_data']['bySelPay']) ? $obj['check_data']['bySelPay'] : '';    $re_chequeno = isset($obj['check_data']['chequeNo']) ? $obj['check_data']['chequeNo'] : '';    $re_bankid = isset($obj['check_data']['SelBank']) ? $obj['check_data']['SelBank'] : '';    $re_branchid = isset($obj['check_data']['SelBranch']) ? $obj['check_data']['SelBranch'] : '';    $re_chequedate = isset($obj['check_data']['chequeDate']) ? $obj['check_data']['chequeDate'] : '';    $re_note = isset($obj['check_data']['ReNote']) ? $obj['check_data']['ReNote'] : '';    $sql = "UPDATE receipt_tb SET re_typepay = '$re_typepay', re_chequeno = '$re_chequeno', re_bankid = '$re_bankid', re_branchid = '$re_branchid'";        $sql .= $re_chequedate != '' ? ", re_chequedate = '$re_chequedate'": '';    $sql .= ", re_note = '$re_note' WHERE re_id = '$id'";        $query = mysqli_query($obj_con,$sql);    return true;    }?>