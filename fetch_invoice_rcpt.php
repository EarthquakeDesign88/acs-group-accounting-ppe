<?php
    include 'connect.php';
    include 'config/config.php'; 
    __check_login();
    $limit = '10';
    $pageno = 1;

    $page = isset($_POST['page']) ? $_POST['page'] : "";
    if($page > 1) {
        $start = (($page - 1) * $limit);
        $pageno = $page;
    } else {
        $start = 0;
    }
    $str_sql = "SELECT * FROM invoice_rcpt_tb AS i INNER JOIN customer_tb AS cust ON i.invrcpt_custid = cust.cust_id INNER JOIN company_tb AS c ON i.invrcpt_compid = c.comp_id INNER JOIN department_tb AS d ON i.invrcpt_depid = d.dep_id INNER JOIN status_tb AS s ON i.invrcpt_stsid = s.sts_id WHERE invrcpt_stsid in('STS001','STS003') AND invrcpt_compid = '". $_POST['queryComp'] ."' AND invrcpt_depid = '". $_POST['queryDep'] ."' ";
    if($_POST['querySearch'] != '') {
        if($_POST['query'] != '') {
            $str_sql .= ' AND '. $_POST['querySearch'] .' LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';
        }
    }
    $str_sql .= " ORDER BY invrcpt_no DESC ";
    $filter_query = $str_sql . ' LIMIT '.$start.', '.$limit.'';
    // echo $filter_query;
    $obj_query = mysqli_query($obj_con, $str_sql);
    $total_data = mysqli_num_rows($obj_query);
    $obj_query = mysqli_query($obj_con, $filter_query);
    $obj_row = mysqli_fetch_array($obj_query);
    $total_filter_data = mysqli_num_rows($obj_query);
    
    $output = '<table class="table mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="15%">เลขที่ใบแจ้งหนี้</th>
                            <th width="40%">รายละเอียด</th>
                            <th width="15%" class="text-center">จำนวนเงิน</th>
                            <th width="5%" class="text-center">ฝ่าย</th>
                            <th width="10%" class="text-center">สถานะ</th>
                            <th width="15%" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>';
    $html_modal_detail = '';
    if($total_data > 0) {
        $i = 1;
        $total = 0;
        foreach($obj_query as $obj_row) {
            $total = $total + $obj_row["invrcpt_grandtotal"];
            if ($obj_row["invrcpt_stsid"] == 'STS001') {
                $bg = 'color: #F00; text-align: center; font-size: 15px; font-weight: 700;';
                $text = 'ค้างจ่าย';
            }else if($obj_row["invrcpt_stsid"] == 'STS003'){
                $bg = 'color: #F00; text-align: center; font-size: 15px; font-weight: 700;';
                $text = 'ยกเลิก';
            }
            if ($obj_row["invrcpt_book"] == '') {
                $invoiceNo = $obj_row["invrcpt_no"];
            } else {
                $invoiceNo = $obj_row["invrcpt_book"].'/'.$obj_row["invrcpt_no"];
            }
            $pathFile = $obj_row["invrcpt_file"];
            if ($obj_row['invrcpt_description1'] == '') {
                $desc = $obj_row['invrcpt_description2'];
            } else {
                $desc = $obj_row['invrcpt_description1'];
            }
            
            $grandtotal = ($obj_row["invrcpt_balancetotal"] + ($obj_row["invrcpt_balancetotal"] * $obj_row["invrcpt_vatpercent"]) / 100) + $obj_row["invrcpt_differencevat"] + $obj_row["invrcpt_differencegrandtotal"];
    //number_format($grandtotal,2) 96
    
            
            //** PDF**//    
            $url_pdf = "export/invoice_rcpt_pdf.php?irID=".$obj_row["invrcpt_id"];
            $html_modal_detail .= '
                <div id="modalDetailInvoiceReceipt_'.$obj_row["invrcpt_id"].'" class="modal fade modal-detail" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title py-2 '.(($obj_row["invrcpt_stsid"] == "STS003") ? " text-danger ": "").'"><i class="icofont-search-document"></i> เลขที่ใบแจ้งหนี้(รายรับ) (เลขที่ '.$obj_row["invrcpt_no"].' '.(($obj_row["invrcpt_stsid"] == "STS003") ? " #ยกเลิก ": "").')</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                            </div>
                            <div class="modal-body">';
                            
                            if($obj_row["invrcpt_stsid"] == "STS003"){
                                $html_modal_detail .= '<div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            <b>ยกเลิก</b><br>
                                          </div>
                                     </div>
                                 </div>';
                            }
                            
                 $html_modal_detail .= '<div class="row">
                                    <div class="col-md-12">
                                         <object data="'.$url_pdf.'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="600" style="border: 1px solid"></object>
                                     </div>
                                 </div>
                                 <div class="col-lg-12 px-1" style="border:2px dashed #d6d8db;margin-top:10px;padding:8px;font-size:13px">
                                    <div class="col-md-12">
                                         <h5><b><i class="icofont-ui-clock"></i> ประวัติการทำรายการ</b></h5>
                                        <div class="row mt-2">
                                              <div class="col-md-12 media"><b><i class="icofont-ui-add"></i> จัดทำเอกสาร โดย : </b> '.$obj_row["invrcpt_userid_create"].' &nbsp;&nbsp;<b> วันเวลา : </b> '.__datetime($obj_row["invrcpt_createdate"]).'</div>
                                               <div class="col-sm-12"><b><i class="icofont-ui-edit"></i> แก้ไขเอกสาร โดย : </b> 
                                               '. ((!empty($obj_row["invrcpt_userid_edit"])) ? $obj_row["invrcpt_userid_edit"].' &nbsp;&nbsp;<b> วันเวลา :</b> '.__datetime($obj_row["invrcpt_editdate"]) : "-").'
                                             </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal" aria-hidden="true"><i class="icofont-close"></i> ปิด</button>
                         </div>
                    </div>
                 </div>
                </div>';
             //**END MODAL PDF**//

            $output .= '<tr>';
                            $output .= '<td class="text-nowrap">
                                <p>'. $invoiceNo .'</p>
                            </td>';
                        
                $output .= '
                            <td>
                                <div class="truncate">
                                    <b>บริษัท : </b> '. $obj_row['cust_name'] .'<br>
                                    <b>รายการ : </b> '. $desc .'
                                </div>
                            </td>       
                            <td class="text-right">
                                <input type="text" class="form-control text-right numInvNet" value="'. number_format($obj_row["invrcpt_balancetotal"],2) .'" readonly>
                                <input type="text" class="form-control text-right d-none numInvNet" value="'. $grandtotal .'" readonly>
                            </td>
                            <td class="text-center text-nowrap">
                                '. $obj_row['dep_name'] .'
                            </td>
                            <td class="text-center" style="'. $bg .'">
                                '. $text .'
                            </td>';
                            if($obj_row["invrcpt_stsid"] != 'STS003'){
                                $output .='<td>
                                <div class="btn-group btn-group-toggle mb-1" style="width: 100%">
                                    <a href="invoice_rcpt_edit.php?cid='. $obj_row['comp_id'] .'&dep='. $obj_row["invrcpt_depid"] .'&m='. $obj_row['invrcpt_month'] .'&projid='. $obj_row['invrcpt_projid'] .'&irID='. $obj_row['invrcpt_id'] .'" class="btn btn-warning edit_data" type="button" name="edit" title="แก้ไข / Edit">
                                        <i class="icofont-edit"></i>
                                    </a>';
                                // if($obj_row["invrcpt_file"] != '') {
                                //     $output .= '
                                //     <a href="./'.$pathFile.'?v='.microtime(true).'" target="blank" class="btn btn-primary" name="view" title="ดู / View">
                                //         <i class="icofont-eye-alt"></i>
                                //     </a>
                                //     ';
                                // }
                                // else {
                                    $output .=' 
                                    <a class="btn btn-primary" name="view" id="'.$obj_row["invrcpt_id"].'" data-type="'.$obj_row["invrcpt_projid"].'" data-toggle="modal" data-target="#modalDetailInvoiceReceipt_'.$obj_row["invrcpt_id"].'" title="ดู / View">
                                        <i class="icofont-eye-alt"></i>
                                    </a>
                                    <input type="hidden" id="proj_id" value="'.$obj_row['invrcpt_projid'].'">
                                    ';
                                // }
                                // <button class="btn btn-danger delete_data" type="button" name="delete" id="'. $obj_row['invrcpt_id'] .'" title="ลบ / Delete">
                                //         <i class="icofont-ui-delete"></i>
                                //     </button>
                                $output .= '
                                </div>
                                <a href="invoice_rcpt_copy.php?cid='. $obj_row['comp_id'] .'&dep='. $obj_row["invrcpt_depid"] .'&projid='. $obj_row['invrcpt_projid'] .'&irID='. $obj_row['invrcpt_id'] .'" class="btn btn-success form-control mb-1" title="คัดลอก / Copy">
                                    <i class="icofont-copy"></i>&nbsp;&nbsp;คัดลอก
                                </a>';
                                
                                    $output .= '<a data-type="'. $obj_row['invrcpt_id'] .'" id="'. $obj_row['invrcpt_no'] .'" class="btn btn-danger form-control mb-1 _cancel" title="ยกเลิก / Cancel">
                                    <i class="icofont-ui-delete"></i>&nbsp;&nbsp;ยกเลิก
                                        </a><a href="receipt_add.php?cid='. $obj_row['comp_id'] .'&dep='. $obj_row["invrcpt_depid"] .'&projid='. $obj_row['invrcpt_projid'] .'&irID='. $obj_row['invrcpt_id'] .'" class="btn btn-primary form-control mb-1" title="เพิ่ม / Add">
                                        <i class="icofont-plus-circle"></i>&nbsp;&nbsp;ใบเสร็จรับเงิน
                                    </a>';
                                
                                $output .= '</td>';
                            } else{
                                $output .= '<td>
                                    <a id="'.$obj_row["invrcpt_id"].'" data-type="'.$obj_row["invrcpt_projid"].'"  data-toggle="modal" data-target="#modalDetailInvoiceReceipt_'.$obj_row["invrcpt_id"].'" class="btn btn-primary form-control mb-1" name="view" title="ดู / View">
                                    <i class="icofont-eye-alt"></i>&nbsp;&nbsp;View</a>
                                </td>';
                            }
                        $output .= '</tr>
                        <tr class="d-none">
                            <td colspan="2"></td>
                            <td class="text-right">
                                <input type="text" class="form-control text-right" value="'. $total .'" readonly>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
            $i++;
        }
    } else {
        $output .= '</tbody>
                    <tbody>
                        <tr width="100%">
                            <td colspan="6" align="center">ไม่มีข้อมูลใบแจ้งหนี้</td>
                        </tr>
                    </tbody>';
    }
    $total_links = ceil($total_data/$limit);
    $previous_link = '';
    $next_link = '';
    $page_link = '';
    $output .= '</table>
                <div class="row mx-0 my-0">
                    <div class="col-md-6 px-0 py-4">
                        <ul class="pagination">';
                        
    $page_array = array();
    if($total_links > 4) {
        if($pageno < 5) {
            for($count = 1; $count <= 5; $count++) {
                $page_array[] = $count;
            }
            $page_array[] = '...';
            $page_array[] = $total_links;
        } else {
            $end_limit = $total_links - 5;
            if($pageno > $end_limit) {
                $page_array[] = 1;
                $page_array[] = '...';
                for($count = $end_limit; $count <= $total_links; $count++) {
                    $page_array[] = $count;
                }
            } else {
                $page_array[] = 1;
                $page_array[] = '...';
                for($count = $pageno - 1; $count <= $pageno + 1; $count++) {
                    $page_array[] = $count;
                }
                $page_array[] = '...';
                $page_array[] = $total_links;
            }
        }
    } else {
        for($count = 1; $count <= $total_links; $count++) {
            $page_array[] = $count;
        }
    }
    for($count = 0; $count < count($page_array); $count++) {
        if($pageno == $page_array[$count]) {
            $page_link .= '<li class="page-item active">
                            <a class="page-link" href="#"> '.$page_array[$count].' <span class="sr-only">(current)</span></a>
                        </li> ';
            $previous_id = $page_array[$count] - 1;
            if($previous_id > 0) {
                $previous_link = '<li class="page-item">
                                    <a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">
                                        <i class="icofont-rounded-left"></i>
                                    </a>
                                </li>';
            } else {
                $previous_link = '<li class="page-item disabled">
                                    <a class="page-link" href="#">
                                        <i class="icofont-rounded-left"></i>
                                    </a>
                                </li>';
            }
            $next_id = $page_array[$count] + 1;
            if($next_id > $total_links) {
                $next_link = '<li class="page-item disabled">
                                <a class="page-link" href="#">
                                    <i class="icofont-rounded-right"></i>
                                </a>
                            </li>';
            } else {
                $next_link = '<li class="page-item">
                                <a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">
                                    <i class="icofont-rounded-right"></i>
                                </a>
                            </li>';
            }
        } else {
            if($page_array[$count] == '...') {
                $page_link .= '<li class="page-item disabled">
                                <a class="page-link" href="#">...</a>
                            </li> ';
            } else {
                $page_link .= '<li class="page-item">
                                <a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a>
                            </li>';
            }
        }
    }
    $output .= $previous_link . $page_link . $next_link;
            $output .= '</ul>
                    </div>
                    <div class="col-md-6 px-0 py-4 text-right">
                        <b>จำนวนใบแจ้งหนี้ทั้งหมด : </b>
                        <span id="numINV">'.$total_data.'</span>
                        <input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
                    </div>
                </div>';
    echo $html_modal_detail;
    echo $output;
?>