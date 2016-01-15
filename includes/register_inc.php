<?php
include_once 'db_connect.php';
include_once 'config.php';
 
$error_msg = "";
$error_user_msg ="";
$error_email_msg ="";
 
if (isset($_POST['user_id'], $_POST['email'], $_POST['p'])) {
	
	//echo "*".$_POST['user_id']."*".$_POST['p']."*".$_POST['email'];
    // Sanitize and validate the data passed in
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
	$firstname=$_POST["firstname"];
	$lastname=$_POST["lastname"];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_email_msg .= '<p class="error">The email address you entered is not valid</p>';
    }
 
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
 
    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //
 
    $prep_stmt = "SELECT user_id FROM users WHERE email = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
 
   // check existing email  
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
           
			$error_email_msg .= '<p class="error">A user with this email address already exists.</p>';
			$stmt->close();
        }
                
    } else {
        $error_msg .= '<p class="error">Database error Line 39</p>';
                $stmt->close();
    }
 
    // check existing username
    $prep_stmt = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
 
    if ($stmt) {
        $stmt->bind_param('s', $user_id);
        $stmt->execute();
        $stmt->store_result();
 
		if ($stmt->num_rows == 1) {
				// A user with this username already exists
				$error_user_msg = '<p class="error">A user with this username already exists</p>';
				$stmt->close();
		}
         
        } else {
                $error_msg .= '<p class="error">Database error line 55</p>';
                $stmt->close();
        }
 
 
    if (empty($error_msg) && empty($error_email_msg) && empty($error_user_msg)) {
        // Create a random salt
        //$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE)); // Did not work
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
 
        // Create salted password 
        $password = hash('sha512', $password . $random_salt);
 
        // Insert the new user into the database 
        if ($insert_stmt = $mysqli->prepare("INSERT INTO users (user_id, password, salt, email, firstname, lastname) VALUES (?, ?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssssss', $user_id, $password, $random_salt, $email, $firstname, $lastname);
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                header('Location: ../error.php?err=Registration failure: INSERT');
            }
        }
        header('Location: register_success.php');
    }
}