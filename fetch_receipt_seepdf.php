<?php
    
    include 'connect.php';
    $limit = '10';
    $pageno = 1;
    if($_POST['page'] > 1) {
        $start = (($_POST['page'] - 1) * $limit);
        $pageno = $_POST['page'];
    } else {
        $start = 0;
    }
    // if ($_POST['queryRefir'] == 0) {
        // $str_sql = "SELECT * FROM receipt_tb AS r 
        //          INNER JOIN invoice_rcpt_tb AS i ON r.re_id = i.invrcpt_reid 
        //          INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
        //          INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
        //          INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
        //          WHERE re_depid = '". $_POST['queryDep'] ."' AND re_year = '". $_POST['queryYear'] ."' AND re_month = '". $_POST['queryMonth'] ."' AND re_file <> '' ";
    // } else {
        $str_sql = "SELECT * FROM receipt_tb AS r 
                    INNER JOIN company_tb AS c ON r.re_compid = c.comp_id 
                    INNER JOIN customer_tb AS cust ON r.re_custid = cust.cust_id 
                    INNER JOIN department_tb AS d ON r.re_depid = d.dep_id 
                    WHERE re_depid = '". $_POST['queryDep'] ."' AND re_year = '". $_POST['queryYear'] ."' AND re_month = '". $_POST['queryMonth'] ."' ";
    // }
    $str_sql .= ' ORDER BY re_no DESC ';
    $filter_query = $str_sql . 'LIMIT '.$start.', '.$limit.'';
    // echo $filter_query;
    $obj_query = mysqli_query($obj_con, $str_sql);
    $total_data = mysqli_num_rows($obj_query);
    $obj_query = mysqli_query($obj_con, $filter_query);
    $obj_row = mysqli_fetch_array($obj_query);
    $total_filter_data = mysqli_num_rows($obj_query);
    $year = $_POST['queryYear'];
    $month = $_POST['queryMonth'];
    $output = '<table class="table mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="25%">เลขที่ใบเสร็จรับเงิน</th>
                            <th width="45%">รายละเอียด</th>
                            <th width="10%" class="text-center">ฝ่าย</th>
                            <th width="20%"></th>
                        </tr>
                    </thead>
                    <tbody>';
    if($total_data > 0) {
        $i = 1;
        $total = 0;
        foreach($obj_query as $obj_row) {
            if ($obj_row["re_bookno"] == '') {
                $ReNo = $obj_row["re_no"];
            } else {
                $ReNo = $obj_row["re_bookno"].'/'.$obj_row["re_no"];
            }
            $pathFile = $obj_row["re_file"];
            if ($obj_row["re_refinvrcpt"] == 0) {
                $str_sql_inv = "SELECT * FROM invoice_rcpt_tb AS i INNER JOIN receipt_tb AS r ON i.invrcpt_reid = r.re_id WHERE invrcpt_reid = '". $obj_row["re_id"] ."'";
                $obj_rs_inv = mysqli_query($obj_con, $str_sql_inv);
                $obj_row_inv = mysqli_fetch_array($obj_rs_inv);
                $desc1 = $obj_row_inv["invrcpt_description1"];
                $desc2 = $obj_row_inv["invrcpt_description2"];
                $desc3 = $obj_row_inv["invrcpt_description3"];
                $desc4 = $obj_row_inv["invrcpt_description4"];
                $desc5 = $obj_row_inv["invrcpt_description5"];
                $desc6 = $obj_row_inv["invrcpt_description6"];
                $desc7 = $obj_row_inv["invrcpt_description7"];
                $desc8 = $obj_row_inv["invrcpt_description8"];
            } else {
                $desc1 = $obj_row["re_description1"];
                $desc2 = $obj_row["re_description2"];
                $desc3 = $obj_row["re_description3"];
                $desc4 = $obj_row["re_description4"];
                $desc5 = $obj_row["re_description5"];
                $desc6 = $obj_row["re_description6"];
                $desc7 = $obj_row["re_description7"];
                $desc8 = $obj_row["re_description8"];
            }
            
            
            $pdf_path = "export/receipt_pdf.php?ReID=".$obj_row["re_id"];
            $pdf_view = $pdf_path;
            $pdf_download = $pdf_path."&download=1";
            $output .= '<tr>
                            <td>
                                '. $ReNo .'
                            </td>
                            <td>
                                <div class="truncate">
                                    <b>บริษัท : </b>'. $obj_row["cust_name"] .'<br>';
                        if($obj_row['re_stsid'] != 'STS003'){
                            $output .= "<b>รายการ : </b>" . $desc1;
                        }else{
                            $output .= "<b>หมายเหตุ : </b>" . $obj_row['re_note_cancel'];
                        }
           
            $output .=  '</div>
                            </td>
                            <td class="text-center text-nowrap">
                                '.$obj_row["dep_name"].'
                            </td>
                            <td>
                                <a href="'.$pdf_view.'" target="blank" " rel="noopener noreferrer" class="btn btn-primary form-control mb-1" name="view">
                                    <i class="icofont-eye-alt"></i>&nbsp;&nbsp;View
                                </a>
                                <a  href="'.$pdf_download.'"  target="blank" " rel="noopener noreferrer" class="btn btn-success form-control mb-1">
                                    <i class="icofont-download-alt"></i>&nbsp;&nbsp;Downlaod
                                </a>
                               </td>
                            </td>
                        </tr>';
            $i++;
        }
    } else {
        $output .= '</tbody>
                    <tbody>
                        <tr>
                            <td colspan="5" align="center">ไม่มีข้อมูลใบเสร็จรับเงิน</td>
                        </tr>
                    </tbody>
                    <div class="row">';
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
                        <b>จำนวนใบเสร็จรับเงินทั้งหมด : </b><span id="numINV">'.$total_data.'</span>
                        <input class="form-control d-none" type="number" id="numINV" value="'.$total_data.'">
                    </div>
                </div>';
    echo $output;
?>