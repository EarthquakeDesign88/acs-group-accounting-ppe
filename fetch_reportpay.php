<?php
include 'config/config.php'; 
__check_login();


$keyword = isset($_POST['s_keywords']) ? $_POST['s_keywords'] : "";
$search = isset($_POST['s_searchby']) ? $_POST['s_searchby'] : "";
$depid = isset($_POST['dep']) ? $_POST['dep'] : "";
$dt = isset($_POST['dt']) ? $_POST['dt'] : "";
$df = isset($_POST['df']) ? $_POST['df'] : "";
$pagenumber = (!empty($_POST["page"])) ? $_POST["page"] : 1;  

$limit_row= 10;
$start_row = ($pagenumber-1)*$limit_row;

$sql = "SELECT * FROM payment_tb p LEFT JOIN invoice_tb i ON p.paym_id = i.inv_paymid 
                                   LEFT JOIN payable_tb py ON i.inv_payaid = py.paya_id
                                   WHERE paym_depid = '$depid'";

$sql .= $search != "" ? " AND paym_no LIKE '%$search%'" : "";
$sql .= $keyword != "" ? " AND paym_paystatus = '$keyword'" : "";
$sql .= $dt != "" && $df != "" ? " AND DATE(paym_updated_paidstatus) BETWEEN '$dt' AND '$df'" : "";
$sql .= $dt != "" && $df == "" ? " AND DATE(paym_updated_paidstatus) <= '$dt'" : "";
$sql .= $dt == "" && $df != "" ? " AND DATE(paym_updated_paidstatus) >= '$df' AND '$df'" : "";

$sql_filters =  $sql. " limit " . $start_row . "," . $limit_row;
$db = new database();
$total_row = $db->query($sql)->num_rows();
$query = $db->query($sql_filters);
$obj_query = $query->result();

$output = '';
$head = '';
$footer = '';

$totalAmount = 0;
if($keyword == "paid"){
    $head .= '<tr>
                <th width="15%" class="text-center">เลขที่ใบสำคัญจ่าย</th>
                <th width="20%">รายละเอียด</th>
                <th width="10%" class="text-center">จำนวนเงิน</th>
                <th width="15%" class="text-center">สถานะทำจ่าย</th>
                <th width="15%" class="text-center">วันที่ทำจ่าย</th>
                <th width="15%" class="text-center">รายชื่อผู้ทำจ่าย</th>
            </tr>';

            foreach($obj_query as $row){
                $totalAmount += $row['inv_netamount'];
                $payStatus = $row['paym_paystatus'] == "paid" ? "จ่ายแล้ว" : "รอจ่าย";
                $output .= '<tr>
                                <td class="text-center">'.$row['paym_no'].'</td>
                                <td>
                                    <div class="truncate-des-paid">
                                        <b>บริษัท : </b>'.$row["paya_name"].'<br>
                                        <b>รายการ : </b>'.$row["inv_description"].'
                                    </div>
                                </td>
                                <td class="text-center">'.__price($row['inv_netamount']).'</td>
                                <td class="text-success text-center">'.$payStatus.'</td>
                                <td class="text-center">'.__date($row['paym_updated_paidstatus']).'</td>
                                <td class="text-center">'.$row['paym_userid_updatedpaidstatus'].'</td>
                            </tr>';
            }
            $output .= '<tr>
                            <td colspan="5"><b class="text-success">ยอดรวมทำจ่าย: '.__price($totalAmount).'</b></td>
                        </tr>';
    $footer .= '';

}else if($keyword == "waiting to pay"){
    $head .= '<tr>
                <th width="20%" class="text-center">เลขที่ใบสำคัญจ่าย</th>
                <th width="40%">รายละเอียด</th>
                <th width="20%" class="text-center">จำนวนเงิน</th>
                <th width="20%" class="text-center">สถานะทำจ่าย</th>
            </tr>';

            foreach($obj_query as $row){
                $totalAmount += $row['inv_netamount'];
                $payStatus = $row['paym_paystatus'] == "paid" ? "จ่ายแล้ว" : "รอจ่าย";
                $output .= '<tr>
                                <td class="text-center">'.$row['paym_no'].'</td>
                                <td>
                                    <div class="truncate-des-waiting">
                                        <b>บริษัท : </b>'.$row["paya_name"].'<br>
                                        <b>รายการ : </b>'.$row["inv_description"].'
                                    </div>
                                </td>
                                <td class="text-center">'.__price($row['inv_netamount']).'</td>
                                <td class="text-warning text-center">'.$payStatus.'</td>
                            </tr>';
            }
            $output .= '<tr>
                            <td colspan="5"><b class="text-warning">ยอดรวมรอจ่าย: '.__price($totalAmount).'</b></td>
                        </tr>';
    $footer .= '';
}

$pagination = __pagination($total_row,$limit_row,$pagenumber);

echo json_encode(array('data'=>$output,'page'=>$pagination,'head'=>$head));

?>