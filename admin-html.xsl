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
			<link rel="stylesheet" href="css/admin.css"></link>
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous"></link>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

		</head>
		
		<!--##### BODY ####  -->
		<body>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-3 col-md-2 sidebar">
						
						<ul class="nav nav-sidebar">
								
							<li><a href="index.php">Manage series </a></li>
							<li><a href="#">Manage users</a></li>
							<li><a href="includes/logout.php">Sign out</a></li>
						</ul>
					
					</div>
					<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
						<h1 class="page-header">All users</h1>
						<div>
							<xsl:apply-templates select="usercatalog" />
						</div>
						<h1 class="page-header"> All series </h1>
							
						</div>

					</div>
					
				</div>
			
		
		</body>
	</html>
</xsl:template>

<xsl:template match="usercatalog">
	<div class="row" style="text-align:center">
		<xsl:apply-templates select="user" />
	</div>
	
	
	
</xsl:template>

<xsl:template match="user">
	<div class="col-md-4">
		<img src="images/avatar_login.png"> </img>
		<p>
			<xsl:value-of select="user_id" />  
			<span class="delete_user glyphicon glyphicon-trash">
				<xsl:attribute name="data-value">
					<xsl:value-of select="user_id" />
				</xsl:attribute>
			</span>
		</p>
		<p><xsl:value-of select="firstname" /><xsl:value-of select="lastname" /> </p>
			
		<p><xsl:value-of select="email" /> </p>
		
	</div>
	
</xsl:template>

<xsl:template match="seriescatalog">
	
	
	
</xsl:template>

<xsl:template match="series">
	
	<div class="col-md-4">
		<div class = "series_block" data-value="series_block">
			<h2>	
				<div class="title">
					<xsl:value-of select="title" />
				</div>
			</h2>
	
			<div>
				<xsl:value-of select="year"/>
			</div>
			
			<div class = "unsubscribe_btn">
				<xsl:attribute name="data-title">
					<xsl:value-of select="title" />
				</xsl:attribute>
				Unsubscribe
			</div>
		</div>
	</div>
</xsl:template>

</xsl:stylesheet>
