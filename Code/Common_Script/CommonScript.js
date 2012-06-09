if (!jsi)
{
   throw("Fatal Error: JSI javascript package not loaded."); 
}


jsi.psp = new jsi.Package("psp")
{
    //This is called BEFORE the parent constructor!!
}

    jsi.psp.ctrlInstances = new Array();

    /** Postback function 
     * 
     * @param string The id of the object that is causing the postback. 
     */
     jsi.psp.doPostback = function(objId)
     {
        //check JSI project exists
        if (!jsi.dhtml.isLoaded)
        {
            //check JSI DHTML package is loaded
            throw("Fatal Error: JSI DHTML package is not loaded.");
        } 
        
        var Postbacker_ID = "[= this.GetPostbackVariable]";

        var Postbacker = new jsi.dhtml.Formitem(Postbacker_ID);
        
        
        //set the hidden field with the data 
        Postbacker.setValue(objId);
        
      
        //submit form
        //NOTE: this should be via the validation package if we want validation
        if (jsi.validation.isLoaded)
        {
            jsi.validation.doSubmit(document.forms[0].id);
        }
        else
        {
            document.forms[0].submit();
        }
        
     }    
     
      
    /** Page Controls base class
     *
     */
    jsi.psp.PageControl = function(pspid, clientid)
    {
        this.id = pspid;

        this.clientID = clientid;
    }


    jsi.psp.addControlInstance = function(type, control)
    {
        //start a new array for this control
        if (this.ctrlInstances[type] == null)
        {
            this.ctrlInstances[type] = new Array();
        }

        this.ctrlInstances[type][control.id] = control;

        return control;
    }

    jsi.psp.getControlInstance = function(type, controlid)
    {
        return this.ctrlInstances[type][controlid];
    }

