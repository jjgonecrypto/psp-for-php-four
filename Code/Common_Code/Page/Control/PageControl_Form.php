<?php

/** A form control
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Form extends PageControl
{
	
	/** The encoding type for the form
	 * @var string
	 */
	var $Encoding = null; 
	
	
	function PageControl_Form()
	{
		$this->Type = PC_FORM;
		
		parent::PageControl(__FILE__);
		
		
		$this->CanHaveControls = true;
		
		$this->ChildrenAreUnique = false;
		
		$this->AppendSpecialAttribute(new ControlAttribute("encoding","Encoding"));
		
		$this->NewNodeName = "form";
		
	}	
	
	function Control_Render(&$Owner)
	{
		//call parent render
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		
		if ($this->Encoding != null)
		{
			$this->NewNode->SetAttribute("enctype",$this->Encoding);	
		}
		
		$this->NewNode->SetAttribute("method","post");
		$this->NewNode->SetAttribute("action","");
		
		
		//NOTE: should fix this to load at beginning of Page.Control_Render, when rendering is fixed
		
		$doc = $this->NewNode->OwnerDocument();
		$hdn = $doc->CreateElement("input");
		$hdn->SetAttribute("type","hidden");
		$hdn->SetAttribute("name","_psp_POSTBACKER");
		
		$this->NewNode->AppendChild($hdn);
		
		
		
		//replace this element if it exists in a template
		if ($this->OriginalNode != null)
		{
			$this->ReplaceElement();
		}
		
		return true;
	}
	
}

?>