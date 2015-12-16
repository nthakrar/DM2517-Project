<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                version="1.0">


<xsl:template match="root">
	
	<html>
		<head>
			<title>My TV shows- What's trending</title>
			<!-- Latest compiled and minified JavaScript -->
			
			<script type="text/JavaScript" src="js/sha512.js"></script> 
			<script type="text/JavaScript" src="js/forms.js"></script> 
			
			<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>			
			<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
			
			<script type="text/JavaScript" src="js/functions.js"></script> 
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>	
			
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"></link>
			<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet"></link>
			<link rel="stylesheet" href="css/index.css"></link>

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous"></link>

		

		</head>
		
		<!--##### BODY ####  -->
		<body>
			<!-- <div class="container">

			</div> -->
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-3 col-md-2 sidebar">
						<xsl:apply-templates select="user" />
					</div>
					<div class="col-sm-8 col-sm-offset-2 col-md-9 col-md-offset-2">
						<div class="jumbotron">
							<h1 style="text-align:center">My TV series</h1>
						</div>
						
						<!-- Presentation of queried series -->
						<div class="info_queried_series col-md-offset-1" style="display:none">
							<div class="info_queried_series_block">
								
							</div>
							<div class = "subscribe_btn">
								<span class="glyphicon glyphicon-plus-sign"></span>Subscribe
							</div>
							<div class="close_info_series">
								Close [X]
							</div>
						</div>
							
							
							
						<!-- Query -->
						<div class="row">
						<div class="col-xs-1 col-sm-offset-3">
						</div>
						<div class="col-xs-4">
							<div class="ui-widget">
								
								<input id ="search_query" class="form-control txt-auto"> </input>
								
							</div>
							<button id ="search_button" class="btn btn-lg btn-primary btn-block btn-signin" type="submit" >search</button>
						</div>
						</div>
						
										
						<xsl:apply-templates select="seriescatalog" /> 
					</div>
				</div>
				
					
					 
				
			</div>
			
			
			
			
		</body>
	</html>
</xsl:template>

<xsl:template match="user[@status='logged_in']">
	<div id ="login_box">
		<div id="login_box_img">
			<img src="images/avatar_login.png"> </img>
		</div>
		<p class="login_box_firstlastname">
			<span id="user_firstname">
				<xsl:value-of select="firstname" /> 
			</span>
			<span id="user_lastname">
				<xsl:value-of select="lastname" /> 
			</span>
		</p>
		<ul class="nav nav-sidebar">
			
			<li><a href="user.php">Go to personal page</a></li>
			<li><a href="includes/logout.php">Sign out</a></li>
		</ul>
		
		
		<!--  <div id ="logout">
			<a href="includes/logout.php">Sign out</a>
		</div> --> 
	</div>
</xsl:template>

<xsl:template match="user[@status='logged_off']">
	
	
	<div id ="login_box">
		<div id="login_box_img">
			<img src="images/avatar_login.png"> </img>
		</div>
		<form class="" action="includes/process_login.php" method="post" name="login_form">                      
			Username: <input class="form-control" type="text" name="user_id" />
			Password: <input class="form-control" type="password" 
							 name="password" id="password" />
			 
		
			<button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" onclick="formhash(this.form, this.form.user_id, this.form.password);">Login</button>
			
			
			
				
		</form>
	</div>
	<p style="padding-top:80px">New to this web page? <a href="register.php">Sign up</a>
	</p> 
</xsl:template>

<xsl:template match="seriescatalog">
	<h2 style="text-align:center"> What's trending </h2>
	<div class="row" style="text-align:center">
		<xsl:apply-templates select="series" />
	</div>
	
</xsl:template>

<xsl:template match="series">
	<div class="col-md-4">
		<div class = "series_block" data-value="series_block">
			<h2>	
				<div class="title">
					<xsl:value-of select="title" />
				</div>
			</h2>
	
			<!--<img>
				<xsl:attribute name="src">	
					<xsl:value-of select="poster" />
				</xsl:attribute>
			</img>
			-->
			<div>
				<xsl:value-of select="year"/>
			</div>
			<div>
				<img>
					<xsl:attribute name ="src">
						http://www.csc.kth.se/~arefm/DM2517/projekt/images/<xsl:value-of select="poster" />
					</xsl:attribute>
				</img>
						
				
			</div>
			
			
			<div class = "subscribe_btn">
				<xsl:attribute name="data-title">
					<xsl:value-of select="title" />
				</xsl:attribute>
				<span class="glyphicon glyphicon-plus-sign"></span>Subscribe
			</div>
		</div>
	</div>
</xsl:template>

</xsl:stylesheet>
