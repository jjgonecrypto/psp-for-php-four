//check for jsi existence
if (!jsi)
{
    throw("Cannot use an Ajax control without the jsi javascript:interface project.");
}


jsi.psp.Ajax = function(pspid, clientid)
{

    jsi.psp.PageControl.call(this, pspid, clientid);
    
    
    this.inputArray = new Array();					
    this.outputArray = new Array();

    this.startup = new Array();
    this.complete = new Array();
    
    this.outputFunction = null;
    
    //private
    this._firstRun = true;
}    
    
    jsi.psp.Ajax.prototype.addInput = function(label, objectId)
    {
        this.inputArray.push(new jsi.ajax.Element(label, new jsi.dhtml.Element(objectId)));
    }
    
    jsi.psp.Ajax.prototype.addOutput = function(label, objectId)
    {
        this.outputArray.push(new jsi.ajax.Element(label, new jsi.dhtml.Element(objectId)));
    }
    
    jsi.psp.Ajax.prototype.addStartup = function(fnc)
    {
        this.startup.push(fnc);
    }
    
    jsi.psp.Ajax.prototype.addComplete = function(fnc)
    {
        this.complete.push(fnc);
    }
    
    jsi.psp.Ajax.prototype.setOutputFunction = function(fnc)
    {
        this.outputFunction = fnc;
    }
    
    jsi.psp.Ajax.prototype.start = function()
    {
        
        if (!jsi.dhtml.isLoaded)
        {
            //check JSI DHTML package is loaded
            throw("Fatal Error: JSI DHTML package is not loaded.");
        }
        else if (!jsi.ajax.isLoaded)
        {
            //check JSI AJAX package is loaded
            throw("Fatal Error: JSI AJAX package is not loaded.");
        }
    
	    var input = this.inputArray;
	    
        //because these objects may be any element, must call a loadObject first to set class    
        for (var i in input)
        {
            if (typeof(input[i].element) == "object")
            {
                input[i].element = jsi.dhtml.resetElement(input[i].element);
            }
        }                
        
        if (this._firstRun)
        {    
	        //psp ajax control flag
	        input.push(new jsi.ajax.Element("[= this.GetAjaxFlagKey]","[= this.GetAjaxFlagValue]"));
	        input.push(new jsi.ajax.Element("[= this.GetAjaxSenderKey]",this.id));
	        
	        this._firstRun = false;
	    }
	    
	    var output = this.outputArray;
	    
	    //because these objects may be anything, must call a loadObject first to set class    
        for (var i in output)
        {
            if (typeof(output[i].element) == "object")
            {
                output[i].element = jsi.dhtml.resetElement(output[i].element);
            } 
        }
        
	    var ajax = new jsi.ajax.Automate(input, output, window.location.href, true);
    	
    	//foreach startup
    	for (var x in this.startup)
    	{   
    	    ajax.addStartupFunction(this.startup[x]);
    	}
        
	    //foreach complete...
	    for (var x in this.complete)
    	{   
    	    ajax.addCompleteFunction(this.complete[x]);
    	}
    	
    	//set output function if any
        if (this.outputFunction != null)
        {
            ajax.outputFunction = this.outputFunction;
        }
    	
    	//execute the ajax automation
	    ajax.start();	

    }