<?php
    if(!empty($datalist)){
        $taxc_no = (!empty($datalist["taxc_no"])) ? $datalist["taxc_no"] : "";
        $comp_taxno = (!empty($datalist["comp_taxno"])) ? $datalist["comp_taxno"] : "";
        $comp_name = (!empty($datalist["comp_name"])) ? $datalist["comp_name"] : "";
        $comp_address = (!empty($datalist["comp_address"])) ? $datalist["comp_address"] : "";
        $twh_taxno = (!empty($datalist["twh_taxno"])) ? $datalist["twh_taxno"] : "";
        $twh_name = (!empty($datalist["twh_name"])) ? $datalist["twh_name"] : "";
        $twh_address = (!empty($datalist["twh_address"])) ? $datalist["twh_address"] : "";
        $invtax1 = (!empty($datalist["invtax1"])) ? $datalist["invtax1"] : "";
        $invtax2 = (!empty($datalist["invtax2"])) ? $datalist["invtax2"] : "";
        $invtax3 = (!empty($datalist["invtax3"])) ? $datalist["invtax3"] : "";
        $invtax = (!empty($datalist["tax"])) ? $datalist["tax"] : "";
        $invtaxtotal1 = (!empty($datalist["invtaxtotal1"])) ? $datalist["invtaxtotal1"] : "";
        $invtaxtotal2 = (!empty($datalist["invtaxtotal2"])) ? $datalist["invtaxtotal2"] : "";
        $invtaxtotal3 = (!empty($datalist["invtaxtotal3"])) ? $datalist["invtaxtotal3"] : "";
        $taxtotal = (!empty($datalist["taxtotal"])) ? $datalist["taxtotal"] : "";
        $taxc_income = (!empty($datalist["taxc_income"])) ? $datalist["taxc_income"] : "";

        $taxc_tfid = (!empty($datalist["tf_id"])) ? $datalist["tf_id"] : $datalist["taxc_tfid"];
        $taxc_tpid = (!empty($datalist["tp_id"])) ? $datalist["tp_id"] : $datalist["taxc_tpid"];
        $taxc_date = (!empty($datalist["taxdate"])) ? $datalist["taxdate"] : $datalist["taxc_date"];

        $taxc_useridcreate = (!empty($datalist["taxcuseridcreate"])) ? $datalist["taxcuseridcreate"] : "";
        $taxc_datecreate = (!empty($datalist["taxcdatecreate"])) ? $datalist["taxcdatecreate"] : "";
        $taxc_useridedit = (!empty($datalist["taxcuseridedit"])) ? $datalist["taxcuseridedit"] : "";
        $taxc_dateedit = (!empty($datalist["taxcdateedit"])) ? $datalist["taxcdateedit"] : "";
   

        //Set Type 
        $invtax1 = (double)$invtax1;
        $invtax2 = (double)$invtax2;
        $invtax3 = (double)$invtax3;
        $invtax = (double)$invtax;
        $invtaxtotal1 = (double)$invtaxtotal1;
        $invtaxtotal2 = (double)$invtaxtotal2;
        $invtaxtotal3 = (double)$invtaxtotal3;
        $taxtotal = (double)$taxtotal;

    }

?>