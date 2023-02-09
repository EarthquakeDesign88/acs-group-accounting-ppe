<?php
include 'config/config.php'; 
__check_login();
__page_seldep2('<b>รายจ่าย</b><i class="icofont-caret-right"></i> ภาษีซื้อรายเดือน <i class="icofont-caret-right"></i> เลือกฝ่าย');
?>

<?php
 if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!$_SESSION['user_name']) {
    header('location: login.php');
} else {
    include 'connect.php';

    $cid = $_GET["cid"];
    $dep = $_GET["dep"];

    $str_sql_user = "SELECT * FROM user_tb AS u 
						INNER JOIN level_tb AS l ON u.user_levid = l.lev_id 
						INNER JOIN department_tb AS d ON u.user_depid = d.dep_id 
						WHERE user_id = '" . $_SESSION["user_id"] . "'";
    $obj_rs_user = mysqli_query($obj_con, $str_sql_user);
    $obj_row_user = mysqli_fetch_array($obj_rs_user);
    //
    $str_sql = "SELECT * FROM department_tb WHERE dep_id = '" . $dep . "'";
    $obj_rs = mysqli_query($obj_con, $str_sql);
    $obj_row = mysqli_fetch_array($obj_rs);
}

?>

<!doctype html>
<html lang="en">

<head>

    <?php include_once 'head.php'; ?>

    <link rel="stylesheet" type="text/css" href="css/checkbox.css">
    <style type="text/css">
        div#show-listCust {
            position: absolute;
            z-index: 99;
            width: 100%;
            margin-left: -15px !important;
        }

        .list-unstyled {
            position: relative;
            background-color: #FFFF;
            cursor: pointer;
            margin-left: 15px;
            margin-right: 15px;
            -webkit-box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
        }

        .list-group-item {
            font-family: 'Sarabun', sans-serif;
            cursor: pointer;
            border: 1px solid #eaeaea;
            list-style: none;
            top: 50%;
            padding: .75rem !important;
        }

        .list-group-item:hover {
            background-color: #f5f5f5;
        }

        #container-main {
            margin-top: 45px;
        }

        .input-date-start,
        .input-date-end {
            width: 100%;
            padding: 5px;
            border-radius: 5px;
            background-color: #EBF5FB;
        }

        .card {
            margin-top: 50px;
        }
    </style>

</head>

<body>

    <?php include_once 'navbar.php'; ?>

    <!-- section-start -->
    <section>
        <div class="container">

            <form method="POST" name="" id="" action="">

                <div class="row py-4 px-1" style="background-color: #BBEBDF">
                    <div class="col-md-12 pb-4">
                        <h3 class="mb-0">
                            <i class="icofont-papers"></i>&nbsp;&nbsp;ภาษีซื้อ
                        </h3>
                    </div>

                    <div class="col-md-12 d-none">
                        <input type="text" class="form-control" name="compid" id="compid" value="<?= $cid; ?>">
                        <input type="text" class="form-control" name="depid" id="depid" value="<?= $dep; ?>">
                    </div>

                    <div class="col-md-6 text-right">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-3 col-form-label text-right">
                                        เรียงตามวันที่ :
                                    </label>
                                    <div class="col-sm-5">
                                        <select class="custom-select form-control" id="FilterBy">
                                            <option value="inv_id" selected>วันที่</option>
                                        </select>
                                        <input type="text" class="form-control d-none" name="FilBy" id="FilBy" value="inv_id">

                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select class="custom-select form-control" id="FilterVal">
                                                <option value="DESC" selected>มากไปน้อย</option>
                                                <!-- <option value="ASC">น้อยไปมาก</option> -->
                                            </select>
                                            <input type="text" class="form-control d-none" name="FilVal" id="FilVal" value="DESC">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="col-md-6">
                        <div class="row text-right">
                            <?php if ($_GET["cid"] == 'C001') : ?>
                                <?php if ($obj_row_user["user_levid"] == 5 || $obj_row_user["user_levid"] == 6) : ?>
                                <?php else : ?>
                                    <div class="col-md-12 mb-4">
                                        <a href="tax_seldep.php?cid=<?= $cid; ?>&dep=" type="button" class="btn btn-warning" name="btnBack" id="btnBack">
                                            <i class="icofont-history"></i>&nbsp;&nbsp;ย้อนกลับ
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!--  -->

                    <div class="col-md-12" id="SearchInv">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="mt-1">ฝ่าย</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="depname" id="depname" value="<?php echo $obj_row["dep_name"]; ?>" readonly style="background-color: #FFF">
                                    <input type="text" class="form-control d-none" name="depid" id="depid" value="<?php echo $dep; ?>">
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-auto">
                                        <label class="mt-1">ค้นหาโดย : </label>
                                    </div>
                                    <div class="col-md-3 mb-0">
                                        <div class="checkbox">
                                            <input type="radio" name="SearchINVBy" id="INVinvno" value="inv_no" checked="checked">
                                            <label for="INVinvno"><span>เลขที่ใบภาษีซื้อ</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-0"></div>
                                    <input type="text" class="form-control d-none" name="SearchINVVal" id="SearchINVVal" value="inv_no">
                                </div>

                                <div class="input-group">
                                    <input type="text" name="search_box" id="search_box" class="form-control" placeholder="กรอกเลขที่ภาษีซื้อที่ต้องการค้นหา" autocomplete="off">
                                    <div class="input-group-append">
                                        <a href="tax_input_add.php?cid=<?php echo $cid; ?>&dep=<?php echo $dep; ?>" class="btn btn-primary float-right">
                                            <i class="icofont-plus-circle"></i>&nbsp;&nbsp;เพิ่มภาษีซื้อ
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row py-4 px-1" style="background-color: #FFFFFF">
                    <div class="col-md-12">
                        <div class="table-responsive" id="invoiceShow"></div>
                    </div>
                </div>

            </form>

        </div>
    </section>
    <!-- section-end -->

    <!-- START VIEW TAX -->
    <div id="dataInvoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dataInvoiceLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title py-2">รายละเอียดภาษีซื้อ</h3>
                    <button type="button" class="close" name="invid_cancel" id="invid_cancel" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body" id="invoice_detail">
                </div>
            </div>
        </div>
    </div>
    <!-- END VIEW TAX -->

    <?php include_once 'footer.php'; ?>
</body>

</html>


<script type="text/javascript">
    $(document).ready(function() {
        //------ START TAX ------//
        load_data(1);

        function load_data(page, query = '', queryDep = '', queryComp = '', queryFil = '', queryFilVal = '', querySearch = '') {
            var queryComp = $('#compid').val();
            var queryDep = $('#depid').val();
            var querySearch = $('#SearchINVVal').val();
            $.ajax({
                url: "tax_fetch.php",
                method: "POST",
                data: {
                    page: page,
                    query: query,
                    queryDep: queryDep,
                    queryComp: queryComp,
                    queryFil: queryFil,
                    queryFilVal: queryFilVal,
                    querySearch: querySearch
                },
                success: function(data) {
                    $('#invoiceShow').html(data);
                }
            });
        }

        $(document).on('click', '.page-link', function() {
            var page = $(this).data('page_number');
            var query = $('#search_box').val();
            var queryDep = $('#depid').val();
            var queryComp = $('#compid').val();
            var queryFil = $('#FilBy').val();
            var queryFilVal = $('#FilVal').val();
            var querySearch = $('#SearchINVVal').val();
            load_data(page, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
        });

        $('#search_box').keyup(function() {
            var query = $('#search_box').val();
            var queryDep = $('#depid').val();
            var queryComp = $('#compid').val();
            var queryFil = $('#FilBy').val();
            var queryFilVal = $('#FilVal').val();
            var querySearch = $('#SearchINVVal').val();
            load_data(1, query, queryDep, queryComp, queryFil, queryFilVal, querySearch);
        });


        // ------ START DELETE TAX ------//
        $(document).on('click', '.delete_data', function() {
            var del_id = $(this).attr("id");
            var element = this;
    
            swal({
                title: "ลบรายการนี้หรือไม่?",
                text: "เมื่อรายการนี้ถูกลบ คุณไม่สามารถกู้คืนได้!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "ตกลง",
                cancelButtonText: "ยกเลิก",
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-secondary',
                closeOnConfirm: false,
                closeOnCancel: false,
                dangerMode: true,
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        method: "POST",
                        url: "tax_del.php",
                        data: {
                            del_id: del_id
                        },
                        success: function(response) {   
                            swal({
                                title: "ลบข้อมูลสำเร็จ",
                                type: "success",
                            })
                            load_data(1); 
                        }   
                    });
                } else {
                    swal("ยกเลิก", "กรุณากด ตกลง เพื่อดำเนินการต่อ", "error");
                }
            })
        });
        // ------ END DELETE TAX ------//
    });
</script>