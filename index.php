<?php include 'prefix.php'; ?>
	
<root>
<?php
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	sec_session_start();
	if(login_check($mysqli)){
		$user = $_SESSION["user_id"];
		if($user=="admin"){
			header("Location: admin.php?");
		}
		$to_print = db_query_user($mysqli, $user, "logged_in");
		print($to_print);
	}else{
		$to_print ="<user status ='logged_off'> </user>";
		print($to_print);
	}
	// }else{
		// $to_print = "<user status=logged_off>".$user."</user>";
		// print($to_print);
	// }
?>
<seriescatalog>
<?php
	
	$to_present = db_query_series_whats_trending($mysqli,"");
	print utf8_encode($to_present);
		

?>
</seriescatalog>
</root>
<?php include 'postfix.php'; ?>

<?php
	if(isset($_POST["action"])){
		subscribe($mysqli);
	}

?>
