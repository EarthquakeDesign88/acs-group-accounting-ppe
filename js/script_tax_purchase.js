var index_add_input = 1

var add_input_i = 0;

$(document).ready(function() {

    if(document.querySelectorAll("#multi_input tr").length === 0) {

        $(".result_add").hide()

    }



    $("#add_tax_input").click(function(){

        add_input("create");

        $("#btnInsert_tax").removeAttr("disabled")

    });



    $("#update_tax_input").click(function(){

        add_input("update");

    });



    $("#btnInsert_tax").click(function(){

        data_input("create");

    });



    $("#btnUpdate_tax").click(function(){

        data_input("update");

    });



    // var count_tr = $("#multi_input tr .t_index");

    // for(let i = 0; i < count_tr.length; i++){

    //     index_add_input = i + 1

    //     $(count_tr[i]).text(index_add_input)

    // }



});



function count_tr(){

    var c_tr = $("#multi_input tr .t_index");

    for(let i = 0; i < c_tr.length; i++){

        num = i + 1

        $(c_tr[i]).text(num)

    }

}



function _resultall(){

    var row_all = $("#multi_input tr");

    var data_price;

    var data_vat;

    var data_result;

    var sum = 0.00;

    var sum2 = 0.00;

    var sum3 = 0.00;

    for(let i = 0; i < row_all.length; i++){

        data_price = row_all[i].children[5].children[0];

        data_vat = row_all[i].children[6].children[0];

        // data_result= row_all[i].children[7].children[0];

        r_price = $(data_price).val();

        r_vat = $(data_vat).val();

        // r_result = $(data_result).val();



        if(r_price == ''){

            sum += 0;

        }else{

            sum += parseFloat(r_price);

        }



        if(r_vat == ''){

            sum2 += 0 ;

        }else{

            sum2 += parseFloat(r_vat);

        }



    }



    result_price = sum.toFixed(2);

    result_vat = sum2.toFixed(2);

    result_all = parseFloat(result_price) + parseFloat(result_vat);

    all = result_all.toFixed(2);

    $("#price_nf").val(result_price);

    $("#vat_nf").val(result_vat);

    $("#result_nf").val(all);

    $("#price_all").val(Comma(result_price));

    $("#vat_all").val(Comma(result_vat));

    $("#result_all").val(Comma(all));

}





function back_input(){

    $("#tax_data").css({display:"block"})

    $("#tax_preview").html("")

}



function data_input(mode){



    let data_count = document.querySelectorAll("#multi_input tr")



    let arr = [];

    let data = {};

    // let regex = /^[0-9]+$/;



    if($("#searchCompany").val() === ""){

        swal({

            title: "กรุณากรอกชื่อ-นามสกุล / ชื่อบริษัท",

            text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",

            type: "warning",

            closeOnClickOutside: false

        })

        return

    }



    if($("#invdate").val() === ""){

        swal({

            title: "กรุณาเลือกวันที่",

            text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",

            type: "warning",

            closeOnClickOutside: false

        })

        return

    }



    if($("#searchTax").val() === ""){

        swal({

            title: "กรุณากรอกเลขประจำตัวผู้เสียภาษีอากร",

            text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",

            type: "warning",

            closeOnClickOutside: false

        })

        return

    }



    // if(!$("#searchTax").val().match(regex)){

    //     swal({

    //         title: "กรุณากรอกเลขประจำตัวเป็นตัวเลข",

    //         text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",

    //         type: "warning",

    //         closeOnClickOutside: false

    //     })

    //     $("#searchTax").val("");

    //     return

    // }



    for(let i = 0; i < data_count.length; i++){

        data['id'] = data_count[i].attributes[0].value

        for(let j = 0; j < data_count[i].children.length; j++){

            if(j === 0 ||j === 8) continue

            let _att = data_count[i].children[j].children[0].attributes[2].value

            data[_att] = data_count[i].children[j].children[0].value

            if(data[_att] === ""){

                swal({

                    title: "กรุณากรอกข้อมูลให้ครบ",

                    text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",

                    type: "warning",

                    closeOnClickOutside: false

                })

                return

            }

        } 

        arr.push(data);

        data = {}

    }



    if(mode === "create"){

        preview(arr,"create")

    }else if(mode === "update"){

        preview(arr,"update")

    }

    

}



function preview(obj,mode){

    let comp_name = $("#searchCompany2").val()

    let comp_name2 = $("#searchCompany").val()

    let date = $("#invdate").val();

    let tax_no = $("#searchTax").val()

    f_date = convertDateThai(date,"head")

    html = ''

    html += `

    <div class="container-fluid px-10">



        <div class="container-fluid">

            <form method="POST" action="">				

                <div class="row py-4 px-1" style="background-color: #E9ECEF">

                    <div class="col-md-12">

                        <h3 class="mb-0">

                            <i class="icofont-search-document"></i>&nbsp;&nbsp;Preview ใบภาษีซื้อ

                        </h3>

                    </div>

                </div>

                <div class="mt-5 mx-2">

                    <h4 class="text-center">รายงานภาษีซื้อ</h4>

                    <h4 class="text-center">เดือน ${f_date}</h4>

                    <div class="row mt-4">

                        <div class="col-md-4">

                            <span>ชื่อผู้ประกอบการ</span>

                        </div>

                        <div class="col-md-4">

                            <span>${comp_name}</span>

                        </div>

                        <div class="col-md-4">

                            <span>เลขประจำตัวผู้เสียภาษีอากร ${tax_no}</span>

                        </div>

                    </div>

                    <div class="row mt-4">

                        <div class="col-md-4">

                            <span>ชื่อผู้สถานประกอบการ</span>

                        </div>

                        <div class="col-md-4">

                            <span>${comp_name2}</span>

                        </div>

                        <div class="col-md-4">

                            <span>สำนักงานใหญ่</span>

                        </div>

                    </div>

                </div>

                <div class="row py-4 px-1" style="background-color: #FFFFFF">

                    <div class="col-md-12">

                    <table class="table mb-0">

                        <thead class="thead-light">

                            <tr>

                                <th width="3%" class="text-center">ลำดับ</th>

                                <th width="10%" class="text-center">วันที่</th>

                                <th width="10%" class="text-center">เล่มที่/เลขที่</th>

                                <th width="47%" class="text-center">ชื่อผู้ซื้อ/ชื่อผู้รับบริการ/รายการ</th>

                                <th width="10%" class="text-center">มูลค่าสินค้า</th>

                                <th width="10%" class="text-center">ภาษีมูลค่าเพิ่ม</th>

                                <th width="10%" class="text-center">รวม</th>

                            </tr>

                        </thead>

                        <tbody>`

                        let price_all = 0

                        let vat_all = 0

                        let result_all = 0

                        for(let j = 0; j < obj.length; j++){

                            price_all += Number(obj[j]['price_input']);

                            vat_all	+= Number(obj[j]['vat_input']);

                            price_f = parseFloat(obj[j]['price_input']);

                            pf = price_f.toFixed(2)

                            vat_f = parseFloat(obj[j]['vat_input']);

                            vf = vat_f.toFixed(2);

                            result_f = parseFloat(obj[j]['result_input']);

                            rf = result_f.toFixed(2)

                            f_date_t = convertDateThai(obj[j]['date_input'],"")



                            list_decs = 

                            html += `<tr>

                                <td width="3%" class="text-center">${j+1}</td>

                                <td width="10%" class="text-center">${f_date_t}</td>

                                <td width="10%" class="text-center">${obj[j]['book_number_input']}</td>

                                <td width="47%"><span>${obj[j]['company_input']}</span>&nbsp;/&nbsp;<span>${obj[j]['list_input']}</span></td>

                                <td width="10%" class="text-center">${Comma(pf)}</td>

                                <td width="10%" class="text-center">${Comma(vf)}</td>

                                <td width="10%" class="text-center">${Comma(rf)}</td>

                            </tr>`

                        }		

                            f_price = price_all.toFixed(2)

                            f_vat = vat_all.toFixed(2)

                            result_all = price_all + vat_all;

                            f_result_all = result_all.toFixed(2)

                            obj['price_a'] = f_price

                            obj['vat_a'] = f_vat

                            obj['result_a'] = f_result_all



                            html += `<tr>

                                <td colspan="4" class="text-center text-danger border-right border-left-0 border-bottom-0 border-right-0">รวมยอด</td>

                                <td width="10%" class="text-center text-danger">${Comma(f_price)}</td>

                                <td width="10%" class="text-center text-danger">${Comma(f_vat)}</td>

                                <td width="10%" class="text-center text-danger">${Comma(f_result_all)}</td>

                            </tr>

                        </tbody>

                    </table>

                    </div>

                </div>





                <hr width="100%" style="background-color: #000; height: 3px; margin: 5px 0px;">



                </div>



                <div class="row py-4 px-1" style="background-color: #FFFFFF">

                    <div class="col-md-12 pb-4 text-center">

                        <a onclick="back_input()" class="btn btn-warning px-5 py-2 mx-1">ย้อนกลับ</a>



                        <input type="button" class="btn btn-success px-5 py-2 mx-1" name="btnPrint" id="btn_save_tax" value="บันทึก">

                    </div>

                </div>



            </form>

        </div>



    </div>`;



    if(mode === "create"){

        $("#tax_data").css({display:"none"})

        $("#tax_preview").append(html)

    

        $("#btn_save_tax").click(function(){

            save_input(obj,"create")

        })

    }else if(mode === "update"){

        $("#tax_data").css({display:"none"})

        $("#tax_preview").append(html)



        $("#btn_save_tax").click(function(){

            save_input(obj,"update")

        })

    }



}



function del_input(id){

    let index = document.querySelectorAll("#multi_input tr")



    for(let i = 0; i < index.length; i++){

        if(index[i].id == id){

            index[i].remove()

        }

    }

    count_tr();

    _resultall();

    if(document.querySelectorAll("#multi_input tr").length === 0) {

        $("#btnInsert_tax").attr('disabled','disabled')

    }

}



function uuidv4() {

    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>

        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)

    );

}



function add_input(mode){

    let id = uuidv4()

    var c_tr = $("#multi_input tr");



    if(mode === "update"){

        index_add_input++

    }

    if(c_tr.length === 0){

        index_add_input = 1

    }

    if(c_tr.length > 0){

        index_add_input = c_tr.length + 1

    }



    html = ''

    html += `

    <tr id=${id}>

        <td width="5%" class="text-center t_index">

            ${index_add_input++}

        </td>

        <td width="9%" class="text-center">

            <input type="date" class="form-control _multi" name="date_input" autocomplete="off">

        </td>

        <td width="14%" class="text-center">

            <input type="text" class="form-control _multi" name="book_number_input" autocomplete="off">

        </td>

        <td width="18%" class="text-center">

            <input type="text" class="form-control _multi search_list_desc" name="list_input" autocomplete="off">

            <div class="list-group" id="list${add_input_i}"></div>

        </td>

        <td width="18%" class="text-center">

            <input type="text" class="form-control _multi search_paya_row" name="company_input" autocomplete="off">

            <div class="list-group" id="show${add_input_i}"></div>

        </td>

        <td width="9%" class="text-center">

            <input type="text" class="form-control _multi text-right" name="price_input" autocomplete="off" value="0.00">	

        </td>

        <td width="9%" class="text-center">	

            <input type="text" class="form-control _multi text-right" name="vat_input" autocomplete="off" value="0.00">

        </td>

        <td width="14%" class="text-center">

            <input type="text" class="form-control _multi text-right" name="result_input" autocomplete="off" value="0.00" readonly>

        </td>

        <td width="4%" class="text-center t_index_del">

            <a class="btn btn-danger" onclick="del_input('${id}');"><i class="icofont-ui-delete"></i></a>

        </td>

        <td width="0%">

            <input type="hidden" class="paya_id _multi" name="paya_id" value=""/>

        </td>

    </tr>`

    add_input_i++



    $("#multi_input").append(html);		



    if(document.querySelectorAll("#multi_input tr").length > 0) {

        $(".result_add").show()

    }

}



function save_input(obj,mode){

    let data = {}

    data['comp_id'] = $("#invcompid").val();

    data['dep_id'] = $("#invdepid").val();

    

    if(mode === "create"){

        data['data'] = obj

        data['date'] = $("#invdate").val();

        data['tax_no'] = $("#searchTax").val();

        data['comp_id2'] = $("#searchCompany").val();

        data['price_all'] = data['data'].price_a

        data['vat_all'] =   data['data'].vat_a

        data['result'] = data['data'].result_a

        data['action'] = "create";

        $.ajax({

            url: 'tax_save.php',

            type: 'post',

            data: data,

            cache: false,

            beforeSend: function(){

                window.swal({

                    title: "กำลังตรวจสอบข้อมูล...",

                    text: "กรุณารอสักครู่",

                    imageUrl: "image/loading.gif",

                    showConfirmButton: false,

                    allowOutsideClick: false

                });

            },

            success: function(result){

                if (result.message == "success") {

                    swal({

                        title: "บันทึกข้อมูลสำเร็จ",

                        text: "เลขที่ใบภาษีซื้อ : " + result.tax_no,

                        type: "success",

                        closeOnClickOutside: false

                    }, function() {

                        window.location.href = 'tax_input_page.php?cid='+data['comp_id']+'&dep='+data['dep_id']

                    });

                }

            },

            error: function(error){

                console.log(error)

            }

        })

    }else if(mode === "update"){

        data['data'] = obj

        data['tax_id'] = $("#taxid").val();

        data['tax_no'] = $("#searchTax").val();

        data['date'] = $("#invdate").val();

        // data['comp_id2'] = $("#invcompid2").val();

        // data['list_id'] = $("#multi_input tr").attr("id");

        data['price_all'] = data['data'].price_a

        data['vat_all'] = data['data'].vat_a

        data['result_all'] = data['data'].result_a

        data['dep_name'] = $("#tax_dep_name").val();

        data['del_list'] = del_list;

        data['action'] = "update";

        $.ajax({

            url: 'tax_save.php',

            type: 'post',

            data: data,

            cache: false,

            beforeSend: function(){

                window.swal({

                    title: "กำลังตรวจสอบข้อมูล...",

                    text: "กรุณารอสักครู่",

                    imageUrl: "image/loading.gif",

                    showConfirmButton: false,

                    allowOutsideClick: false

                });

            },

            success: function(result){

                if (result.message == "success") {

                    swal({

                        title: "บันทึกข้อมูลสำเร็จ",

                        text: "แก้ไขเลขที่ใบภาษีซื้อ : " + result.tax_id,

                        type: "success",

                        closeOnClickOutside: false

                    }, function() {

                        window.location.href = 'tax_input_page.php?cid='+data['comp_id']+'&dep='+data['dep_id']

                    });

                }

            },

            error: function(error){

                console.log(error)

            }

        })

    }



}



function convertDateThai(date,mode){

    

    if(mode === 'head'){

        month_th = [

            "",

            "มกราคม", 

            "กุมภาพันธ์", 

            "มีนาคม",

            "เมษายน",

            "พฤษภาคม",

            "มิถุนายน",

            "กรกฎาคม",

            "สิงหาคม",

            "กันยายน",

            "ตุลาคม",

            "พฤศจิกายน",

            "ธันวาคม"

        ];

    }else{

        month_th = [

            "",

            "ม.ค.", 

            "ก.พ.", 

            "มี.ค.",

            "เม.ย.",

            "พ.ค.",

            "มิ.ย.",

            "ก.ค.",

            "ส.ค.",

            "ก.ย.",

            "ต.ค.",

            "พ.ย.",

            "ธ.ค."

        ];

    }



    data = date.split("-");

    y = data[0]

    m = month_th[Number(data[1])]

    d = Number(data[2])



    if(mode !== 'head'){

        y = y.substr(2)

    }



    if(mode === 'head'){

        return `${m} ${y}`

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





