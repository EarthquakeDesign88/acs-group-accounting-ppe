<?php
if(!empty($datalist)){
    $cheque_invnet = isset($datalist['sum_total']) ? __price($datalist['sum_total']): $datalist['invnet'];
    
    $paya_name = $datalist["paya_name"];
    $bank_name = $datalist["bank_name"];
    $cheque_no = isset($datalist['cheq_no']) ? $datalist['cheq_no'] : '';
    
    $bank_nameShort = $datalist["bank_nameShort"];
    
    if(isset($datalist['sum_total'])){
        $text_bath = __bahtthai($datalist['sum_total']);
    }else{
        $text_bath = $datalist['invnetText'];
    }
}
?>