<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet exclude-result-prefixes="xs html" version="2.0" xmlns:file="http://expath.org/ns/file" xmlns:html="http://www.w3.org/1999/xhtml" xmlns:xs="http://www.w3.org/2001/XMLSchema"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:param name="path"></xsl:param>

  <xsl:template match="phpcs">    
    <html>
      <head>
        <title>PHPCS Report</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"/>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" />
      </head>
      <body>
        <div class="container">
          <div class="row">
            <div class="col-md-7">
              <h3>Details</h3>
              <xsl:apply-templates select="file" mode="detail">
                <xsl:sort select="@name"></xsl:sort>
              </xsl:apply-templates>
            </div>
            <div class="col-md-5">
              <h3>Overview</h3>
              <ul>
                <xsl:apply-templates select="/phpcs" mode="summary" />
              </ul>
            </div>
          </div>
        </div>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="phpcs" mode="summary">    
    Errors: <xsl:value-of select="count(//error)"/> (<xsl:value-of select="count(//error[@fixable=1])"/>)<br/>
    Warnings: <xsl:value-of select="count(//warning)"/> (<xsl:value-of select="count(//warning[@fixable=1])"/>)<br/>
    Fixable: <xsl:value-of select="count(//*[@fixable=1])"/>
  </xsl:template>

  <xsl:template match="file" mode="detail">
    <div class='panel panel-default'>
      <div class='panel-heading'>
        <xsl:value-of select="substring-after(@name, $path)"/>
      </div>
      <div class="panel-body">
        <p>Errors: <xsl:value-of select="count(error)"/> (<xsl:value-of select="count(error[@fixable=1])"/>) | 
          Warnings: <xsl:value-of select="count(warning)"/> (<xsl:value-of select="count(warning[@fixable=1])"/>) | 
          Fixable: <xsl:value-of select="count(*[@fixable=1])"/>
        </p>
      </div>
      
      <ul class="list-group">
        <xsl:apply-templates select="error[@fixable!=1]" mode="detail"/>
        <xsl:apply-templates select="warning[@fixable!=1]" mode="detail"/>
      </ul>
    </div>
  </xsl:template>
  
  <xsl:template match="error|warning" mode="detail">
    <xsl:variable name="class">
      <xsl:choose>
        <xsl:when test="@fixable=1">disabled</xsl:when>
        <xsl:otherwise></xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    
    <li class="list-group-item {$class}">
      <h4 class="list-group-item-heading"><xsl:value-of select="."/></h4>
      <p><xsl:value-of select="@line"/>:<xsl:value-of select="@column"/>:<xsl:value-of select="@source"/></p>
    </li>
  </xsl:template>

  <xsl:template match="@* | node()">
    <xsl:copy>
      <xsl:apply-templates select="@* | node()" />
    </xsl:copy>
  </xsl:template>

</xsl:stylesheet>
