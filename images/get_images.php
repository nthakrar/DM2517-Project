<?php

	$file = fopen("id_title.txt", "r");
	$output_file = fopen("title_poster.txt", "w+") or die("Error opening img_urls.txt");
	if($file){
		while(!feof($file)){
			$id_title = trim(fgets($file));
			$title_exploded = explode(",", $id_title);
			$title = $title_exploded[1];
			$title = str_replace(" ", "+", $title);
			$title = trim($title);
			// echo $title."\n";
			$query = 'http://www.omdbapi.com/?t='.$title.'&r=xml';
			$result = file_get_contents($query);
			$xml_substr = substr($result,40);
			// echo $xml_substr . "\n";
			if (strpos($xml_substr,'True') !== false) {
					$xml=simplexml_load_string($result) or die("Error: Cannot create object");
					$type = $xml->movie[0]['type'];
					if($type=="series"){
						$title = $xml->movie[0]['title'];
						$poster= $xml->movie[0]['poster'];
						fwrite($output_file, $title.",".$poster."\n");
					}
					
			}
			
		}
		fclose($file);
		fclose($output_file);
	}

?>