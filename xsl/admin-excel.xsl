<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet 
	version="1.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">

	<xsl:template match="/">
		<ss:Workbook>
			<ss:Styles>
				<ss:Style ss:ID="1">
					<ss:Font ss:Bold="1"/>
				</ss:Style>
				
			</ss:Styles>
			
			<ss:Worksheet ss:Name="Sheet1">
				<xsl:apply-templates />
			</ss:Worksheet>
		</ss:Workbook>
	</xsl:template>

<xsl:template match="root">
    <ss:Table>
		<ss:Column ss:Width="80"/>
		<ss:Column ss:Width="80"/>
		<ss:Column ss:Width="80"/>
		<ss:Column ss:Width="80"/>
		<ss:Column ss:Width="80"/>
		<ss:Column ss:Width="80"/>
		<ss:Column ss:Width="80"/>
		<ss:Column ss:Width="80"/>
		<ss:Column ss:Width="150"/>
		<ss:Column ss:Width="80"/>
		<ss:Column ss:Width="80"/>
		<ss:Column ss:Width="80"/>
		<ss:Column ss:Width="80"/>
		
		
		<ss:Row ss:StyleID="1">
			<ss:Cell>
				All users
			</ss:Cell>
		</ss:Row>
		<!-- table:2 user info -->
        <ss:Row ss:StyleID="1">
            <ss:Cell>
                <ss:Data ss:Type="String">User id</ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String">First Name</ss:Data>
            </ss:Cell>
            <ss:Cell>
                <ss:Data ss:Type="String">Last Name</ss:Data>
            </ss:Cell>
            <ss:Cell>
                <ss:Data ss:Type="String">email</ss:Data>
            </ss:Cell>
        </ss:Row>
        <xsl:apply-templates select="usercatalog" />
		
		<!-- Empty row -->
		<ss:Row ss:StyleID="1"></ss:Row>
		
		<ss:Row ss:StyleID="1">
			<ss:Cell>
				All series
			</ss:Cell>
		</ss:Row>
		<!-- table3: series info (the series user_id subscribes to) -->
		<ss:Row ss:StyleID="1">
            <ss:Cell>
                <ss:Data ss:Type="String">Title</ss:Data>
            </ss:Cell>
            <ss:Cell>
                <ss:Data ss:Type="String">Year</ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String">Rated</ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String">Released</ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String">Runtime</ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String">Genre</ss:Data>
            </ss:Cell>
            <ss:Cell>
                <ss:Data ss:Type="String">Director</ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String">Actors</ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String">Plot</ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String">Language</ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String">Country</ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String">Poster</ss:Data>
            </ss:Cell>

        </ss:Row>
        <xsl:apply-templates select="seriescatalog"/>
   

   </ss:Table>
</xsl:template>
	
<xsl:template match="usercatalog">
    
	
		<xsl:apply-templates select="user" />
	
            
</xsl:template>

<xsl:template match="user">
    <ss:Row>
		<ss:Cell>
			<ss:Data ss:Type="String">
				<xsl:value-of  select="user_id" />
			</ss:Data>
		</ss:Cell>
		<ss:Cell>
			<ss:Data ss:Type="String">
				<xsl:value-of  select="firstname" />
			</ss:Data>
		</ss:Cell>
		<ss:Cell>
			<ss:Data ss:Type="String">
				<xsl:value-of  select="lastname" />
			</ss:Data>
		</ss:Cell>
		<ss:Cell>
			<ss:Data ss:Type="String">
				<xsl:value-of  select="email" />
			</ss:Data>
		</ss:Cell>
	</ss:Row>
</xsl:template>


<xsl:template match="seriescatalog">
    
	<xsl:apply-templates select="series" />
            
</xsl:template>

<xsl:template match="series">
	<ss:Row>
			
            <ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="title" /></ss:Data>
            </ss:Cell>
            <ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="year" /></ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="rated" /></ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="released" /></ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="runtime" /></ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="genre" /></ss:Data>
            </ss:Cell>
            <ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="director" /></ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="actors" /></ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="plot" /></ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="language" /></ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="country" /></ss:Data>
            </ss:Cell>
			<ss:Cell>
                <ss:Data ss:Type="String"><xsl:value-of  select="poster" /></ss:Data>
            </ss:Cell>

        </ss:Row>
		

</xsl:template>

</xsl:stylesheet>