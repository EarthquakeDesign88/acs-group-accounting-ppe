<?php
include 'config/config.php'; 
__check_login();
$user_id = __session_user("id");
$user_level_id = __session_user("level_id");
$user_department_id = __session_user("department_id");
$paramurl_company_id = (isset($paramurl_company_id)) ? $paramurl_company_id : ((isset($_GET["cid"])) ? $_GET["cid"] : "");
$paramurl_department_id = (isset($paramurl_department_id)) ? $paramurl_department_id : ((isset($_GET["dep"])) ? $_GET["dep"] : "");
$authority_comp_count_dep = __authority_company_count_department($user_id,$paramurl_company_id);
$comp_name = __data_company($paramurl_company_id,"name");
$dep_name = __data_department($paramurl_department_id,"name");
$dep_name_th = __data_department($paramurl_department_id,"name_th");
$dep_name_new = ($dep_name_th!="") ? $dep_name." - ".$dep_name_th : "ฝ่าย ".$dep_name;
if($authority_comp_count_dep>1){
    $url_back = "payment.php?cid=".$paramurl_company_id."&dep=0";
}else{
    $url_back = "index.php?cid=".$paramurl_company_id."&dep=".$paramurl_department_id;
}
?>
<?php
$s_tab = (isset($_GET["s_tab"])) ? $_GET["s_tab"] : 1;
$s_change_tab = (isset($_POST["s_change_tab"])) ? $_POST["s_change_tab"] : 1;
$s_filterby = (isset($_POST["s_filterby"])) ? $_POST["s_filterby"] : 1;
$s_filterval = (isset($_POST["s_filterval"])) ? $_POST["s_filterval"] : 1;
$s_searchby = (isset($_POST["s_searchby"])) ? $_POST["s_searchby"] : 1;
$s_keywords = (isset($_POST["s_keywords"])) ? $_POST["s_keywords"] : "";
$s_keywords = $db->real_escape_string($s_keywords);
$s_keywords_format = str_replace(" ", '', trim($s_keywords));    
if($s_tab==1){
    $FilterByList =  __invoice_filterby_list(); 
    $FilterValList = __invoice_filterval_list();
    $SearchByList = __invoice_searchby_list();
}else{
    $FilterByList =  __invoice_payment_filterby_list(); 
    $FilterValList = __invoice_filterval_list();
    $SearchByList =  __invoice_payment_searchby_list(); 
}
$filterby_option = "";
$filterby_option .= '<optgroup label="เรียงลำดับตาม">';
if(count($FilterByList)>=1){
    foreach ($FilterByList as $keyFilterBy => $valFilterBy) {
        $sel_filterby = ($s_filterby==$keyFilterBy) ? " selected " : "";
        $filterby_option .= '<option value="'.$keyFilterBy.'" '.$sel_filterby.'>';
        $filterby_option .= $valFilterBy["name"];
        $filterby_option .= '</option>';
    }
}
$filterby_option .= '</optgroup>';
$filterval_option = "";
$filterval_option .= '<optgroup label="เรียงจาก">';
if(count($FilterValList)>=1){
    foreach ($FilterValList as $keyFilterVal => $valFilterVal) {
        $sel_filterval = ($s_filterval==$keyFilterVal) ? " selected " : "";
        $filterval_option .= '<option value="'.$keyFilterVal.'" '.$sel_filterval.'>';
        $filterval_option .= $valFilterVal["name"];
        $filterval_option .= '</option>';
    }
}
$filterval_option .= '</optgroup>';
$searchby_option = "";
$searchby_option .= '<optgroup label="ค้นหาโดย">';
if(count($SearchByList)>=1){
    foreach ($SearchByList as $keySearchBy => $valSearchBy) {
        $sel_searchby = ($s_searchby==$keySearchBy) ? " selected " : "";
        
        $searchby_option .= '<option value="'.$keySearchBy.'" '.$sel_searchby.'>';
        $searchby_option .= $valSearchBy["name"];
        $searchby_option .= '</option>';
    }
}
$searchby_option .= '</optgroup>';
?>
<style>
    .clear-pd {
        padding-right: 0 !important;
    }
</style>
<?php if($s_change_tab){ ?>
<div class="row"  style="background-color: #E9ECEF;border-bottom:1px solid #d4d3d3;">
    <div class="col-md-10 col-sm-12 mt-4">
        <div class="row">
            <div class="col-md-12"><h3>ใบสำคัญจ่าย  (<?= ($s_tab==1) ? '<i class="icofont-plus"></i> เพิ่มใบสำคัญจ่าย': '<i class="icofont-tick-boxed"></i> ออกใบสำคัญจ่ายแล้ว'; ?>)</h3></div>
        </div>
        
        <div class="row">
            <div class="col-md-8 col-form-label text-left"><font class="font-bold"><i class="icofont-rounded-right"></i> ชื่อบริษัท: </font><font class="font-normal"><?=$comp_name;?></font></div>
            <div class="col-md-4 col-form-label text-left"><font class="font-bold"><i class="icofont-rounded-right"></i> ชื่อฝ่าย: </font><font class="font-normal"><?=$dep_name_new;?></font></div>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-12 mt-4 text-right">
        <a href="<?=$url_back;?>" type="button" class="btn btn-warning mb-2" name="btnBack" id="btnBack">
            <i class="icofont-history"></i> ย้อนกลับ
        </a>
    </div>
</div>
<div class="row py-4 px-1" style="background-color: #f4f4f4;">
    <div class="col-md-12 text-right">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-3 col-sm-12 col-form-label text-left">เรียงลำดับตาม: </label>
                    <div class="col-md-3 col-sm-12 mb-1">
                        <select class="custom-select form-control" id="s_filterby">
                            <?=$filterby_option;?>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-1">
                        <div class="input-group">
                            <select class="custom-select form-control" id="s_filterval">
                                <?=$filterval_option;?>
                            </select>
                        </div>
                    </div>                                  
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-right">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-3 col-sm-12 col-form-label text-left">ค้นหา: </label>
                    <div class="col-md-3 col-sm-12 mb-1">
                        <select class="custom-select form-control" id="s_searchby">
                            <?=$searchby_option;?>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-1">
                        <input type="text" name="s_keywords" id="s_keywords" class="form-control" placeholder="กรอกคำค้นหา" autocomplete="off" value="<?=$s_keywords;?>">
                    </div>                                  
                </div>
            </div>
        </div>
    </div>
</div>  
 <div class="row div-tabs">
      <div class="col-md-12 pl-0 pr-0">
        <ul id="tabs" class="nav nav-tabs nav-fill">
            <li class="nav-item">
                <a href="javascript:void(0);onTab(1)" data-tab_id="1"  class="nav-link <?=($s_tab==1) ? "active disabled" : ""; ?>"><i class="icofont-plus"></i> เพิ่มใบสำคัญจ่าย</a>
            </li>
            <li class="nav-item">
                <a href="javascript:void(0);onTab(2)" data-tab_id="2"  class="nav-link <?=($s_tab==2) ? "active disabled" : ""; ?>"><i class="icofont-tick-boxed"></i> ออกใบสำคัญจ่ายแล้ว</a>
            </li>
        </ul>
    </div>
</div>
<?php } ?>
<div id="divShowContent" style="margin-top: 20px">
<?php
$arrData = array();
$con = "";
if($s_searchby != '' && $s_keywords_format!="") {
    if(isset($SearchByList[$s_searchby]["db_column"])){
         $arrData[] = "(REPLACE(".$SearchByList[$s_searchby]["db_column"].", ' ', '') LIKE '%".$s_keywords_format."%')";
    }
}
if (count($arrData) >= 1) {
    $con .= " AND ";
    $con .= @implode(" AND ", $arrData);
}
$con_orderby = "";
if($s_filterby!= '' && $s_filterval!="") {
    if(isset($FilterByList[$s_filterby]["db_column"]) && isset($FilterValList[$s_filterval]["db_orderby"])){
        $con_orderby .= ' ORDER BY '. $FilterByList[$s_filterby]["db_column"] .' '. $FilterValList[$s_filterval]["db_orderby"];
    }
}
            
$sql_select = __invoice_query_select();
 if($s_tab==1){
    $sql_from = __invoice_query_from();
 }else{
     $sql_from = __invoice_payment_query_from();
 }
 
$sql_where = " WHERE  i.inv_compid = '". $paramurl_company_id ."' AND  i.inv_depid = '". $paramurl_department_id."'";
 if($s_tab==1){
    $sql_where .= " AND ((i.inv_statusMgr = 1 AND i.inv_apprMgrno <> '') AND inv_paymid = '' AND p.paya_id<>'') "; 
}
$sql_all = $sql_select.$sql_from.$sql_where;
$sql_filters = $sql_all;
$sql_filters .= $con;
$sql_filters .= ($s_tab==2) ? "  GROUP BY pm.paym_no " : "";
$sql_filters .= $con_orderby;
$pagenumber = (!empty($_POST["pagenumber"])) ? $_POST["pagenumber"] : 1;           
$limit_row= 10;
$start_row = ($pagenumber-1)*$limit_row;
if($start_row < 0){$start_row = 0;}
$query = $db->query($sql_filters);
$total_row = $query->num_rows();
$sql =  $sql_filters. " limit " . $start_row . "," . $limit_row ;
$result =$db->query($sql)->result();
$arrPagination = array($s_filterby,$s_filterval,$s_searchby,$s_keywords);
$pagination = __pagination($total_row,$limit_row,$pagenumber,$arrPagination);
$loadPage =  $pagenumber.", '".implode("' , '",$arrPagination)."'";
$data_checked = __invoice_payment_data_checked($paramurl_company_id,$paramurl_department_id);
$amount = $data_checked["amount"];
$count_checked  = $data_checked["count"];
$arrayChecked  = $data_checked["arrayChecked"];
$btn_loading = '<button type="button" class="btn btn-warning form-control btn-block disabled"  title="รอสักครู่..."><i class="icofont-spinner icofont-spin"></i> Waiting...</button>';
$btn_check= '<button type="button" class="btn btn-success form-control btn-block btn-uncheck" title="เลือก / Approve"  onclick="onCheck(this,1)"><i class="icofont-check"></i></button>';
$btn_uncheck= '<button type="button" class="btn btn-danger form-control btn-block btn-check"  title="ไม่เลือก / No Approve"  onclick="onCheck(this,0)"><i class="icofont-close"></i></button>';
?> 
<div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead class="thead-light">
                <?php if ($s_tab == 1) { ?>
                    <tr>
                        <th style="width: 70px;min-width: 70px;"></th>
                        <th style="width: 70px;min-width: 70px;" class="text-center">ลำดับ</th>
                        <th style="width: 140px;min-width: 140px;" class="text-center">เลขที่ใบแจ้งหนี้</th>
                        <th style="min-width: 400px;">รายละเอียด</th>
                        <th style="width: 140px;min-width: 140px;" class="text-center">วันที่ครบชำระ</th>
                        <th style="width: 140px;min-width: 140px;" class="text-center">จำนวนเงิน</th>
                        <th style="width: 70px;min-width: 70px;"> </th>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <th style="width: 5%" class="text-center">ลำดับ</th>
                        <th style="width: 25%" class="text-center">เลขที่ใบสำคัญจ่าย</th>
                        <th style="min-width: 400px;">รายละเอียด</th>
                        <th style="width: 25%" class="text-center">จำนวนเงิน</th>
                        <th style="width: 25%" class="text-center">สถานะทำจ่าย</th>
                        <th style="width: 20%"> </th>
                    </tr>
                <?php } ?>
            </thead>
            <tbody>
<?php
$html_modal_detail = "";
$a = 1+$start_row;
if(count($result)>=1){
    foreach ($result as $datalist) {
        
         if($s_tab==1){
            include 'variable_invoice.php'; 
            echo  $html_payment_tr;
            $html_modal_detail .= $html_detail;
        }else{
            include 'variable_payment.php'; 
             echo  $html_tr;
             $html_modal_detail .= $html_detail;
        }
        
        $a++;
        }
  ?>
<?php }else { ?>      
        <tr>
            <td colspan="6" align="center">ไม่มีข้อมูล</td>
        </tr>
<?php } ?>
        </tbody>
        
    <?php if($s_tab==1){ ?>
    <tfoot>
        <tr>
            <td colspan="5" class="title-amount">ยอดรวม</td>
            <td colspan="2"  class="amount"><?=__price($amount);?></td>
        </tr>
    </tfoot>
    <?php } ?>
        
    </table>
</div>
<?=$pagination;?>    
<?=$html_modal_detail;?>
<?php if($s_tab==1){ ?>
<div class="row" style="border-top:1px solid #d4d3d3;margin-top: 40px">
    <div class="col-md-12 my-4 text-center">
        <button type="button" class="btn btn-outline-danger " name="btnReset" onclick="onResetForm()"><i class="icofont-close"></i> ล้างรายการที่เลือก</button>
        <button type="button" class="btn btn-outline-info" name="btnReset" onclick="onViewForm()"><i class="icofont-search-document"></i> ดูรายการที่เลือก</button>
        <button type="button" class="btn btn-lg btn-success  " name="btnCheck"  onclick="onSaveForm()"><i class="icofont-arrow-right"></i> ถัดไป&nbsp;&nbsp;&nbsp;&nbsp;</button>
    </div>
</div>
<?php } ?>
</div>
<div id="modalViewForm" class="modal fade modal-view" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title py-2"><i class="icofont-search-document"></i>  รายการที่เลือก</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body" id="viewForm"> </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal" aria-hidden="true"><i class="icofont-close"></i> ปิด</button>
             </div>
        </div>
     </div>
</div>
<script>
    function base_url() {
        return "r_fetch_payment.php?cid=<?= $paramurl_company_id; ?>&dep=<?= $paramurl_department_id; ?>";
    }
    function swal_empty() {
        swal_fail({
            title: "คุณยังไม่ได้เลือกใบแจ้งหนี้",
            text: "กรุณาเลือกใบแจ้งหนี้ อย่างน้อย 1 รายการ",
            btnclose: 1
        });
    }
    var count_checked = function() {
        var count = 0;
        $.ajax({
            async: false,
            url: base_url() + "&action=data_check",
            success: function(jsondata) {
                if (IsJsonString(jsondata) == true) {
                    var res = JSON.parse(jsondata);
                    count = res.count;
                }
            }
        });
        return count;
    }
    function onCheck(t, check = "") {
        if (count_checked() < 6 || check == 0) {
            var btn = $(t);
            var parent = $(t).parents("tr");
            var id = parent.attr("data-row");
            var row = $(".row-" + id)
            var divbtn = row.find(".div-btn");
            var input_amount = $(".amount");
            var error_text = "";
            $.ajax({
                data: "id=" + id + "&check=" + check,
                type: "POST",
                url: base_url() + "&action=check",
                success: function(jsondata) {
                    if (IsJsonString(jsondata) == true) {
                        var res = JSON.parse(jsondata);
                        var response = res.response;
                        var message = res.message;
                        var amount = res.amount;
                        if (response == "S") {
                            input_amount.html(amount);
                            if (check == 1) {
                                row.addClass("row-checked");
                                divbtn.html('<?= $btn_uncheck; ?>');
                            } else if (check == 0) {
                                row.removeClass("row-checked");
                                divbtn.html('<?= $btn_check; ?>');
                            }
                        } else {
                            error_text = message;
                        }
                    } else {
                        error_text = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
                    }
                    if (error_text != "") {
                        swal_fail({
                            text: error_text,
                            btnclose: 1
                        });
                    }
                }
            });
        } else {
            swal_fail({
                text: "เลือกใบแจ้งหนี้เกิน 6 รายการ \n กรุณาเลือกใหม่",
                btnclose: 1
            });
        }
    }
    function onResetForm() {
        if (count_checked() >= 1) {
            var resetForm = function() {
                var error_text = "";
                $.ajax({
                    url: base_url() + "&action=reset",
                    success: function(jsondata) {
                        if (IsJsonString(jsondata) == true) {
                            var res = JSON.parse(jsondata);
                            var response = res.response;
                            var message = res.message;
                            if (response == "S") {
                                var fn = function() {
                                    loadPage(<?= $loadPage; ?>);
                                }
                                swal_success({
                                    fn: fn
                                });
                            } else {
                                error_text = message
                            }
                        } else {
                            error_text = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
                        }
                        if (error_text != "") {
                            swal_fail({
                                text: error_text,
                                btnclose: 1
                            });
                        }
                    }
                });
            }
            swal_confirm({
                title: "คุณต้องการล้างรายการที่เลือกไว้ ใช่หรือไม่ ?",
                fn: resetForm
            })
        } else {
            swal_empty();
        }
    }
    function onViewForm() {
        var modalView = $("#modalViewForm");
        var div = $("#viewForm");
        div.html("<br><div align='center'>กรุณารอสักครู่...<br><br><i class='icofont-spinner icofont-spin' style='font-size:24px'></i></div>");
        modalView.modal('show');
        $.ajax({
            url: base_url() + "&action=view",
            success: function(data) {
                div.html(data);
            }
        });
    }
    function onSaveForm() {
        if (count_checked() >= 1) {
            if (count_checked() <= 6) {
                var saveForm = function() {
                    var error_text = "";
                    $.ajax({
                        url: base_url() + "&action=save",
                        success: function(jsondata) {
                            if (IsJsonString(jsondata) == true) {
                                var res = JSON.parse(jsondata);
                                var response = res.response;
                                var message = res.message;
                                if (response == "S") {
                                    loadPage(<?= $loadPage; ?>);
                                } else {
                                    error_text = message
                                }
                            } else {
                                error_text = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";
                            }
                            if (error_text != "") {
                                swal_fail({
                                    text: error_text,
                                    btnclose: 1
                                });
                            }
                        }
                    });
                }
                var count = 0;
                var amount = 0;
                $.ajax({
                    async: false,
                    url: base_url() + "&action=data_check",
                    success: function(jsondata) {
                        if (IsJsonString(jsondata) == true) {
                            var res = JSON.parse(jsondata);
                            count = res.count;
                            amount = res.amount;
                        }
                    }
                });
                var html = "";
                html += "<h3>กรุณาตรวจสอบข้อมูลให้ถูกต้อง</h3>";
                html += "<table class='table table-bordered swal-table'>";
                html += "<tr>";
                html += "<th>จำนวนใบแจ้งหนี้</th>";
                html += "<td class='swal-count'>" + count + "</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<th>ยอดรวม</th>";
                html += "<td class='swal-amount'>" + amount + "</td>";
                html += "</tr>";
                html += "</table>";
                var onNext = function() {
                    window.location.href = 'payment_action.php?cid=<?= $paramurl_company_id; ?>&dep=<?= $paramurl_department_id; ?>&action=add';
                }
                swal_confirm({
                    text: html,
                    fn: onNext
                });
            } else {
                swal_fail({
                    text: "เลือกใบแจ้งหนี้เกิน 6 รายการ \n กรุณาเลือกใหม่",
                    btnclose: 1
                });
            }
        } else {
            swal_empty();
        }
    }
</script>
<script>
    function onTab(s_tab) {
        var div = $('#divShowAll');
        div.html("<br><div align='center'>กรุณารอสักครู่...<br><br><i class='icofont-spinner icofont-spin' style='font-size:24px'></i></div>");
        $.ajax({
            url: "fetch_payment.php?cid=<?= $paramurl_company_id; ?>&dep=<?= $paramurl_department_id; ?>&s_tab=" + s_tab,
            method: "POST",
            data: "s_change_tab=1",
            success: function(data) {
                setTimeout(function() {
                    div.html(data);
                    var path = window.location.pathname;
                    var searchParams = new URLSearchParams(window.location.search);
                    searchParams.set('s_tab', s_tab)
                    var newParams = searchParams.toString()
                    window.history.pushState(null, null, path + "?" + newParams);
                }, 500);
            }
        });
    }
    function delay(callback, ms) {
        var timer = 0;
        return function() {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function() {
                callback.apply(context, args);
            }, ms || 0);
        };
    }
    $('#s_filterby, #s_filterval, #s_searchby').change(function() {
        onSearch();
    });
    $('#s_keywords').keyup(delay(function(e) {
        var value = $('#s_keywords').val();
        onSearch();
    }, 500));
    function onSearch() {
        var s_filterby = $('#s_filterby').val();
        var s_filterval = $('#s_filterval').val();
        var s_searchby = $('#s_searchby').val();
        var s_keywords = $('#s_keywords').val();
        loadPage(1, s_filterby, s_filterval, s_searchby, s_keywords);
    }
    function loadPage(pagenumber, s_filterby, s_filterval, s_searchby, s_keywords) {
        var div = $('#divShowContent');
        var arr = new Array();
        var data = "";
        if (pagenumber != "") {
            arr.push("pagenumber=" + pagenumber);
        }
        if (typeof(s_filterby) === "undefined") {} else {
            if (s_filterby != "") {
                arr.push("s_filterby=" + s_filterby);
            }
        }
        if (typeof(s_filterval) === "undefined") {} else {
            if (s_filterval != "") {
                arr.push("s_filterval=" + s_filterval);
            }
        }
        if (typeof(s_searchby) === "undefined") {} else {
            if (s_searchby != "") {
                arr.push("s_searchby=" + s_searchby);
            }
        }
        if (typeof(s_keywords) === "undefined") {} else {
            if (s_keywords != "") {
                arr.push("s_keywords=" + s_keywords);
            }
        }
        if (arr.length >= 1) {
            data = data;
            data = data + arr.join("&");
        }
        div.html("<br><div align='center'>กรุณารอสักครู่...<br><br><i class='icofont-spinner icofont-spin' style='font-size:24px'></i></div>");
        $.ajax({
            url: "fetch_payment.php?cid=<?= $paramurl_company_id; ?>&dep=<?= $paramurl_department_id; ?>&s_tab=<?= $s_tab; ?>",
            method: "POST",
            data: data + "&s_change_tab=0",
            success: function(data) {
                setTimeout(function() {
                    div.html(data);
                }, 500);
            }
        });
    }
</script>
<script type="text/javascript">
    function changePayStatus(paym_id) { 
        var paymentStatus = $("#paym_paystatus" + paym_id).val();
        var modalchangePayStatus = $(".modal-changestatus");
        // console.log(paym_id + paymentStatus);
        $.ajax({
            type: "POST",
            url: base_url() + "&action=change_paystatus",
            data: {
                paym_id: paym_id,
                paymentStatus: paymentStatus
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 'success') {
                    modalchangePayStatus.modal('hide');
                    $('.modal-backdrop').removeClass('modal-backdrop');
                    $('.loadwindow-remove').addClass('clear-pd');
                
                    swal_success({title: response.message,text:"กรุณารอสักครู่..."}); 
                    loadPage(<?= $loadPage; ?>);   
                } 
                else {
                    modalchangePayStatus.modal('hide');
                    $('.modal-backdrop').removeClass('modal-backdrop');
                    $('.loadwindow-remove').addClass('clear-pd');

                    swal_alert({title: response.message,text:"กรุณากด ตกลง เพื่อดำเนินการต่อ",});
                }
            }
        });
    }
</script>
