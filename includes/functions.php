<?php
	include_once 'config.php';
	
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
					
					// echo "Test: " . $user_id. " " . $login_string. " **** ". $password;
					
					 // If the user exists get variables from result.
					//$login_check = hash('sha512', $password . $user_browser);
					//$_SESSION['login_check'] = $login_check;
					
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
				$poster = $row["poster"] ;
				$poster = substr($poster,34);
				$return_str .= "<series>";
				// echo $row["title"];
				$return_str .= '<title>'.$row["title"].'</title>';
				$return_str .= '<year>'.$row["year"].'</year>';
				$return_str .= '<rated>'.$row["rated"].'</rated>';
				$return_str .= '<released>'.$row["released"].'</released>';
				$return_str .= '<runtime>'.$row["runtime"].'</runtime>';
				$return_str .= '<genre>'.$row["genre"].'</genre>';
				$return_str .= '<director>'.$row["director"].'</director>';
				$return_str .= '<actors>'.$row["actors"].'</actors>';
				$return_str .= '<plot>'.$row["plot"].'</plot>';
				$return_str .= '<language>'.$row["language"].'</language>';
				$return_str .= '<country>'.$row["country"].'</country>';
				// $return_str .= '<awards>'.$row["awards"].'</awards>';
				$return_str .= '<poster>'.$poster.'</poster>';
				
				$return_str .= "</series>";
			}
			mysqli_free_result($result);	
			return $return_str;
		}
		// echo $return_str;
	}
	function db_query_series_whats_trending($mysqli, $where_clause){
		// file_put_contents("./series_names/img_urls.txt", "hejhej");
		if(empty($where_clause)){
			
			$query = "SELECT * FROM series";
		}else{
			$where_clause=trim($where_clause);
			$query = "SELECT * FROM series WHERE title='".$where_clause."'";
		}
		
		if($result = $mysqli->query($query)){
			
			$return_str="";
			while($row = $result->fetch_assoc()){
				$year = $row["year"];
				$year_exploded= explode("-", $year);
				$start_year = $year_exploded[0];
				if(intval($start_year==2015)){
					$poster = $row["poster"] ;
					$poster = substr($poster,34);
					
					$return_str .= "<series>";
					// echo $row["title"];
					$return_str .= '<title>'.$row["title"].'</title>';
					$return_str .= '<year>'.$row["year"].'</year>';
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
		// echo $return_str;
		

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
		$file=fopen("./series_names/test.txt", "r");
		$title = "";
		$result = "xml";
		
		if($file){
			
			while(!feof($file)){
				$is_true = false;
				$title = trim(fgets($file));
				$title = str_replace(" ", "+", $title);
				echo "title to query: ".$title.".";
				// $title = "glee";
				
				$toquery = 'http://www.omdbapi.com/?t='.$title.'&r=xml';
				echo $toquery."</br>";
				
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
		$year=  $xml->movie[0]['year'];
		// echo "Year: " . $year . ": " . bin2hex($year) . "</br>";
		// list($start, $end) = explode('\xe2\x80\x93', $year);
		/*Issue of en dash which has a different encoding than a normal dash. Replacing them with "-". */
		$year = str_replace("\xe2\x80\x93", '-', $year);
		// echo $year . "</br>";
		
		
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
		//TODO add poster to file to download. 
		// $file=fopen("./series_names/img_urls.txt", "w") or die ("Unable to open file");
		// fwrite($file,"testing\n");
		file_put_contents("./series_names/img_urls.txt", "hejhej");
		// echo "here I am";
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
		
		print("inside remove_user");
		if($delete_stmt = $mysqli->prepare("DELETE FROM users WHERE user_id = ?")){
			$delete_stmt-> bind_param ('s', $user_id);
			$delete_stmt->execute();
			print($user_id . " successfully removed");
			
		}
		else{
			print("Failed removing: $user_id from the system");
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
		
		// unsubscribe($title, $mysqli, $user_id);
	}
	
	function test($title){
		echo $title;	
	}
?>