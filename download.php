<?php

	if(isset($_GET['filename'])) {

		$filename = $_GET['filename'];

		if (file_exists($filename)) {

			//Define header information
			// header('Cache-control: private');
			// header('Content-type: application/force-download') ;
			// header('Content-transfer-encoding: binary\n');
			// header('Content-Disposition: attachment; filename="'.basename($filename).'"');
			// header('Content-Length: ' . filesize($filename));
			// header('Pragma: public');

			// flush();

			// readfile($filename,true);

			// die();

			header('Content-Description: File Transfer');
			header('Content-Type: application/force-download');
			header('Content-Disposition: attachment; filename="'.basename($filename).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filename));
			readfile($filename);
			exit;

		} else{
			echo "File path does not exist.";
		}
	} else {
		echo "File path is not defined.";
	}

?>