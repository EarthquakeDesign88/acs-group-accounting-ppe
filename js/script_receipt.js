
function get_bank(){
    $.ajax({
        type: "post",
        url: "receipt_filter.php",
        data: {
            "action" : "bank"
        },
        dataType:"json",
        success: function(response){
            let data = response.data;
            let html = '';
                html += `<option value="" selected disabled>
                            กรุณาเลือกธนาคาร...
                        </option>`
                for(let i = 0; i < data.length; i++){
                    html += `<option value="${data[i]['bank_id']}">
                                ${data[i]['bank_name']}
                            </option>`
                }
                $("#SelBank").append(html)

        }
    })
}

function get_branch(id){
    $("#SelBranch").html("")
    $.ajax({
        type: "post",
        url: "receipt_filter.php",
        data: {
            "action" : "branch",
            id
        },
        dataType:"json",
        success: function(response){
            let data = response?.data;
            console.log(data)
            let html = '';
            // if(data === undefined){
            //     html += `<option value="" selected disabled>ไม่มีสาขา</option>`
            //     $("#SelBranch").append(html)
            //     return
            // }
            
            for(let i = 0; i < data.length; i++){
                html += `<option value="${data[i]['brc_id']}">
                            ${data[i]['brc_name']}
                        </option>`
            }
            $("#SelBranch").append(html)
            // $("#SelBranch").attr("disabled", false)
        }
    })
}

function savePay(type){
    let data = $('.filter_pay')
    let check_data = {};
    
    if(type == "save_pay"){
        let check_type = $("input[name=bySelPay]:checked").val();
        if(check_type === undefined){
            swal({
                title: "กรุณาเลือกวิธีการชำระเงินก่อน",
                text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                type: "warning",
                closeOnClickOutside: false
            })
            return
        }
        check_data['bySelPay'] = check_type
    }


    for(let i = 0; i < data.length; i++){
        var _attr = $(data[i]).attr('name');
        var _val = $(data[i]).val();
        check_data[_attr] = _val;
    }
    
    if(type == "cancel"){
        if($("#ReNoteCancel").val() === ""){
            swal({
                title: "กรุณากรอกหมายเหตุ",
                text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                type: "warning",
                closeOnClickOutside: false
            })
            return
        }
        
        check_data['ReNoteCancel'] = $("#ReNoteCancel").val()
    }
    
    if(type == "save_pay"){
        $.ajax({
            type: "post",
            url: "receipt_filter.php",
            data: {
                action : "save_pay",
                check_data
            },
            dataType:"json",
            success: function(response){
                if (response) {
                    swal({
                        title: "บันทึกข้อมูลสำเร็จ",
                        text: "กรุณากด ตกลง เพื่อดำเนินการต่อ",
                        type: "success",
                        closeOnClickOutside: false
                    })
                }
                
                
            }
        })
    }else{
        $.ajax({
            type: "post",
            url: "r_receipt_cancel.php",
            data: {
                check_data
            },
            dataType:"json",
            success: function(response){
                if (response.status == 1) {
                    swal({
                        title: "ยกเลิกใบเสร็จรับเงินสำเร็จ",
                        text: "เลขที่ใบเสร็จรับเงิน" + response.Receiptno,
                        type: "success",
                        closeOnClickOutside: false
                    })
                }
            }
        })
    }

}


function modalContent(id){
    html = '';
    html += `<div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="checkbox">
                                    <input type="radio" class="check_pay" name="bySelPay" id="byCash" value="1">
                                    <label for="byCash" class="mb-1">
                                        <span>เงินสด</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="checkbox">
                                    <input type="radio" class="check_pay" name="bySelPay" id="byTransfer" value="3">
                                    <label for="byTransfer" class="mb-1">
                                        <span>โอนเข้าบัญชี</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="checkbox">
                                    <input type="radio" class="check_pay" name="bySelPay" id="byCheque" value="2">
                                    <label for="byCheque" class="mb-1">
                                        <span>เช็คเลขที่</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control my-1 filter_pay" name="chequeNo" id="chequeNo" autocomplete="off" value="" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12" id="SelectBankBranch">
                            <label class="my-1">ธนาคาร</label>
                            <div class="input-group">
                                <select class="custom-select form-control filter_pay" name="SelBank" id="SelBank" >
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" id="SelectBranch">
                            <label class="my-1">สาขา</label>
                            <div class="input-group">
                                <select class="custom-select form-control filter_pay" name="SelBranch" id="SelBranch" >
                                    <option value="" selected disabled>กรุณาเลือกสาขา...</option>
                                </select>
                            </div>
                            <input type="text" class="form-control d-none filter_pay" name="re_id" value="${id}">
                        </div>
                        <div class="col-md-12">
                            <label class="my-1">วันที่</label>
                            <div class="input-group">
                                <input type="date" class="form-control filter_pay" name="chequeDate" id="chequeDate">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="my-1">หมายเหตุ</label>
                            <textarea class="form-control filter_pay" name="ReNote" id="ReNote" rows="2" placeholder="หมายเหตุ" autocomplete="off"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>`

    $(".modal-body-action").append(html)
}

