<?phpinclude 'config/config.php'; __check_login();$user_id = __session_user("id");$user_level_id = __session_user("level_id");$user_department_id = __session_user("department_id");$paramurl_step = (isset($paramurl_step)) ? $paramurl_step : ((isset($_GET["step"])) ? $_GET["step"] : "");$paramurl_company_id = (isset($paramurl_company_id)) ? $paramurl_company_id : ((isset($_GET["cid"])) ? $_GET["cid"] : "");$paramurl_department_id = (isset($paramurl_department_id)) ? $paramurl_department_id : ((isset($_GET["dep"])) ? $_GET["dep"] : "");$step_key = "checkinvoice_".$paramurl_step;$invoice_step = __invoice_step_company_list($user_id,$paramurl_company_id,$paramurl_department_id);$ck_permiss = false;$step_key = "checkinvoice_".$paramurl_step;$step_name = "";$step_icon = "";$step_query_where = "";if(!empty($invoice_step[$step_key])){    $array_step = $invoice_step[$step_key];    $ck_permiss = true;    $step_name = $array_step["name"];    $step_icon = $array_step["icon"];    $step_query_where = $array_step["query_where"];}?><?php$FilterByList = __invoice_filterby_list();$FilterValList = __invoice_filterval_list();$SearchByList = __invoice_searchby_list();$s_filterby = (isset($_POST["s_filterby"])) ? $_POST["s_filterby"] : 1;$s_filterval = (isset($_POST["s_filterval"])) ? $_POST["s_filterval"] : 1;$s_searchby = (isset($_POST["s_searchby"])) ? $_POST["s_searchby"] : 1;$s_keywords = (isset($_POST["s_keywords"])) ? $_POST["s_keywords"] : "";$s_keywords = $db->real_escape_string($s_keywords);$s_keywords_format = str_replace(" ", '', trim($s_keywords));$arrData = array();$con = "";if($s_searchby != '' && $s_keywords_format!="") {    if(isset($SearchByList[$s_searchby]["db_column"])){         $arrData[] = "(REPLACE(".$SearchByList[$s_searchby]["db_column"].", ' ', '') LIKE '%".$s_keywords_format."%')";    }}if (count($arrData) >= 1) {    $con .= " AND ";    $con .= @implode(" AND ", $arrData);}$con_orderby = "";if($s_filterby!= '' && $s_filterval!="") {    if(isset($FilterByList[$s_filterby]["db_column"]) && isset($FilterValList[$s_filterval]["db_orderby"])){        $con_orderby .= ' ORDER BY '. $FilterByList[$s_filterby]["db_column"] .' '. $FilterValList[$s_filterval]["db_orderby"];    }}            $sql_select = __invoice_query_select();$sql_from = __invoice_query_from();$sql_where = " WHERE  ".$step_query_where ." AND  i.inv_compid = '". $paramurl_company_id ."' AND  i.inv_depid = '". $paramurl_department_id."'";$sql_all = $sql_select.$sql_from.$sql_where;$sql_filters = $sql_all;$sql_filters .= $con;$sql_filters .= $con_orderby;$pagenumber = (!empty($_POST["pagenumber"])) ? $_POST["pagenumber"] : 1;           $limit_row= 10;$start_row = ($pagenumber-1)*$limit_row;if($start_row < 0){$start_row = 0;}$query = $db->query($sql_filters);$total_row = $query->num_rows();$sql =  $sql_filters. " limit " . $start_row . "," . $limit_row ;$result =$db->query($sql)->result();$arrPagination = array($s_filterby,$s_filterval,$s_searchby,$s_keywords);$pagination = __pagination($total_row,$limit_row,$pagenumber,$arrPagination);$loadPage =  $pagenumber.", '".implode("' , '",$arrPagination)."'";$data_checked = __invoice_data_checked($paramurl_step,$paramurl_company_id,$paramurl_department_id);$amount = $data_checked["amount"];$count_checked  = $data_checked["count"];$arrayChecked  = $data_checked["arrayChecked"];        $btn_loading = '<button type="button" class="btn btn-warning form-control btn-block disabled"  title="รอสักครู่..."><i class="icofont-spinner icofont-spin"></i> Waiting...</button>';$btn_check= '<button type="button" class="btn btn-success form-control btn-block btn-uncheck" title="อนุมัติ / Approve"  onclick="onCheck(this,1)"><i class="icofont-checked"></i> Approve</button>';$btn_uncheck= '<button type="button" class="btn btn-danger form-control btn-block btn-check"  title="ไม่อนุมัติ / No Approve"  onclick="onCheck(this,0)"><i class="icofont-checked"></i> No Approve</button>';?><div class="table-responsive">    <table class="table table-bordered mb-0">        <thead class="thead-light">            <tr>                <th style="width: 70px;min-width: 70px;" class="text-center">ลำดับ</th>                <th style="width: 140px;min-width: 140px;">เลขที่ใบแจ้งหนี้</th>                <th style="min-width: 340px;">รายละเอียด</th>                <th style="width: 140px;min-width: 140px;" class="text-center">วันที่ครบชำระ</th>                <th style="width: 140px;min-width: 140px;" class="text-center">จำนวนเงิน</th>                <th style="width: 180px;min-width: 180px;"></th>            </tr>        </thead>                <tbody><?php$html_modal_detail = "";$a = 1+$start_row;if(count($result)>=1){    foreach ($result as $datalist) {        include 'variable_invoice.php';         $html_modal_detail .= $html_detail;        echo $html_tr;                $a++;        }    ?>    <?php    }else {    ?>              <tr>            <td colspan="6" align="center">ไม่มีข้อมูลใบแจ้งหนี้</td>        </tr>    <?php } ?>        </tbody>                <tfoot>            <tr>                <td colspan="4" class="title-amount">ยอดรวมตรวจสอบ</td>                <td colspan="2"  class="amount"><?=__price($amount);?></td>            </tr>        </tfoot>    </table></div><?=$pagination;?><?=$html_modal_detail;?><div class="row" style="border-top:1px solid #d4d3d3;margin-top: 40px">    <div class="col-md-12 my-4 text-center">        <button type="button" class="btn btn-outline-danger " name="btnReset" onclick="onResetForm()"><i class="icofont-close"></i> ล้างรายการที่เลือก</button>        <button type="button" class="btn btn-outline-info" name="btnReset" onclick="onViewForm()"><i class="icofont-search-document"></i> ดูรายการที่เลือก</button>        <button type="button" class="btn btn-lg btn-success  " name="btnCheck"  onclick="onSaveForm()"><i class="icofont-save"></i> บันทึกข้อมูล</button>    </div></div><div id="modalViewForm" class="modal fade modal-view" tabindex="-1" role="dialog" aria-hidden="true">    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">        <div class="modal-content">            <div class="modal-header">                <h3 class="modal-title py-2"><i class="icofont-search-document"></i>  รายการที่เลือก</h3>                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>            </div>            <div class="modal-body" id="viewForm">            </div>            <div class="modal-footer">                <button type="button" class="btn btn-dark" data-dismiss="modal" aria-hidden="true"><i class="icofont-close"></i> ปิด</button>             </div>            </div>        </div>    </div></div><div id="modalSaveForm" class="modal fade modal-detail" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" >    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">        <div class="modal-content">            <div class="modal-header">                <h3 class="modal-title py-2"><i class="icofont-save"></i> คุณต้องการบันทึกข้อมูลใช่หรือไม่?</h3>            </div>            <div class="modal-body" id="saveForm">            </div>            <div class="modal-footer">                <button type="button" class="btn btn-danger btn-close" data-dismiss="modal" aria-hidden="true"><i class="icofont-close"></i> ไม่บันทึกข้อมูล</button>                <button type="button" class="btn btn-success btn-save" onclick="onSave()"><i class="icofont-save"></i> บันทึกข้อมูล</button>             </div>            </div>        </div>    </div></div><script>function base_url(){    return "r_fetch_checkinvoice.php?step=<?=$paramurl_step;?>&cid=<?=$paramurl_company_id;?>&dep=<?=$paramurl_department_id;?>";}function swal_empty(){    swal_fail({title:"คุณยังไม่ได้เลือกใบแจ้งหนี้",text:"กรุณาเลือกใบแจ้งหนี้ อย่างน้อย 1 รายการ",btnclose:1});}var count_checked = function(){    var count = 0;      $.ajax({        async: false,        url: base_url()+"&action=data_check",        success:function(jsondata){            if(IsJsonString(jsondata)==true){                var res = JSON.parse(jsondata);                  count = res.count;            }        }    });            return count;}function onCheck(t,check=""){    var btn = $(t);    var parent = $(t).parents("tr");    var id = parent.attr("data-row");    var row = $(".row-"+id)    var divbtn = row.find(".div-btn");    var input_amount = $(".amount");        var error_text = "";        $.ajax({        data: "id="+id+"&check="+check,        type: "POST",        url: base_url()+"&action=check",        success: function(jsondata){            if(IsJsonString(jsondata)==true){                var res = JSON.parse(jsondata);                  var response = res.response;                var message = res.message;                var amount = res.amount;                                if(response=="S"){                    input_amount.html(amount);                    if(check==1){                        row.addClass("row-checked");                        divbtn.html('<?=$btn_uncheck;?>');                    }else if(check==0){                        row.removeClass("row-checked");                        divbtn.html('<?=$btn_check;?>');                    }                }else{                    error_text = message;                }            }else{                 error_text = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";            }                               if(error_text!=""){                swal_fail({text:error_text,btnclose:1});            }        }    });}            function onResetForm(){    if(count_checked()>=1){        var resetForm = function(){            var error_text = "";            $.ajax({                url:  base_url()+"&action=reset",                success: function(jsondata){                    if(IsJsonString(jsondata)==true){                        var res = JSON.parse(jsondata);                          var response = res.response;                        var message = res.message;                        if(response=="S"){                            var fn = function(){                                loadPage(<?=$loadPage;?>);                            }                            swal_success({fn:fn});                        }else{                            error_text = message                        }                    }else{                         error_text = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";                    }                                               if(error_text!=""){                        swal_fail({text:error_text,btnclose:1});                    }                }            });        }                swal_confirm({title:"คุณต้องการล้างรายการที่เลือกไว้ ใช่หรือไม่ ?",fn:resetForm})    }else{        swal_empty();    }}function onViewForm(){    var modalView = $("#modalViewForm");    var div = $("#viewForm");        div.html("<br><div align='center'>กรุณารอสักครู่...<br><br><i class='icofont-spinner icofont-spin' style='font-size:24px'></i></div>");    modalView.modal('show');        $.ajax({        url: base_url()+"&action=view",        success:function(data){            div.html(data);        }    });}function onSaveForm(){    if(count_checked()>=1){        var modalSave = $("#modalSaveForm");        var div = $("#saveForm");                div.html("<br><div align='center'>กรุณารอสักครู่...<br><br><i class='icofont-spinner icofont-spin' style='font-size:24px'></i></div>");        modalSave.modal('show');                $.ajax({            data: "save=1",            type: "POST",            url: base_url()+"&action=recheck",            success:function(data){                div.html(data);            }        });    }else{        swal_empty();    }}function onSaveForm(){    if(count_checked()>=1){        var saveForm = function(){            var error_text = "";            $.ajax({                url: base_url()+"&action=save",                success:function(jsondata){                    if(IsJsonString(jsondata)==true){                        var res = JSON.parse(jsondata);                          var response = res.response;                        var message = res.message;                                                if(response=="S"){                            loadPage(<?=$loadPage;?>);                        }else{                            error_text = message                        }                    }else{                         error_text = "กรุณาลองใหม่อีกครั้ง หรือติดต่อโปรแกรมเมอร์";                    }                                               if(error_text!=""){                        swal_fail({text:error_text,btnclose:1});                    }else{                        swal_success({title:"บันทึกข้อมูลสำเร็จ",text:"กรุณารอสักครู่..."});                    }                }            });        }                var count = 0;        var amount = 0;          $.ajax({            async: false,            url: base_url()+"&action=data_check",            success:function(jsondata){                if(IsJsonString(jsondata)==true){                    var res = JSON.parse(jsondata);                      count = res.count;                    amount =res.amount;                }            }        });                var html = "";        html += "<h3>กรุณาตรวจสอบข้อมูลให้ถูกต้อง</h3>";        html += "<table class='table table-bordered swal-table'>";        html += "<tr>";            html += "<th>จำนวนใบแจ้งหนี้</th>";            html += "<td class='swal-count'>"+count+"</td>";        html += "</tr>";                html += "<tr>";            html += "<th>ยอดรวมตรวจสอบ</th>";            html += "<td class='swal-amount'>"+amount+"</td>";        html += "</tr>";        html += "</table>";                swal_save({text:html,fn:saveForm});    }else{        swal_empty();    }}</script>