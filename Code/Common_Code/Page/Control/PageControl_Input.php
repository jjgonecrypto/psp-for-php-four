<?php

/** A form input control
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Input extends PageControl
{
	/** The value of this input
	 * @var string
	 */
	var $Value = null; 
	
	/** The type of this input
	 * @var string
	 */
	var $InputType = null;
	
	
	function PageControl_Input()
	{
		$this->Type = PC_INPUT;
		
		parent::PageControl(__FILE__);
		
		$this->CanHaveControls = false;
		
		$this->ChildrenAreUnique = false;
		
		$this->AppendSpecialAttribute(new ControlAttribute("type","InputType"));
		$this->AppendSpecialAttribute(new ControlAttribute("value","Value"));
		
		$this->NewNodeName = "input";
	}	
	
	
	/** Override the Init event to set this node to a textarea if required.
	 *
	 */ 
	function Control_Init()
	{
		parent::Control_Init();
		
		if ($this->InputType == "textarea")
		{
			$this->NewNodeName = "textarea";	
		}	
	}
	
	/** Load up controls from postback
	*
	*/ 
	function Control_Preload()
	{
		
		//get value from POSTBACK 
		$value = Postback::Get($this);
		
		if (is_string($value))
		{
			$this->Value = $value;
		}
		
		parent::Control_Preload();
	}
	
	
	function Control_Render(&$Owner)
	{
		
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		//set attributes
		$this->NewNode->SetAttribute("name",$this->GetClientID());

		if ($this->InputType != null)
		{
			$this->NewNode->SetAttribute("type",$this->InputType);
		}
		
		if ($this->Value != null)
		{
			if ($this->InputType == "textarea")
			{
				$doc = $this->NewNode->OwnerDocument();
				
				$this->NewNode->AppendChild($doc->CreateTextNode($this->Value));	
			}
			else
			{
				$this->NewNode->SetAttribute("value",$this->Value);
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