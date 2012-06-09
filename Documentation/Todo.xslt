<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">

    <html xmlns="http://www.w3.org/1999/xhtml" >
      <head>
        <title>psp for php.four : todo</title>
        <link rel="stylesheet" href="./Styles/Styles.css" />
        <style type="text/css">
          .High .Priority
          {
          color: red;
          }
          .Medium .Priority
          {
          color: blue;
          }
          .Low .Priority
          {
          color: green;
          }
          .Priority
          {
            font-weight: bold;
          }
        </style>
      </head>
      <body>
        <div class="All">
          <div class="Top">
            <div class="Heading">psp for php.four</div>
            <div class="SmallTop">
              Author: <xsl:value-of select="/*/author"/>
              <xsl:text > </xsl:text>
              <xsl:value-of select="/*/copyright"/>
            </div>
          </div>
          <div class="Subheading">todo</div>
          <div class="Rest">
            <table class="List" cellspacing="0" cellpadding="0" align="center">
              <tr class="Heading">
                <th>Name</th>
                <th>Description</th>
                <th>Priority</th>
                <th>Status</th>
              </tr>
              <xsl:for-each select="todo/item">
                <xsl:sort select="order" data-type="number"  order="descending" />
                <xsl:if test="not(status='Complete')">
                  <tr>
                    <xsl:attribute name="class">
                      <xsl:value-of select="priority" />
                    </xsl:attribute>
                    <td style="font-weight:bold; vertical-align: top;">
                      <xsl:value-of select="name"/>
                    </td>
                    <td>
                      <xsl:value-of select="desc" />
                    </td>
                    <td class="Priority">
                      <xsl:value-of select="priority" />
                    </td>
                    <td>
                      <xsl:value-of select="status" />
                    </td>
                  </tr>
                </xsl:if>
              </xsl:for-each>
            </table>
          </div>
        </div>
      </body>
    </html>
    
  </xsl:template>
  
</xsl:stylesheet>