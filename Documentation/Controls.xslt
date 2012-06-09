<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">

    <html xmlns="http://www.w3.org/1999/xhtml" >
      <head>
        <title>psp for php.four : page.controls</title>
        <link rel="stylesheet" href="./Styles/Styles.css" />
        <style type="text/css">

          div.Each
          {
          margin: 20px 10px 20px 10px;
          }

          table.Control
          {
          border-collapse: collapse;
          width: 100%;
          color: #333333;
          }
          table.Control th, table.Control td
          {
          border: 1px solid #333333;
          padding: 3px;
          }
          table.Control > tr > th
          {
          background-color: #94ff0c;
          color: #333333;
          text-align: right;
          vertical-align: top;
          width: 8em;
          }

          table.Attributes
          {
          width: 100%;
          background-color: #eeeeee;
          }
          table.Attributes th
          {
          text-align: center;
          background-color: #888888;
          color: white;
          }
          table.Attributes td
          {
          vertical-align: top;
          }
          .CSName
          {

          text-align: left;
          font-size: 90%;
          }

          table.Control > tr > td > table:first-child
          {
          margin-top: 0px;
          }
          table.ChildNode
          {
          border-collapse: collapse;
          text-align: left;
          margin-top: 10px;
          }

          table.ChildNode td, table.ChildNode th
          {
          border: 1px solid #aaaaaa;
          padding: 2px;
          }
          table.ChildNode th
          {
          vertical-align: top;
         
          }
          .CDesc
          {
          text-align: left;
          font-size: 85%;
          color: gray;
          }
          .CName
          {
          font-weight: bold;
          text-align: left;
          font-size: 150%;
          }

          .AttName
          {
          font-weight: bold;
          }
          .AttDesc
          {
          text-align: left;
          }
          .CNodeName
          {
          color: brown;
          font-weight: bold;
          }
          pre
          {
          margin: 1px;
          font-size: 135%;
          }
          .Example
          {
            text-align: left;
            color: brown;
            font-family: courier new;
            padding-left: 3px;
          }
          .ControlName, .AttributeName
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
          <div class="Subheading">::page.controls::</div>
          <div class="Rest">

            <!-- Top Links -->
            <div>
              <xsl:for-each select="controls/control">
                <xsl:sort select="name" />
                <a>
                  <xsl:attribute name="href">
                    <xsl:text>#</xsl:text><xsl:value-of select="name" />
                  </xsl:attribute>
                  <xsl:value-of select="name" />
                </a>
                <xsl:text> </xsl:text> 
              </xsl:for-each>
            </div>
            
            <xsl:for-each select="controls/control">
              <xsl:sort select="name" />
              <div class="Each">
                
                <a>
                  <xsl:attribute name="name">
                    <xsl:value-of select="name" />
                  </xsl:attribute>
                </a>
                
              <table class="Control" cellspacing="0" cellpadding="0" >
                <tr>
                  <th>Name</th>
                  <td class="CName">
                    <xsl:value-of select="name" />
                  </td>
                </tr>
                <tr>
                  <th>Description</th>
                  <td class="CDesc">
                    <xsl:value-of select="description" />
                  </td>
                </tr>
                <tr>
                  <th>Attributes</th>
                  <td>
                    <table class="Attributes">
                      <tr>
                        <th>Name</th>
                        <th>Property</th>
                        <th>Type</th>
                        <th>Default</th>
                        <th>Description</th>
                      </tr>
                      <xsl:if test="attributes[@includebase='true']">
                        <xsl:for-each select="/controls/baseattributes/attribute">
                          <tr>
                            <td class="AttName">
                              <xsl:value-of select="name"/>
                            </td>
                            <td>
                              <xsl:value-of select="property"/>
                            </td>
                            <td>
                              <xsl:value-of select="type"/>
                            </td>
                            <td>
                              <xsl:value-of select="default"/>
                            </td>
                            <td class="AttDesc">
                              <xsl:value-of select="description"/>
                            </td>
                          </tr>
                        </xsl:for-each>
                        
                      </xsl:if>
                      <xsl:for-each select="attributes/attribute">
                        <tr>
                          <td class="AttName">
                            <xsl:value-of select="name"/>
                          </td>
                          <td>
                            <xsl:value-of select="property"/>
                          </td>
                          <td>
                            <xsl:value-of select="type"/>
                          </td>
                          <td>
                            <xsl:value-of select="default"/>
                          </td>
                          <td class="AttDesc">
                            
                            <xsl:value-of select="description"/>
                          </td>
                        </tr>
                      </xsl:for-each>
                    </table>
                  </td>
                </tr>
                <xsl:if test="children">
                  <tr>
                    <th>Child Nodes</th>
                    <td class="CSName">
                      <xsl:for-each select="children/child">
                        <table class="ChildNode">
                          <tr>
                            <th>Name</th>
                            <td class="CNodeName">
                              <xsl:value-of select="name"/>
                            </td>
                          </tr>
                          <tr>
                            <th>Status</th>
                            <td>
                              <xsl:value-of select="status"/>
                            </td>
                          </tr>
                          <tr>
                            <th>Desc</th>
                            <td>
                              <xsl:value-of select="description"/>
                            </td>
                          </tr>
                          <tr>
                            <th>Attributes</th>
                            <td>
                              <xsl:if test="attributes">
                                <table class="Attributes">
                                  <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Example</th>
                                  </tr>
                                  <xsl:for-each select="attributes/attribute">
                                    <tr>
                                      <td>
                                        <xsl:value-of select="name"/>
                                      </td>
                                      <td>
                                        <xsl:value-of select="status"/>
                                      </td>
                                      <td>
                                        <xsl:value-of select="type"/>
                                      </td>
                                      <td> 
                                        <xsl:value-of select="description"/>
                                      </td>
                                      <td>
                                        <pre>
                                          <xsl:value-of select="example"/>
                                        </pre>
                                      </td>
                                    </tr>
                                  </xsl:for-each>
                              </table>
                              </xsl:if>
                            </td>
                          </tr>  
                        </table>
                      </xsl:for-each>
                    </td>
                  </tr>
                </xsl:if>
                
                <tr>
                  <th>Example</th>
                  <td>
                    <div class="Example">
                      &lt;psp:<span class="ControlName"><xsl:value-of select="name"/>
                      </span><xsl:text> </xsl:text>
                      <xsl:if test="attributes[@includebase='true']">
                        <xsl:for-each select="/controls/baseattributes">
                          <xsl:apply-templates select="attribute" />
                        </xsl:for-each>
                      </xsl:if>
                      <xsl:for-each select="attributes">
                        <xsl:apply-templates select="attribute" />
                      </xsl:for-each> /&gt;
                    </div>
                  </td>
                </tr>
                
              </table>
              </div>
            </xsl:for-each>
              
            

          </div>
        </div>
      </body>
    </html>

  </xsl:template>

  
  <xsl:template match="attribute">
      <span class="AttributeName">
        <xsl:value-of select="name" />="<xsl:value-of select="example"/>"
      </span>
  </xsl:template>

  
</xsl:stylesheet>