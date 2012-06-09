<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">

    <html xmlns="http://www.w3.org/1999/xhtml" >
      <head>
        <title>psp for php.four - Version History</title>
        <link rel="stylesheet" href="./Styles/Styles.css" />
        <style type="text/css">
          table.List td
          {
            vertical-align: top;
          }
          ol.Ordered
          {
            margin: 0px;
            list-style-type:lower-roman;
          }
          ol.Ordered li
          {
            margin-top: 5px;
          }
          ol.Ordered > li:first-child
          {
            margin-top: 0px;
          }
        </style>
      </head>
      <body>
        <div class="All">
          <div class="Top">
            <div class="Heading">psp for php.four</div>
            <div class="SmallTop">
              Author: <xsl:value-of select="history/author"/>
              <xsl:text > </xsl:text>
              <xsl:value-of select="history/copyright"/>
            </div>
          </div>
          
          <div class="Subheading">version:history</div>
          <div class="Rest">
            <table class="List" cellspacing="0" cellpadding="0" align="center">
              <tr class="Heading">
                <th>Version</th>
                <th>Description</th>
                <th>Date</th>
              </tr>
              <xsl:for-each select="history/version">
                <xsl:sort data-type="number" select="major" order="descending"/>
                <xsl:sort data-type="number" select="minor" order="descending"/>
                <xsl:sort data-type="number" select="revision" order="descending"/>
                <xsl:sort data-type="text" select="build" order="descending"/>
                <tr>
                  <td style="width: 8em;  text-align: center; font-weight: bold;">
                    <xsl:value-of select="major"/>.<xsl:value-of select="minor"/>.<xsl:value-of select="revision"/>
                    <xsl:if test="build">.<xsl:value-of select="build" /></xsl:if>
                  </td>
                  <td>
                    <ol class="Ordered">
                      <xsl:for-each select="notes/note">
                        <li>
                          <xsl:value-of select="." />
                        </li>
                      </xsl:for-each>
                    </ol>
                  </td>
                  <td style="width: 8em; text-align: center;">
                    <xsl:value-of select="date" />
                  </td>
                </tr>
              </xsl:for-each>
            </table>

          </div>
        </div>
      </body>
    </html>

  </xsl:template>

</xsl:stylesheet>