//check for jsi existence
if (!jsi)
{
    throw("Cannot use an InputTable control without the jsi javascript:interface project.");
}



jsi.psp.InputTable = function(pspID, clientID, heading, parentID, outputDataID)
{
    jsi.psp.PageControl.call(this, pspID, clientID);
        
    this.table = new jsi.table.InputTable(new jsi.dhtml.Element(parentID),new jsi.dhtml.Element(outputDataID),heading);

    //initialise
    this.init();
    
}

    jsi.psp.InputTable.prototype.init = function()
    {
        
	    this.table.cssClass = "PSPInputTableTable";
	    this.table.headingCssClass = "PSPInputTableHeading";
	    this.table.footerCssClass = "PSPInputTableFooting";
	    
    }
    
    
    jsi.psp.InputTable.prototype.getXML = function(ignoreEmptyRows)
    {
        return this.table.getAsXML(ignoreEmptyRows);
    }