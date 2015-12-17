<?php include 'prefix_user.php'; ?>
<root>


<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
sec_session_start();
?>
<?php
	
	if (login_check($mysqli) == true){
		$to_present = "<usercatalog>";
		
		$user_id = $_SESSION["user_id"];	
		if($user_id=="admin"){
			header("Location: admin.php?");
		}
		$to_present .= db_query_user($mysqli, $user_id, "");
		
		$to_present .="</usercatalog>";
		
		$to_present .= "<seriescatalog>";
		$to_present .= db_query_user_subscription_series($mysqli);
		$to_present .= "</seriescatalog>";
		print utf8_encode($to_present);
	}else{
		header("Location: index.php?");
	}
	
?>
</root>
<?php include 'postfix_user.php'; ?>
