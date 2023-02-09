<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('รายงาน <i class="icofont-caret-right"></i> รายจ่าย  <i class="icofont-caret-right"></i> สรุปรายการทำจ่าย <i class="icofont-caret-right"></i> เลือกฝ่าย');
?>
<?php
	 if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
	 
	if (!$_SESSION["user_name"]){  //check session
		Header("Location: login.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form
		
	} else {
		include 'connect.php';
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
    $url_back = "reportpay.php?cid=".$paramurl_company_id."&dep=0";
}else{
    $url_back = "index.php?cid=".$paramurl_company_id."&dep=".$paramurl_department_id;
}
?>
<!DOCTYPE html>
<html>
<head>
	
	<?php include 'head.php'; ?>
	<style type="text/css">
		.table .thead-light th {
			color: #000;
		}
		tr:nth-last-child(n) {
			border-bottom: 1px solid #dee2e6;
		}
        .truncate-des-paid{
            width: auto;
            min-width: 0;
            max-width: 400px;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 14px;
        }

        .truncate-des-waiting{
            width: auto;
            min-width: 0;
            max-width: 653px;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 14px;
        }
	</style>
</head>
<body>
	<?php include 'navbar.php'; ?>
	<section>
		<div class="container">
        <div class="row"  style="background-color: #E9ECEF;border-bottom:1px solid #d4d3d3;">
        <input type="hidden" name="depid" id="depid" value="<?= $paramurl_department_id ?>">

            <div class="col-md-10 col-sm-12 mt-4">
                <div class="row">
                    <div class="col-md-12"><h3>สรุปรายการทำจ่าย</h3></div>
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
                            <label class="col-md-1 col-sm-12 col-form-label text-left">สถานะ: </label>
                            <div class="col-md-2 col-sm-12 mb-1">
                                <select class="custom-select form-control" name="s_searchby" id="s_searchby">
                                    <option value="paid">จ่ายแล้ว</option>
                                    <option value="waiting to pay">รอจ่าย</option>
                                </select>
                            </div>
                            <label class="col-md-3 col-sm-12 col-form-label text-right">ค้นหาเลขที่ใบสำคัญจ่าย: </label>
                            <div class="col-md-6 col-sm-12 mb-1">
                                <input type="text" name="s_keywords" id="s_keywords" class="form-control" placeholder="กรอกเลขที่ค้นหา" autocomplete="off" value="">
                            </div>                                  
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-2 col-sm-12 col-form-label text-left">วันที่เริ่มต้นการค้นหา </label>
                            <div class="col-md-4 col-sm-12 mb-1">
                                <input type="date" name="dt" id="dt" class="form-control">
                            </div>
                            <label class="col-md-2 col-sm-12 col-form-label text-right">วันที่สิ้นสุดการค้นหา </label>
                            <div class="col-md-4 col-sm-12 mb-1">
                                <input type="date" name="df" id="df" class="form-control">
                            </div>                                  
                        </div>
                    </div>
                </div>
            </div>
            </div>  
            <div class="row py-4 px-1" style="background-color: #FFFFFF">
                <div class="col-md-12">
                    <div class="table-responsive" id="reportpayTable">
                        <table class="table mb-0">
                            <thead class="thead-light" id="report-head">

                            </thead>
                            <tbody id="reportpayTable-body">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="width: 100%;" id="paging"></div>
            </div>
		</div>
	</section>
	<script type="text/javascript">
        load_data(1)

        $(document).on("change",'#s_searchby',function(){
            search_filter()
        })

        $(document).on("change",'#dt',function(){
            search_filter()
        })

        $(document).on("change",'#df',function(){
            search_filter()
        })

        $(document).on("keyup",'#s_keywords',function(){
            search_filter()
        })

        $(document).on("click",'.page-link',function(){
            page = $(this).attr("data-page_number")
            search_filter(page)
        })

        function load_data(page=1,s_keywords="paid",s_searchby="",dt="",df="") {
            let dep = $("#depid").val();
            $.ajax({
                url:"fetch_reportpay.php",
                method:"POST",
                data:{
                    dep,
                    s_keywords,
                    s_searchby,
                    page,
                    df,
                    dt
                },
                dataType: 'json',
                success:function(data) {
                    cleart_data()
                    $("#report-head").append(data.head)
                    $("#reportpayTable-body").append(data.data)
                    $("#paging").append(data.page)
                }
            });
        }

        function search_filter(page){
            let s_keywords = $("#s_keywords").val()
            let s_searchby = $("#s_searchby").val()
            let dt = $("#dt").val()
            let df = $("#df").val()
            load_data(page,s_searchby,s_keywords,dt,df)
        }

        function cleart_data(){
            $("#reportpayTable-body").html("")
            $("#paging").html("")  
            $("#report-head").html("")          
        }
        
	</script>
	<?php include 'footer.php'; ?>
</body>
</html>
<?php } ?>