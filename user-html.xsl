<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                version="1.0">


<xsl:template match="root">
	
	<html>
		<head>
			<title>My TV shows- What's trending</title>
			<script type="text/JavaScript" src="js/sha512.js"></script> 
			<script type="text/JavaScript" src="js/forms.js"></script> 
			<script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>			
			<script type="text/JavaScript" src="js/functions.js"></script> 
			
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"></link>
			<link rel="stylesheet" href="css/user.css"></link>
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous"></link>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

		</head>
		
		<!--##### BODY ####  -->
		<body>
			<div class="container-fluid">
				<div class="row">
					<xsl:apply-templates select="usercatalog"/>
					<xsl:apply-templates select="seriescatalog" />
					<div id ="usercatalog" class="col-md-10 col-md-offset-2" style="display:none">
						<h1 class="page-header">Edit profile</h1>
						<div class="row" style="text-align:center">
						<div class="col-md-2 col-md-offset-2">
						</div>
						<div class="col-md-4">
							<form class=""  name="edit_form">
								
								First name: <input id ="edit_firstname" class="form-control" type="text" name="firstname" />
								Last name: 	<input id ="edit_lastname"  class="form-control" type="text"  />
								email: 	<input id ="edit_email" class="form-control" type="text" 
												 name="email"/>
								<div class="col-md-9">
									
								</div>
								<div class="col-md-3">
									<button  type="button" id="edit_profile_updatebtn" class="btn btn-lg btn-primary btn-block btn-signin">Save</button>
								</div>
								
							</form>
						</div>
							
						</div>
					</div>
					
				</div>
			</div>
		
		</body>
	</html>
</xsl:template>

<xsl:template match="usercatalog">
	
	<div class="col-md-2 sidebar">
		<div id ="login_box">
			<div id="login_box_img">
				<img src="images/avatar_login.png"> </img>
			</div>
			<div>
			
				<xsl:apply-templates select="user" />
			</div>
		</div>
		<ul class="nav nav-sidebar">
			
			<li><a href="index.php">What's trending </a></li>
			<li id ="editprofile"> <a>Edit profile</a></li>
			<li><a href="#">Manage series</a></li>
			<li id ="calendar"><a href="#">Calendar</a></li>
			<li><a href="includes/logout.php">Sign out</a></li>
		</ul>
		 
	</div>
	
	
</xsl:template>

<xsl:template match="user">
	
	<p class="login_box_firstlastname"> 
		
			<span id="user_firstname">
				<xsl:value-of select="./firstname" /> 
			</span>
			<span id="user_lastname">
				<xsl:value-of select="lastname" /> 
			</span>
		
	</p>
	<p class="login_box_email"> 
		<span id="user_email">
			<xsl:value-of select="email" /> 
		</span>
	</p>
</xsl:template>


<xsl:template match="seriescatalog">
	
	<div id ="seriescatalog" class="col-md-10 col-md-offset-2">
		<h1 class="page-header">My Collection</h1>
		<div class="row" style="text-align:center">
			<xsl:choose>
				<xsl:when test="series">
					<xsl:apply-templates select="series" />
					
				</xsl:when>
				<xsl:otherwise>
					<h1> EMPTY </h1>	
					<p>Check out What's trending to see if there is something interesting catching your eye. </p>
				</xsl:otherwise>
			</xsl:choose>

		</div>

	</div>
	
</xsl:template>

<xsl:template match="series">
	
	<div class="col-md-4">
		<div class = "series_block">
			<xsl:attribute name="data-value">
				<xsl:value-of select="title" />
			</xsl:attribute>
			<h2>	
				<div class="title">
					<xsl:value-of select="title" />
				</div>
			</h2>
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
						
		</div>
		<div class = "unsubscribe_btn">
			<xsl:attribute name="data-title">
				<xsl:value-of select="title" />
			</xsl:attribute>
			<span class="glyphicon glyphicon-minus-sign"></span>
			Unsubscribe
		</div>
	</div>
	
	<div class="info_series col-md-offset-1" style="display:none">
		<xsl:attribute name="data-value">info_series_<xsl:value-of select="title" /></xsl:attribute>
		<div class="info_series_block">
			<div>
				<img>
					<xsl:attribute name="src">
						http://www.csc.kth.se/~arefm/DM2517/projekt/images/<xsl:value-of select="poster" />
					</xsl:attribute>
				</img>
			</div>
			
			
			<div class="info_series_plot">
			
			<h3>Plot</h3>
				<xsl:value-of select="plot" />
			</div>
			<div>
				<xsl:value-of select="rated" />
			</div>
			<div>
				<xsl:value-of select="released" />
			</div>
			<div>
				<xsl:value-of select="runtime" />
			</div>
			<div>
				<xsl:value-of select="genre" />
			</div>
			<div>
				<xsl:value-of select="director" />
			</div>
			<div>
				<xsl:value-of select="actors" />
			</div>
			<div>
				<xsl:value-of select="language" />
			</div>
			<div>
				<xsl:value-of select="country" />
			</div>
		</div	>
		<div class="close_info_series">
			Close [X]
		</div>
		
		
	</div>
</xsl:template>

</xsl:stylesheet>
