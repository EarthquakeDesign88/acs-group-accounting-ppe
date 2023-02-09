<?php

$path = realpath(dirname(__FILE__). '');

include $path.'/config/config.php';


__check_login();

if(!empty($_POST)){
	$paymid = (!empty($_POST["paymid"])) ? $_POST["paymid"] : 0;
	$taxid = (!empty($_POST["taxid"])) ? $_POST["taxid"] : 0;
	$datalist = array();
    $invtax1 = 0;
    $invtax2 = 0;
    $invtax3 = 0;
    $invtaxtotal1 = 0;
    $invtaxtotal2 = 0;
    $invtaxtotal3 = 0;

    
    if($paymid != 0) {
        $sql  = "SELECT inv_tax1, inv_tax2, inv_tax3, inv_taxtotal1, inv_taxtotal2, inv_taxtotal3 FROM payment_tb AS paym 
                 INNER JOIN invoice_tb AS i ON paym.paym_id = i.inv_paymid WHERE paym.paym_id = '" . $paymid . "'";
    }
    else {
        $sql = "SELECT inv_tax1, inv_tax2, inv_tax3, inv_taxtotal1, inv_taxtotal2, inv_taxtotal3 FROM taxcertificate_tb as t
                INNER JOIN payment_tb AS p ON t.taxc_id = p.paym_taxcid
                INNER JOIN invoice_tb AS i ON p.paym_id = i.inv_paymid
                WHERE taxc_id = '" . $_POST["taxcid"] . "'";
    }
    
    $row_data = $db->query($sql)->row();        
    $row = $db->query($sql)->result();

    for ($i=0; $i<count($row); $i++) {
        foreach ($row as $key => $value) {
            $datalist[$key]  = $value;
        }
        $invtax1 += $datalist[$i]["inv_tax1"];
        $invtax2 += $datalist[$i]["inv_tax2"];
        $invtax3 += $datalist[$i]["inv_tax3"];
        $invtaxtotal1 += $datalist[$i]["inv_taxtotal1"];
        $invtaxtotal2 += $datalist[$i]["inv_taxtotal2"];
        $invtaxtotal3 += $datalist[$i]["inv_taxtotal3"];
    }

    $tax = $invtax1 + $invtax2 + $invtax3;
    $taxtotal = $invtaxtotal1 + $invtaxtotal2 + $invtaxtotal3;

	$datalist["invtax1"] = $invtax1;
	$datalist["invtax2"] = $invtax2;
	$datalist["invtax3"] = $invtax3;
	$datalist["tax"] = $tax;
	$datalist["invtaxtotal1"] = $invtaxtotal1;
	$datalist["invtaxtotal2"] = $invtaxtotal2;
	$datalist["invtaxtotal3"] = $invtaxtotal3;
	$datalist["taxtotal"] = $taxtotal;

	

    // Loop Request
    foreach ($_POST as $key => $value) {
        $datalist[$key]  = $value;
    }


    //Add & Edit
	$datalist["comp_id"] = (!empty($_POST["compid"])) ? $_POST["compid"] : "";
	$datalist["depid"] = (!empty($_POST["depid"])) ? $_POST["depid"] : "";
    $datalist["taxctype"] = (!empty($_POST["taxctype"])) ? $_POST["taxctype"] : "";
    $datalist["taxc_income"] = (!empty($_POST["taxincome"])) ? $_POST["taxincome"] : $_POST["taxcincome"];
    $datalist["inputGroupSelectTF"] = (!empty($_POST["inputGroupSelectTF"])) ? $_POST["inputGroupSelectTF"] : "";
	$datalist["inputGroupSelectPayer"] = (!empty($_POST["inputGroupSelectPayer"])) ? $_POST["inputGroupSelectPayer"] : "";
    $datalist["searchTaxwithheld"] = (!empty($_POST["searchTaxwithheld"])) ? $_POST["searchTaxwithheld"] : "";
    $datalist["twhid"] = (!empty($_POST["twhid"])) ? $_POST["twhid"] : "";
	$datalist["paymid"] = (!empty($_POST["paymid"])) ? $_POST["paymid"] : "";
    $datalist["taxcuseridcreate"] = (!empty($_POST["taxcuseridcreate"])) ? $_POST["taxcuseridcreate"] : "";
    $datalist["taxcdatecreate"] = (!empty($_POST["taxcdatecreate"])) ? $_POST["taxcdatecreate"] : "";
    $datalist["taxcuseridedit"] = (!empty($_POST["taxcuseridedit"])) ? $_POST["taxcuseridedit"] : "";
    $datalist["taxcdateedit"] = (!empty($_POST["taxcdateedit"])) ? $_POST["taxcdateedit"] : "";


	$datalist["taxdate"] = (!empty($_POST["taxcdate"])) ? $_POST["taxcdate"] : $_POST["taxdate"]; 




	$sql_taxfil = "SELECT * FROM taxfiling_tb WHERE tf_id = '" . $datalist["inputGroupSelectTF"] . "'";
	$row_taxfil = $db->query($sql_taxfil)->row();

	foreach ($row_taxfil as $key => $value) {

		$datalist[$key]  = $value;

	}


	$sql_taxpayer = "SELECT * FROM taxpayer_tb WHERE tp_id = '" . $datalist["inputGroupSelectPayer"] . "'";
	$row_taxpayer = $db->query($sql_taxpayer)->row();

	foreach ($row_taxpayer as $key => $value) {

		$datalist[$key]  = $value;

	}


	$sql_taxwithheld = "SELECT * FROM taxwithheld_tb WHERE twh_id = '" . $datalist["twhid"] . "'";
	$row_taxwithheld = $db->query($sql_taxwithheld)->row();

	foreach ($row_taxwithheld as $key => $value) {

		$datalist[$key]  = $value;

	}


	$sql_company = "SELECT * FROM company_tb WHERE comp_id = '" . $datalist["comp_id"] . "'";
	$row_company = $db->query($sql_company)->row();

	foreach ($row_company as $key => $value) {

		$datalist[$key]  = $value;

	}

    //Set Taxcer No. for preview
	$comp_name_short = $datalist['comp_name_short'];
	if(strlen($comp_name_short) == 2) {
		$code = "xx";
	}
	else if(strlen($comp_name_short) == 3){
		$code = "xxx";
	}
	else {
		$code = "xxxx";
	}
	$datalist["taxc_no"] = (!empty($_POST["taxcno"])) ? $_POST["taxcno"] : $code."-xxxxxxx"; 


    
   $pdf = __pdf_taxcer_payment($datalist,"P");
   $pdf_preview = $pdf["preview_url"];
   $preview_path = $pdf["preview_path"];


   echo json_encode(array('preview_url' => $pdf_preview,'preview_path'=> $preview_path));
}

?>

