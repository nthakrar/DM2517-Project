<?php
include_once 'includes/register_inc.php';
include_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Secure Login: Registration Form</title>
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
		<script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>			
			<script type="text/JavaScript" src="js/functions.js"></script> 
        <link rel="stylesheet" href="css/register.css" />
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous"></link>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"></link>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- Registration form to be output if the POST variables are not
        set or if the registration script caused an error. -->
        
        <?php
        if (!empty($error_msg)) {
            echo $error_msg;
        }
		
        ?>
		<div class="container">
				<div class="jumbotron">
					<h1 style="text-align:center">My TV series</h1>
				</div>
					<h1>Register with us</h1>
					<div class="row">
						
						
						<div class="col-xs-6">
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="registration_form"> 
								Username: <input class="form-control" type='text' name='user_id' id='user_id' />						
								<?php
									if(!empty($error_user_msg)){
										echo $error_user_msg;
									}
								?>
								<br>
								Password: <input class="form-control" type="password" name="password" id="password"/>				<br>
								Confirm password: <input class="form-control" type="password" name="confirmpwd" id="confirmpwd" />	<br>
								First name: <input class="form-control" type="text" name="firstname" id="firstname" />							<br>
								Last name: <input class="form-control" type="text" name="lastname" id="lastname" />							<br>
								Email: <input class="form-control" type="text" name="email" id="email" />	
								<?php
									if(!empty($error_email_msg)){
										echo $error_email_msg;
									}
								?>
												
								
								</br>
								<input style="width:200px; float:right" class="btn btn-lg btn-primary btn-block btn-signin" type="button" value="Register" onclick="return regformhash(this.form, this.form.user_id, this.form.email, this.form.password, this.form.confirmpwd);" /> 
							</form>
						</div>
					</div>
				</div>
			</div>
        
        <p>Return to the <a href="index.php">login page</a>.</p>
    </body>
</html>