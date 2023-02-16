number = 1
arr_id_del = []
$(document).on("keyup","._result",function(){
    let _set = $(this).parent().parent()[0].children

    let set_price = $(_set[6])[0].children['price']
    let set_vat = $(_set[7])[0].children['vat']
    let set_avg_vat = $(_set[8])[0].children['avg_vat']
    let set_avg = $(_set[9])[0].children['avg_percent']

    let _price = $(set_price).val()

    let _val_vat = _price * 7 /100;
    let _vat = $(set_vat).val(_val_vat.toFixed(2))

    let _avg = $(set_avg).val()
    let _val_avg_vat = $(_vat).val() * _avg / 100

    let _avg_vat = $(set_avg_vat).val(_val_avg_vat.toFixed(2))
    result_all()
});

$(document).on("click",".del_input",function(){
    
    $(this).parent().parent().remove()
    count_all()
    result_all()

    if($("#multi_input tr").length == 0){
        $("#preview_add").attr("disabled", true)
        $(".result_add").hide()
    }
});

$(document).on('keyup', '.search_paya_row', function() {
    let parent_td = $(this).parent()
    let ele = $(this).val()
    let show_list = $(parent_td).children("div")
    if (ele != "") {
        $.ajax({
            url: "action_script_payable_all.php",
            method: "post",
            data: {
                query_payable: ele,
            },
            success: function (data) {
                $(show_list).fadeIn();
                $(show_list).html(data)
            },
        });
    } else {
        $(show_list).fadeOut();
        $(show_list).html("");
    }
})

$(document).on("click", "li.payable_row", function () {
    let text = $(this).text()
    let id_paya = $(this).attr("id")
    let taxno_paya = $(this).attr("data-type")
    let parent_ul = $(this).parent().parent().parent()
    let parent_tr = $(this).parent().parent().parent().parent()
    let ele_txt = $(parent_ul).find(".search_paya_row")
    let ele_id = $(parent_tr).find(".paya_id")
    let ele_no = $(parent_tr).find(".tax_no")
    let list_v = $(parent_ul).children("div")
    $(ele_txt).val(text)
    $(ele_id).val(id_paya)
    $(ele_no).val(taxno_paya)
    $(list_v).html("");
    $(list_v).fadeOut();
});

$(document).on('keyup', '.search_list_desc', function() {
    let parent_td = $(this).parent()
    let ele = $(this).val()
    let show_list = $(parent_td).children("div")
    if (ele != "") {
        $.ajax({
            url: "action_script_list_desc.php",
            method: "post",
            data: {
                query_list: ele,
            },
            success: function (data) {
                $(show_list).fadeIn();
                $(show_list).html(data)
            },
        });
    } else {
        $(show_list).fadeOut();
        $(show_list).html("");
    }
});

$(document).on("click", "li.list_desc_row", function () {
    let text = $(this).text()
    let parent_ul = $(this).parent().parent().parent()
    let parent_tr = $(this).parent().parent().parent().parent()
    let ele_txt = $(parent_ul).find(".search_list_desc")
    let list_v = $(parent_ul).children("div")
    $(ele_txt).val(text)
    $(list_v).html("");
    $(list_v).fadeOut();
});

function count_all(){
    let row = $("#multi_input tr");
    
    for(let i = 0; i < row.length; i++){
        $(row[i])[0].children[0].innerText = i + 1
    }

    number = row.length + 1
}

function result_all(){
    let data = $("#multi_input tr");
    let sum_price = 0 , sum_vat = 0 , sum_avg_vat = 0 

    for(let i = 0; i < data.length; i++){
        sum_price += parseFloat($(data)[i].children[6].children['price'].value)
        sum_vat += parseFloat($(data)[i].children[7].children['vat'].value)
        sum_avg_vat += parseFloat($(data)[i].children[8].children['avg_vat'].value)
    }

    price_all = sum_price.toFixed(2)
    vat_all = sum_vat.toFixed(2)
    avg_vat_all = sum_avg_vat.toFixed(2)

    $("#price_all").val(price_all)
    $("#vat_all").val(vat_all)
    $("#avg_vat_all").val(avg_vat_all)
}

function uuidv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

function addInput(){
    html = ''
    html += `
    <tr id="">
        <td>${number++}</td>
        <td>
            <input type="date" class="form-control _input" name="date"/>
        </td>
        <td>
            <input type="text" class="form-control _input" name="tax_no" autocomplete="off"/>
        </td>
        <td>
            <input type="text" class="form-control _input tax_no" name="tax_card" autocomplete="off" readonly/>
        </td>
        <td>
            <input type="text" class="form-control _input search_paya_row" name="payable" autocomplete="off"/>
            <div class="list-group"></div>
        </td>
        <td>
            <input type="text" class="form-control _input search_list_desc" name="list" autocomplete="off"/>
            <div class="list-group"></div>
        </td>
        <td>
            <input type="text" class="form-control text-right _input _result" name="price" value="0.00"/>
        </td>
        <td>
            <input type="text" class="form-control text-right _input _result" name="vat" readonly value="0.00"/>
        </td>
        <td>
            <input type="text" class="form-control text-right _input _result" name="avg_vat" readonly value="0.00"/>
        </td>
        <td>
            <input type="text" class="form-control text-right _input _result" name="avg_percent" readonly value="30.98"/>
        </td>
        <td>
            <a class="btn btn-danger del_input"><i class="icofont-ui-delete"></i></a>
        </td>
        <td>
            <input type="hidden" class="form-control _input paya_id" name="paya_id" value=""/>
        </td>	
    </tr>`

    $("#multi_input").append(html)

    if($("#multi_input tr").length > 0){
        $("#preview_add").removeAttr("disabled")
        $(".result_add").show()
    }
}

function data_input(mode){
    let data = $("#multi_input tr");
    let all_data = [];
    let check_data = {};		
    if($("#date_head").val() == ""){
        swal({
            title: "กรุณาเลือกวันที่",
            text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
            type: "warning",
            closeOnClickOutside: false
        })
        return
    }

    for(let i = 0; i < data.length; i++){
        check_data['id'] = $(data[i]).attr("id")
        for(let j = 0; j < data[i].children.length; j++){
            if(j == 0 || j == 10) continue
            let _attr = data[i].children[j].children[0].attributes[2].value
            let _val = data[i].children[j].children[_attr].value
            let _result = parseFloat(data[i].children[6].children['price'].value) + parseFloat(data[i].children[7].children['vat'].value)

            check_data[_attr] = _val
            check_data["result"] = _result.toFixed(2)

            if(check_data[_attr] === ""){
                swal({
                    title: "กรุณากรอกข้อมูลให้ครบ",
                    text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                    type: "warning",
                    closeOnClickOutside: false
                })
                return
            }
        }
        all_data.push(check_data);
        check_data = {};
    }

    preview(all_data,mode)
    
}

function preview(data,mode){
    $("#frmAddInvoice").hide();
    $("#tax_preview").html("")
    date_head = convertDateThai($("#date_head").val(),"head")
    html = ''
    html = `
    <div class="container-fluid">
        <div class="container-fluid">
            <div class="row py-4 px-1" style="background-color: #E9ECEF">
                <div class="col-md-12">
                    <h3 class="mb-0">
                        <i class="icofont-search-document"></i>&nbsp;&nbsp;Preview ใบภาษีซื้อ
                    </h3>
                </div>
            </div>
            <table class="mt-3" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="33%"></td>
                    <td width="33%" style="text-align: center; font-size: 18pt;">
                        <b>รายงานภาษีซื้อ</b>
                    </td>
                    <td width="33%" style="text-align: right; font-size: 12pt;"><strong></strong></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="text-align: center; font-size: 16pt;">
                        <b>เดือน ${date_head}</b>
                    </td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="font-size: 12pt; padding: 3px 0px;width: 100%">
                        <b>ชื่อผู้ประกอบการ &nbsp;&nbsp;&nbsp;&nbsp; บริษัท ธรรมบุรี จำกัด </b>
                    </td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="0" border="0" width="70%">
                <tr>
                    <td width="68%"></td>
                    <td class="b">0</td>
                    <td class="b">1</td>
                    <td class="b">0</td>
                    <td class="b">5</td>
                    <td class="b">5</td>
                    <td class="b">3</td>
                    <td class="b">8</td>
                    <td class="b">1</td>
                    <td class="b">0</td>
                    <td class="b">6</td>
                    <td class="b">4</td>
                    <td class="b">6</td>
                    <td class="b">1</td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="0" border="0" width="90%" style="margin-top: 10px">
                <tr>
                    <td width="55%"></td>
                    <td class="b"></td>
                    <td></td>
                    <td>สำนักงานใหญ่</td>
                    <td></td>
                    <td class="b">/</td>
                    <td></td>
                    <td>สาขาที่</td>
                    <td></td>
                    <td></td>
                    <td class="b">0</td>
                    <td class="b">0</td>
                    <td class="b">0</td>
                    <td class="b">1</td>
                </tr> 
            </table>   
            <table>
                <tr>
                    <td width="100%" style="font-size: 12pt; padding: 3px 0px">
                        <b>ชื่อสถานประกอบการ &nbsp;&nbsp;&nbsp;&nbsp; บริษัท ธรรมบุรี จำกัด </b>
                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="height: 10px"></td>
                </tr>
            </table>
            <table class="txtbody text-center" cellspacing="0" cellpadding="0" border="0" width="100%">
                <thead>
                    <tr>
                        <th rowspan="2">ลำดับ</th>
                        <th colspan="2">ใบกำกับภาษี</th>
                        <th rowspan="2">เลขประจำตัว<br>ผู้เสียภาษี</th>
                        <th rowspan="2" width="25%">ชื่อผู้ซื้อ/ผู้ให้บริการ/รายการ</th>
                        <th rowspan="2">มูลค่าสินค้า<br>หรือบริการ</th>
                        <th rowspan="2">จำนวนเงิน<br>ภาษีมูลค่าเพิ่ม</th>
                        <th rowspan="2">จำนวนเงิน<br>ภาษีที่เฉลี่ย</th>
                        <th rowspan="2">หมายเหตุ<br> % เฉลี่ย</th>
                    </tr>
                    <tr>
                        <th>วัน/เดือน/ปี</th>
                        <th>เล่มที่/เลขที่</th>
                    </tr>
                </thead>
                <tbody>`
                for(let i = 0; i < data.length; i++){
                    date_data = convertDateThai(data[i]['date'])
                    html += `
                    <tr>
                        <td>1</td>
                        <td>${date_data}</td>
                        <td>${data[i]['tax_no']}</td>
                        <td>${data[i]['tax_card']}</td>
                        <td style="text-align:left">${data[i]['payable']}</td>
                        <td style="text-align:right">${Comma(data[i]['price'])}</td>
                        <td style="text-align:right">${Comma(data[i]['vat'])}</td>
                        <td style="text-align:right">${Comma(data[i]['avg_vat'])}</td>
                        <td style="text-align:right">${data[i]['avg_percent']}%</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:left">${data[i]['list']}</td>
                        <td></td> 
                        <td></td> 
                        <td></td> 
                        <td></td>       
                    </tr>
                    <tr>`
                }

                html += `<td colspan="5" style="border: none;"></td>
                        <td style="text-align: right;"><b>${Comma($("#price_all").val())}</b></td>
                        <td style="text-align: right;"><b>${Comma($("#vat_all").val())}</b></td>
                        <td style="text-align: right;"><b>${Comma($("#avg_vat_all").val())}</b></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div class="row py-4 px-1" style="background-color: #FFFFFF">
                <div class="col-md-12 pb-4 text-center">
                    <a onclick="back_input()" class="btn btn-warning px-5 py-2 mx-1">ย้อนกลับ</a>
                    <input type="button" class="btn btn-success px-5 py-2 mx-1" id="${mode}" value="บันทึก">
                </div>
            </div>
        </div>
    </div>
    `
    $("#tax_preview").append(html)

    $("#insert").click(function(){
        insertData(data,"create_tbri")
    })

    $("#update").click(function(){
        insertData(data,"update_tbri")
    })
}

function insertData(data,mode){
    let date_head = $("#date_head").val()
    let price_all = $("#price_all").val()
    let vat_all = $("#vat_all").val()
    let sum = parseFloat(price_all) + parseFloat(vat_all)
    let tax_result_all = sum.toFixed(2)
    let result_all = $("#avg_vat_all").val()

    let set_data = {
        action: mode,
        data,
        date_head,
        price_all,
        vat_all,
        result_all,
        tax_result_all
    }

    if (mode === "update_tbri"){
        set_data.tax_id = $("#tax_id").val()
        set_data.del_id = arr_id_del
    }

    $.ajax({
        url: "tax_save.php",
        method: "post",
        data: set_data,
        // cache: false,
        // beforeSend: function(){
        //     window.swal({
        //         title: "กำลังตรวจสอบข้อมูล...",
        //         text: "กรุณารอสักครู่",
        //         imageUrl: "image/loading.gif",
        //         showConfirmButton: false,
        //         allowOutsideClick: false
        //     });
        // },
        success: function(response){
            if (response.message == "success") {
                swal({
                    title: "บันทึกข้อมูลสำเร็จ",
                    text: "เลขที่ใบภาษีซื้อ : " + response.tax_id,
                    type: "success",
                    closeOnClickOutside: false
                }, function() {
                    window.location.href = 'tax_input_page.php?cid=C009&dep=D013'
                });
            }
        }
    })
}

function back_input(){
    $("#tax_preview").html("");
    $("#frmAddInvoice").show();
}

function convertDateThai(date,mode = ""){

    if(mode === 'head'){

        month_th = ["","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"];

    }else{

        month_th = ["","ม.ค.", "ก.พ.", "มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค."];
    }

    data = date.split("-");
    y = data[0]
    m = month_th[Number(data[1])]
    d = Number(data[2])

    if(mode !== 'head'){
        y = y.substr(2)
    }

    if(mode === 'head'){
        return `${m} ${Number(y) + 543}`
    }
    return `${d} ${m} ${y}`
}

function Comma(Num) {
    Num += '';
    Num = Num.replace(/,/g, '');
    x = Num.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1))
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    return x1 + x2;
}

function del_data(id){
    arr_id_del.push(id)
}