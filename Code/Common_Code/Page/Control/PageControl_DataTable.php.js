//check for jsi existence
if (!jsi)
{
    throw("Cannot use a DataTable control without the jsi javascript:interface project.");
}


jsi.psp.DataTable = function(pspid, clientid, parentid, loadingDivID)
{

    jsi.psp.PageControl.call(this, pspid, clientid);
    
    var self = this;
    
    this.table = new jsi.table.Table(new jsi.dhtml.Element(parentid));
    
    this._renderer = this.table.getRenderer();
    
    if (loadingDivID != null)
    {
        this._loaderDiv = new jsi.dhtml.Element(loadingDivID);
    }
    
    //initialise
    this.init();
    
    
    this.onLoadComplete = function()
    {    
    
        //add js events to rows
        for (var x in self.table.rows)
        {
            row = self.table.rows[x];
            row.addAttribute(new jsi.dhtml.Attribute("class","PSPDataTableRow"));
            row.addAttribute(new jsi.dhtml.Attribute("onmouseover","jsi.psp.getControlInstance('[= this.Type]','" + self.id + "').mouseoverRow(this);"));
            row.addAttribute(new jsi.dhtml.Attribute("onmouseout","jsi.psp.getControlInstance('[= this.Type]','" + self.id + "').mouseoutRow(this);"));
            row.addAttribute(new jsi.dhtml.Attribute("onmousedown","jsi.psp.getControlInstance('[= this.Type]','" + self.id + "').mousedownRow(this);"));
        
        }

        self._renderer.draw();
        
        self._loaderDiv.setDisplay(false);
    
    }
    
}
    
    
     
    jsi.psp.DataTable.prototype.init = function()
    {
        this.table.cssClass = "PSPDataTableTable";
        
        this.table.columnsCssClass = "PSPDataTableHeaders";

        this.table.footerCssClass = "PSPDataTableFooter";
        
        
    }
    
    jsi.psp.DataTable.prototype.setPageSize = function(psize)
    {
        this._renderer.setPageSize(psize);
    }
    
    jsi.psp.DataTable.prototype.loadViaAjax = function()
    {
        this._loaderDiv.setOpacity(75);
        
        var input = new Array();
         
        //psp ajax control flag
        input.push(new jsi.ajax.Element("[= this.GetAjaxFlagKey]","[= this.GetAjaxFlagValue]"));
        input.push(new jsi.ajax.Element("[= this.GetAjaxSenderKey]",this.id));
 
	    var ajax = new jsi.ajax.AutomateTable(this.table, input);
    	
    	ajax.addCompleteFunction(this.onLoadComplete);
    	
    	//execute the ajax automation
	    ajax.start();	
	    
    }
    
    
    
    
    
    ////////////////BUTTON FUNCTIONS
 
    jsi.psp.DataTable.prototype.mouseoverRow = function(rowObj)
    {
	    rowObj.setAttribute("class","PSPDataTableRowHover");
	    rowObj.setAttribute("className","PSPDataTableRowHover");
    }

    jsi.psp.DataTable.prototype.mouseoutRow = function(rowObj)
    {
	    rowObj.setAttribute("class","");
	    rowObj.setAttribute("className","");
    }

    //NOTE: this is hardcoded to the selector being in the first column.
    jsi.psp.DataTable.prototype.mousedownRow = function(rowObj)
    {
        var rowElement = new jsi.dhtml.Element(rowObj);
        
        var checkitem = rowElement.getElementsByTagName("input")[0];
        
        if (checkitem == null)
        {
            return;   
        } 
       
        //load as Checkitem
        checkitem = jsi.dhtml.resetElement(checkitem);
        
        //toggle checked
	    var status = checkitem.getChecked();
	    
	    checkitem.setChecked(!status);
	    
    }

    jsi.psp.DataTable.prototype.checkitemClicked = function(obj)
    {
        if (obj.type == "checkbox")
        {
            this.mousedownRow(obj.id);
        }
    }
    
    jsi.psp.DataTable.prototype.countClicked = function()
    {
        var tbody = this._renderer.tbodyElement;
        
        var inputs = tbody.getElementsByTagName("input");
        
        var counter = 0;
        
        for (var i in inputs)
        {
            input = inputs[i];
            
            input = jsi.dhtml.resetElement(input);
            
            if (input.getChecked())
            {
                counter++;
            }
        }
        
        return counter;
    }
    
    jsi.psp.DataTable.prototype.buttonClicked = function(requiresElements, allowsMultiple, confirmText)
    {
        var clickedItems = this.countClicked();
        
        //check min one
        if (requiresElements)
        {
            //ensure at least one has been clicked
            if (clickedItems < 1)
            {
                alert("At least one element must be selected.");
                return false;    
            }
            
        }
        
        //check max one
        if (!allowsMultiple)
        {
            if (clickedItems > 1)
            {
                alert("Only one element may be selected for this option.");
                return false;
            }
        
        }
        
        //do confirm text
        if (confirmText != "")
        {
            return confirm(confirmText);
        }
        
        return true;
        
    }