var _psp_grid = new PSPGrid();

function PSPGrid()
{
    _clickedItems = 0; 
    
    this.mouseoverRow = function(rowObj)
    {
	    rowObj.setAttribute("class","PSPGridRowHover");
	    rowObj.setAttribute("className","PSPGridRowHover");
    }

    this.mouseoutRow = function(rowObj)
    {
	    rowObj.setAttribute("class","");
	    rowObj.setAttribute("className","");
    }

    this.mousedownRow = function(inputId, noIncrement)
    {
	    var checkitem = jsi.dhtml.loadFormitemSingle(inputId);
	    var status = checkitem.getChecked();
	    checkitem.setChecked(!status);
	    if (noIncrement == null)
	    {
	        if (!status)
	        {
	            if (checkitem.getAttribute("type") == "checkbox")
	            {
	            
	                _clickedItems++;
	            }
	            else
	            {
	                _clickedItems = 1;
	            }
	        }
	        else
	        {
	            if (checkitem.getAttribute("type") == "checkbox")
	            {
	                _clickedItems--;
	            }
	            else
	            {
	                _clickedItems = 0;
	            }
	        }
	    }
    }

    this.checkitemClicked = function(obj)
    {
        if (obj.type == "checkbox")
        {
            this.mousedownRow(obj.id, true);
        }
    }
    
    this.buttonClicked = function(requiresElements, allowsMultiple, confirmText)
    {
        //check min one
        if (requiresElements)
        {
            //ensure at least one has been clicked
            if (_clickedItems < 1)
            {
                alert("At least one element must be selected.");
                return false;    
            }
            
        }
        
        //check max one
        if (!allowsMultiple)
        {
            if (_clickedItems > 1)
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

}