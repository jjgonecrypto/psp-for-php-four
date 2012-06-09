<?php

/** A control that amends an attribute of parent nodes with data
 * 
 * The parent's attribute should contain the format string {id} 
 * where id is this control's ID.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Attribute extends PageControl
{
	
	/** The name of the attribute to modify
	 * @var string
	 */ 
	var $Attribute = null;
	
	/** The text to replace the literal
	 * @var string
	 */
	var $Value = null; 
	
	
	function PageControl_Attribute()
	{
		$this->Type = PC_ATTRIBUTE;
		
		parent::PageControl(__FILE__);
		
		$this->CanHaveControls = false;
		
		$this->ChildrenAreUnique = false;
	
		$this->AppendSpecialAttribute(new ControlAttribute("attribute","Attribute"));
		$this->AppendSpecialAttribute(new ControlAttribute("value","Value"));
		
	}	
	
	
	function Control_Render(&$Owner)
	{
		
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		
		if ($this->Value != null && $this->Attribute != null)
		{
			
			$parent = null;
			
			//get the parent 
			if ($this->OriginalNode != null)
			{
				//parent is within template
				$parent = $this->OriginalNode->ParentNode();
				
			}
			else
			{
				//NOTE: This will break if the parent was added programmatically
				
				//NOTE: this doesn't check if original node is a control 
				
				//parent is control's parent
				$parent = $this->Parent->OriginalNode;
			
				if ($parent->IsSpecialAttribute($this->Attribute))
				{
					trigger_error("Attribute controls cannot modify the psp attributes of a parent control. Attribute control id of ".$this->ID,E_USER_ERROR);
				}				
			}
			
			$att = $parent->GetAttribute($this->Attribute);
			
			$att_new = str_replace("{".$this->ID."}",$this->Value,$att);
			
			$parent->SetAttribute($this->Attribute,$att_new);
			
			
		}
		
		
		//remove me if exists in template 
		if ($this->OriginalNode != null)
		{
			$this->RemoveMe();
		}
		
		return true;
	}
	
}

?>