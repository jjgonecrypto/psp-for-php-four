<?php

/** A pageholder control - used to insert controls into progromattically
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Placeholder extends PageControl
{
	
	
	function PageControl_Placeholder()
	{
		
		$this->Type = PC_PLACEHOLDER;
		$this->CanHaveControls = true;
		$this->ChildrenAreUnique = false;
		
		parent::PageControl(__FILE__);
		
	}	
	
	function Control_Load()
	{
		
		//this control cannot be loaded programmatically
		if ($this->OriginalNode == null)
		{
			trigger_error("The Placeholder control cannot be loaded programmatically. It must exist within a template.", E_USER_ERROR);	
		}	
		
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
		
		//NOTE: to get here, OriginalNode must exist
		//In PHP5 could do this some other way
		
		$parent = $this->OriginalNode->ParentNode();
		
		//insert each control before this one
		foreach ($this->Controls as $control)
		{
			$parent->InsertBefore($control->NewNode,$this->OriginalNode);
		}	
		
		//remove me 
		if ($this->OriginalNode != null)
		{
			$this->RemoveMe();
		}
		
		return true;
	}
	
}

?>