<?php
	// Put XML content into a string
	$xmlstr = trim(ob_get_contents());
	ob_end_clean();
	// echo "!.." . $xmlstr . "..!";

	// Check if there was an error
	if (!is_null(error_get_last()))
	{
		// There was an error, print it and exit
		echo utf8_decode($xmlstr);
		exit;
	}

	// Load the XML string into a DOMDocument
	$xml = new DOMDocument;
	$xml->loadXML($xmlstr);
	// Make a DOMDocument for the XSL stylesheet
	$xsl = new DOMDocument;

	// See which user agent is connecting
	if(!empty($_POST["action"]) && $_POST["action"]=="export_excel"){
		header('Content-type: text/xml');
		$xsl->load('xsl/user-excel.xsl');
		
	}else{
		header('Content-type: text/html');
		$xsl->load('xsl/user-html.xsl');
	}
	
	// header('Content-type: text/html');
	// header('Content-type: text/xml');
	
	// $xsl->load('user-html.xsl');
	// $xsl->load('user-excel.xsl');


	// Make the transformation and print the result
	$proc = new XSLTProcessor;
	$proc->importStyleSheet($xsl); // Attach the XSL rules
	echo utf8_decode($proc->transformToXML($xml));
?>
