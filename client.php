<?php	
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	sec_session_start();
	
	if(isset($_POST["action"]) && !empty($_POST["action"])){
		$action = 	$_POST["action"];
		$title = $_POST["title"];
		$user_id = $_POST["user_id"];
		$firstname = $_POST["new_firstname"];
		$lastname = $_POST["new_lastname"];
		$email = $_POST["new_email"];
		if(empty($user_id)){
			$user_id = $_SESSION["user_id"];
			
		}
		switch($action){
			// case 'subscribe': echo "Action: " . $action . " title: " . $title;//subscribe($title);
			case 'subscribe': subscribe($title, $mysqli); break;
			case 'remove_user': remove_user($user_id, $mysqli); break;
			case 'remove_all_users': remove_user("", $mysqli); break;
			case 'unsubscribe':unsubscribe($title, $mysqli, $user_id); break;
			case 'query_series': db_query_series_json($mysqli, $title); break;
			case 'edit_profile': edit_user_profile($mysqli, $user_id); break;
			case 'update_profile': update_user_profile($mysqli, $user_id, $firstname,$lastname, $email); break;
			case 'remove_series': remove_series($mysqli, $title); break;
			case 'remove_all_series': remove_series($mysqli, ""); break;
			case 'add_series': query_omdb($mysqli); break;
			case 'calendar': calendar($mysqli, $user_id); break;
		}
	}
	
	

?>
