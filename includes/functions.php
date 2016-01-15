<?php
	include_once 'config.php';
	/*
		Called in admin_add_series_id_table.php to add entries to series_id table for parsing rss
	*/
	function db_add_series_id_table($mysqli){
		$file=fopen("http://xml.csc.kth.se/~thakrar/DM2517-Project/images/id_title_poster.txt", "r");
		if($file){
			while(!feof($file)){
				
				$id_title_poster = trim(fgets($file));
				$exploded = explode(",", $id_title_poster);
				$id = $exploded[0];
				$title = $exploded[1];
				$id = trim($id);
				$title = trim($title);
				
				// echo $id . "," . $title;
				
				if ($insert_stmt = $mysqli->prepare("INSERT INTO series_id (id, name) VALUES (?, ?)")) {
					$insert_stmt->bind_param('ss', $id, $title);
					$insert_stmt->execute();
				}else{printf("Errorcode: %d\n", $mysqli->errno);}
			}
			
			fclose($file);	
		}
			
	}
	function calendar($mysqli, $userid){
		$ids=array();
		$query = "SELECT * FROM subsribes WHERE user_id='".$userid."'";
		if($result = $mysqli->query($query)){
			
			while($row = $result->fetch_assoc()){
				$title = $row["title"];
				// echo $title;
				$query2="SELECT id FROM series_id WHERE name='".$title."'";
				if($result2 = $mysqli->query($query2)){
					while($row2 = $result2->fetch_assoc()){
						$id = $row2["id"];
						$ids[$id]=$title;
						// echo $id;
						
					}
				}
			}
		}
		echo parse_showrss($ids);
	}
	function parse_showrss($ids){
		$arrlength = count($ids);
		$arr_return = array();

		$arr_top = array();
		foreach($ids as $key => $value){
			// echo $ids[$x]."\n";
			
			$arr_middle=array();
			
			$toquery = 'http://showrss.info/feeds/'.$key.'.rss';
			$query = file_get_contents($toquery);
			$xml=simplexml_load_string($query) or die("Error:parse_showrss");
			
			foreach($xml->channel->item as $item){
				
				$pair = array(
					"episode"=>$item->title,
					"date"=>$item->pubDate
					
				);
				$arr_middle[]=$pair;
			}
			$arr_top[$value]=$arr_middle;
		}
		return json_encode($arr_top);
	}
	function sec_session_start(){
		$session_name = 'sec_session_id';
		$httponly = true;
		$secure = SECURE;
		if(ini_set('session.use_only_cookies', 1)==FALSE){
			//header("Location: ../error.php?err=Could not instantiate a safe session (ini_set)");
			exit();
		}
		//Get current cookies params
		$cookieparams = session_get_cookie_params();
		session_set_cookie_params(
			$cookieparams["lifetime"],
			$cookieparams["path"],
			$cookieparams["domain"],
			$secure,
			$httponly);
			
		session_name($session_name);
		session_start();
		session_regenerate_id(true);
			
	
	}
	
	function login($user_id, $password, $mysqli){
		if($stmt = $mysqli -> prepare("SELECT password, salt FROM users WHERE user_id=? LIMIT 1")){
			/*	corresponding variable has type string
				eg. $stmt->bind_param('sssd', $code, $language, $official, $percent);*/
			$stmt->bind_param('s', $user_id);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($db_password,$salt);
			$stmt->fetch();
			
			 // hash the password with the unique salt.
			$password = hash('sha512', $password . $salt);
			if($stmt->num_rows==1){
				//check if the password in the database matches the password handed by the user
				if($db_password == $password){
					
					// $user_browser = $_SERVER['HTTP_USER_AGENT'];
					$_SESSION['user_id'] = $user_id;
					$_SESSION['login_string'] = $password;
					//OK to use user_browser as a salt as it's rare that a user would change the browser mid using the webpage
					//$_SESSION['login_string'] = hash('sha512', $password . $user_browser);
					
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}	
		

		}
		
	}
	/*
		Checking login status by checking the user_id and login_string saved in the global SESSION variable.
		The "login_string" SESSION variable has the user's browser information hashed together with the password. We use the browser information because it is very unlikely that the user will change their browser mid-session. Doing this helps prevent session hijacking.
	*/
	function login_check($mysqli){
		if (isset($_SESSION['user_id'], $_SESSION['login_string'])) {
			$user_id = $_SESSION['user_id'];
			$login_string = $_SESSION['login_string'];
			
			
			//$user_browser = $_SERVER['HTTP_USER_AGENT'];
			
			if($stmt=$mysqli->prepare("SELECT password FROM users WHERE user_id = ? LIMIT 1")){
				$stmt->bind_param('s', $user_id);
				$stmt->execute();
				$stmt->store_result();
				
				if($stmt->num_rows==1){
					$stmt->bind_result($password);
					$stmt->fetch();
					
					$_SESSION['login_check'] = $password;
					$login_check = $_SESSION['login_check'];
					
					$stmt->close();
					if($login_check == $login_string){
						  
						return true;
					}else{
						return false;
					}	
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	function db_query_series_json($mysqli, $title){
		$myArray=array();
		$query = "SELECT * FROM series WHERE title='".$title."'";
		if($result = $mysqli->query($query)){
			while($row = $result->fetch_assoc()){
				array_push($myArray, $row);
			}
			
			echo json_encode($myArray);
		}
		
	}
	function xml_entities($s) {
		return str_replace(array('&','>','<','"', "'"), array('&amp;','&gt;','&lt;','&quot;','&apos;'), $s);
	}
	function xmlsafe($s,$intoQuotes=1) {
		if ($intoQuotes)
			 return str_replace(array('&','>','<','"', '\''), array('&amp;','&gt;','&lt;','&quot;','&apos;'), $s);
		else
			 return str_replace(array('&','>','<'), array('&amp;','&gt;','&lt;'), html_entity_decode($s));
	}
	function db_query_series($mysqli, $where_clause){
		
		if(empty($where_clause)){
			
			$query = "SELECT * FROM series";
		}else{
			$where_clause=trim($where_clause);
			$query = "SELECT * FROM series WHERE title='".$where_clause."'";
		}
		
		if($result = $mysqli->query($query)){
			
			$return_str="";
			while($row = $result->fetch_assoc()){
				$title = $row["title"];
				// $title = xmlsafe($title);
				$poster = $row["poster"] ;
				$poster = substr($poster,34);
				$poster=image_exists($poster);
				$plot = $row["plot"];
				$plot = xml_entities($plot);
				
				$return_str .= "<series>";
				// echo $row["title"];
				$return_str .= '<title>'.$title.'</title>';
				$return_str .= '<year>'.$row["year"].'</year>';
				$return_str .= '<rated>'.$row["rated"].'</rated>';
				$return_str .= '<released>'.$row["released"].'</released>';
				$return_str .= '<runtime>'.$row["runtime"].'</runtime>';
				$return_str .= '<genre>'.$row["genre"].'</genre>';
				$return_str .= '<director>'.$row["director"].'</director>';
				$return_str .= '<actors>'.$row["actors"].'</actors>';
				$return_str .= '<plot>'.$plot.'</plot>';
				$return_str .= '<language>'.$row["language"].'</language>';
				$return_str .= '<country>'.$row["country"].'</country>';
				// $return_str .= '<awards>'.$row["awards"].'</awards>';
				$return_str .= '<poster>'.$poster.'</poster>';
				
				$return_str .= "</series>";
			}
			mysqli_free_result($result);	
			return $return_str;
		}
	}
	
	function image_exists($poster){
		if(!empty($poster) && file_exists("images/".$poster)){
			$poster="images/".$poster;
		}else{
			$poster="images/default_image.png";
		}
		return $poster;
	}
	function db_query_series_whats_trending($mysqli, $where_clause){
		if(empty($where_clause)){
			
			$query = "SELECT * FROM series";
		}else{
			$where_clause=trim($where_clause);
			$query = "SELECT * FROM series WHERE title='".$where_clause."'";
		}
		// echo "query: " . $query . "\n";
		if($result = $mysqli->query($query)){
			
			$return_str="";
			while($row = $result->fetch_assoc()){
				
				$year = $row["year"];
				$year_exploded= explode("-", $year);
				$start_year = $year_exploded[0];
				
				if(intval($start_year)==2015){
					$title = $row["title"];
					$poster = $row["poster"] ;
					$poster = substr($poster,34);
					$poster=image_exists($poster);
					$return_str .= "<series>";
					$return_str .= '<title>'.$title.'</title>';
					$return_str .= '<year>'.$year.'</year>';
					$return_str .= '<rated>'.$row["rated"].'</rated>';
					$return_str .= '<released>'.$row["released"].'</released>';
					$return_str .= '<runtime>'.$row["runtime"].'</runtime>';
					$return_str .= '<genre>'.$row["genre"].'</genre>';
					$return_str .= '<director>'.$row["director"].'</director>';
					$return_str .= '<actors>'.$row["actors"].'</actors>';
					$return_str .= '<plot>'.$row["plot"].'</plot>';
					$return_str .= '<language>'.$row["language"].'</language>';
					$return_str .= '<country>'.$row["country"].'</country>';
					$return_str .= '<poster>'.$poster.'</poster>';
					// $return_str .= '<awards>'.$row["awards"].'</awards>';
					
					
					$return_str .= "</series>";
				}
				
			}
			mysqli_free_result($result);	
			return $return_str;
		}
		

	}
	
	function db_query_user_subscription_series($mysqli){
		$user_id = $_SESSION["user_id"];
		$query = "SELECT DISTINCT title FROM subsribes WHERE user_id='".$user_id."'";
		$return_str="";
		if($result = $mysqli->query($query)){
			
			while($row = $result->fetch_assoc()){
				$title=$row["title"];
				 
				$return_str.=db_query_series($mysqli, $title);
				if(empty($return_str)){
					echo "<p>EMPTY</p>";
					// $return_str="EMPTY";
				}
				
			}
			mysqli_free_result($result);	
			
		}
		
		return $return_str;
		
	}
	function query_omdb($mysqli){
		mb_internal_encoding("UTF-8");
		$file=fopen("http://xml.csc.kth.se/~thakrar/DM2517-Project/images/id_title.txt", "r");
		$title = "";
		$result = "xml";
		
		if($file){
			
			while(!feof($file)){
				$is_true = false;
				$id_title = trim(fgets($file));
				$title_exploded = explode(",", $id_title);
				$title = $title_exploded[1];
				$title = str_replace(" ", "+", $title);
				// echo "title to query: ".$title.".";
				// $title = "glee";
				
				$toquery = 'http://www.omdbapi.com/?t='.$title.'&r=xml';
				// echo $toquery."</br>";
				
				$query = file_get_contents($toquery);
				$xml_substr = substr($query,40);
				// echo "</br>substr: $xml_substr</br>";
				if (strpos($xml_substr,'True') !== false) {
					// echo "Is true! </br>";
					$is_true=true;
				}
				
				$xml=simplexml_load_string($query) or die("Error: Cannot create object");
				// var_dump($xml);
				// $status = $xml->root[0];
				$type = $xml->movie[0]['type'];
				
				// echo $title." has type: ". $type."</br>";
				
				if($is_true && $type=="series"){
					// echo "root response=true && type == series</br>";
					db_add_series($xml, $mysqli);
					// break;
				}				
			}
		}
		fclose($file);
		
	}
	function db_add_series($xml, $mysqli){
		mb_internal_encoding("UTF-8");
		$title = $xml->movie[0]['title'];
		// $title = xml_entities($title);
		$year=  $xml->movie[0]['year'];
		$year = str_replace("\xe2\x80\x93", '-', $year);
		
		$rated= $xml->movie[0]['rated'];
		// echo $rated, PHP_EOL;
		$released= $xml->movie[0]['released'];
		$runtime= $xml->movie[0]['runtime'];
		$genre= $xml->movie[0]['genre'];
		$director= $xml->movie[0]['director'];
		$actors= $xml->movie[0]['actors'];
		$plot= $xml->movie[0]['plot'];
		$language= $xml->movie[0]['language'];
		$country= $xml->movie[0]['country'];
		$awards= $xml->movie[0]['awards'];
		$poster= $xml->movie[0]['poster'];

		if ($insert_stmt = $mysqli->prepare("INSERT INTO series (title, year, rated, released, runtime, genre, director, actors, plot, language, country, awards, poster) VALUES (?, ?, ?, ?,?, ?, ?, ?,?, ?, ?, ?,?)")) {
			$insert_stmt->bind_param('sssssssssssss', $title, $year, $rated, $released, $runtime, $genre, $director, $actors, $plot, $language, $country, $awards, $poster);
			// Execute the prepared query.
			$insert_stmt->execute();
				
		}
	}
	
	function update_user_profile($mysqli, $user_id, $firstname,$lastname, $email){
		if($update_stmt = $mysqli->prepare("UPDATE users SET firstname=?, lastname=?,  email=? WHERE user_id=?")){
			$update_stmt -> bind_param('ssss', $firstname, $lastname,$email, $user_id);
			$update_stmt->execute();
			// echo "affected rows: " . $update_stmt->affected_rows;
			echo "Successfully updated your profile";
		}else{
			echo "failure on updating profile";
		}
	}
	function edit_user_profile($mysqli, $user_id){
		print(db_query_user($mysqli, $user_id, ""));
	}
	function db_query_user($mysqli, $user_id, $status){
		
		$query = "SELECT * FROM users WHERE user_id='".$user_id."'";
		$return_str="";
		if($result = $mysqli->query($query)){
			
			while($row = $result->fetch_assoc()){
				if(!empty($status)){
					$return_str.="<user status= '".$status."'>";
				}else{
					
					$return_str.="<user>";
				}
				
				// $return_str.="<user_id>".$user_id."</user_id>";
				$return_str.="<user_id>".$row["user_id"]."</user_id>";
				$return_str.="<email>".$row["email"]."</email>";
				$return_str.="<firstname>".$row["firstname"]."</firstname>";
				$return_str.="<lastname>".$row["lastname"]."</lastname>";
				$return_str.="</user>";
			}
		}
		return $return_str;
	}
	function db_query_all_users($mysqli){
		$query = "SELECT * FROM users WHERE user_id != 'admin'";
		$return_str="";
		if($result = $mysqli->query($query)){
			while($row = $result->fetch_assoc()){
				$return_str.="<user>";
				$return_str.="<user_id>".$row["user_id"]."</user_id>";
				$return_str.="<email>".$row["email"]."</email>";
				$return_str.="<firstname>".$row["firstname"]."</firstname>";
				$return_str.="<lastname>".$row["lastname"]."</lastname>";
				$return_str.="</user>";
			}
		}
		return $return_str;
	}
	
	
	function subscribe($title, $mysqli){
		if(login_check($mysqli)){
			$user_id = $_SESSION['user_id'];
			if ($stmt_check=$mysqli->prepare("SELECT * FROM subsribes WHERE user_id=? AND title=?")) {
				$stmt_check->bind_param('ss', $user_id, $title);
				$stmt_check->execute();
				$stmt_check->store_result();
				
				if ($stmt_check->num_rows == 1) {
					print("already subscribed");
				}else{
					if($insert_stmt=$mysqli->prepare("INSERT INTO subsribes (user_id, title) VALUES(?,?)")){
						$insert_stmt->bind_param('ss', $user_id, $title);
						$insert_stmt->execute();
						print("Successfully added: " . $title . " to your private collection");
					
					}else{	
						print("NOT Successfully added: " . $title . " to your private collection");
					}
				}
			}
		}else{
			echo "not logged in";
		}
		
		
	}
	function remove_user($user_id, $mysqli){
		if(empty($user_id)){
			if($delete_stmt = $mysqli->prepare("DELETE FROM users WHERE user_id != 'admin'")){
				$delete_stmt->execute();
				print("All users successfully removed");
				
			}
			else{
				print("Failed removing all users");
			}
		}else{
			if($user_id != "admin"){
				if($delete_stmt = $mysqli->prepare("DELETE FROM users WHERE user_id = ?")){
					$delete_stmt-> bind_param ('s', $user_id);
					$delete_stmt->execute();
					print($user_id . " successfully removed");
					
				}
				else{
					print("Failed removing: $user_id from the system");
				}
			}
		}
		
	}
	
	function unsubscribe($title, $mysqli, $user_id){
		if($delete_stmt = $mysqli->prepare("DELETE FROM subsribes WHERE user_id = ? AND title = ?")){
			$delete_stmt->bind_param('ss', $user_id, $title);
			$delete_stmt->execute();
			print("Successfully unsubscribed ($user_id, $title)");
		}else{
			print("Failed unsubscribing: ($user_id, $title)");
		}
	}
	function remove_series($mysqli, $title){
		
		if(empty($title)){
			if($delete_stmt = $mysqli->prepare("DELETE FROM series")){
				$delete_stmt->execute();
				print("Successfully deleted all series from table series");
			}else{
				print("Failed deleting all series from table series");
			}
			
			if($delete_stmt = $mysqli->prepare("DELETE FROM subsribes")){
				$delete_stmt->execute();
				print("Successfully deleted all series and users from table subscribes");
			}else{
				print("Failed deleting all series and users from table subsribes");
			}
			
		}else{
			if($delete_stmt = $mysqli->prepare("DELETE FROM series WHERE title = ?")){
				$delete_stmt->bind_param('s', $title);
				$delete_stmt->execute();
				print("Successfully deleted $title from series table");
			}else{
				print("Failed deleting series from table series: $title");
			}
			
			if($delete_stmt = $mysqli->prepare("DELETE FROM subsribes WHERE title = ?")){
				$delete_stmt->bind_param('s', $title);
				$delete_stmt->execute();
				print("Successfully deleted $title from subscribes table");
			}else{
				print("Failed deleting series from table subsribes: $title");
			}
		}
	}
	
	function test($title){
		echo $title;	
	}
?>