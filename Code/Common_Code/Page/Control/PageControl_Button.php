<?php

/** A button webpage control. 
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Button extends PageControl
{
	
	var $Name = null;

	
	/** Does the button post back the form. Default is true. 
	 * @var bool
	 */
	var $DoesPostback = null;
	
	
	/** If the button doesn't postback, then it redirects to this address.
	 * @var string
	 */
	var $Href = null;
	
	
	/** Tooltip for this button.
	 * @var string
	 */
	var $Title = null;
	
	
	/** Array of javascript commands to execute prior to postback
	 * @var array
	 */
	var $ClientFunction = null; 
	
	
	
	/** The model name -> either BASIC or TEMPLATE. Default is TEMPLATE.
	 * @var string 
	 */
	//var $Model = null; 
	
	
	function PageControl_Button()
	{
		$this->Type = PC_BUTTON;
		
		parent::PageControl(__FILE__);
		
		$this->CanHaveControls = true;
		$this->ChildrenAreUnique = false;
	
		$this->DoesPostback = true;
			
		
		$this->AppendSpecialAttribute(new ControlAttribute("name","Name"));
		$this->AppendSpecialAttribute(new ControlAttribute("postback","DoesPostback","BOOL"));
		$this->AppendSpecialAttribute(new ControlAttribute("href","Href"));
		$this->AppendSpecialAttribute(new ControlAttribute("title","Title"));
		//$this->AppendSpecialAttribute(new ControlAttribute("model","Model"));
		$this->AppendSpecialAttribute(new ControlAttribute("onclick","ClientFunction"));
		$this->AppendSpecialAttribute(new ControlAttribute("usetemplate","UseTemplate","BOOL"));
		
		
	}	
	
	function Control_Init()
	{
		parent::Control_Init();
		
		//set the assigned template node
		$this->AssignedTemplateNode = $this->Template->Document->XPathQuerySingle("//a[@buttonlink='true']");
		
		
	}
	
	
	function Control_Load()
	{
		
		
		//set the postback code
		
		
		//set the control data
		$controlx =& $this->FindControl("buttonName");
		$controlx->Text = $this->Name;
		
		if ($this->DoesPostback)
		{
			//set the postback function 
			$controly =& $this->FindControl("attClientFnc");
			
			if ($this->ClientFunction != null)
			{
				$controly->Value = $this->ClientFunction;
			}
			else
			{
				$controly->Value = "true";	
			}
			
			//set the button name
			$controly =& $this->FindControl("attButton");
			$controly->Value = $this->GetClientID();
		}
		
		if (!$this->UseTemplate)
		{
			//don't use the template
			$this->NewNodeName = "input";	
			
		}
		
		
		//load the child controls
		parent::Control_Load();
		
	}
	
	function Control_Render(&$Owner)
	{
		//call parent render
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		
		if ($this->UseTemplate)
		{
			
			//Postback action
			if ($this->DoesPostback)
			{
				//do nothing
			}
			else if ($this->Href != "")
			{
				//get the redirect href attribute
				$this->AssignedTemplateNode->SetAttribute("href",$this->Href);
				
				//clear the onclick event
				$this->AssignedTemplateNode->SetAttribute("onclick","");
					
			}
			else
			{
				$this->AssignedTemplateNode->SetAttribute("onclick",$this->ClientFunction);
				
			}
			
			
			$this->AssignedTemplateNode->RemoveAttribute("buttonlink");
			$this->AssignedTemplateNode->RemoveAttribute("postbackhref");
			
			$this->AssignedTemplateNode->SetAttribute("title",$this->Title);
		}
		else
		{
			
			$this->NewNode->SetAttribute("value",$this->Name);
			
			
			//Postback action
			if ($this->DoesPostback)
			{
				$this->NewNode->SetAttribute("type","submit");
				
				$this->NewNode->SetAttribute("onclick",$this->AssignedTemplateNode->GetAttribute("onclick"));
			}
			else if ($this->Href != "")
			{
				$this->NewNode->SetAttribute("type","button");
					
				$this->NewNode->SetAttribute("onclick","void window.location.href='".$this->Href."';");
			}
			else
			{
				$this->NewNode->SetAttribute("type","button");
				
				$this->NewNode->SetAttribute("onclick",$this->ClientFunction);
					
			}
			
		}
		
		
		
		//replace this element if it exists in a template
		if ($this->OriginalNode != null)
		{
			$this->ReplaceElement(false);
		}
		
		return true;
	}
	
}

?>