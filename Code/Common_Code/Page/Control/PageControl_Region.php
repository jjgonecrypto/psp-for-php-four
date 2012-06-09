<?php
/** A region control - used in a master page to define a content region
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Region extends PageControl
{
	var $ContentControl = null;
	
	function PageControl_Region()
	{
		$this->Type = PC_REGION;
		
		parent::PageControl(__FILE__);
		
		$this->CanHaveControls = false;
		
		$this->ChildrenAreUnique = false;
		
		
	}	
	
	function Control_Load()
	{
		//this control cannot be loaded programmatically
		if ($this->OriginalNode == null)
		{
			trigger_error("The Region control cannot be loaded programmatically. It must exist within a template.", E_USER_ERROR);	
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
		
		//remove region if content not set
		if ($this->ContentControl == null)
		{
			$this->RemoveMe();
			return false;
		}
		
		//NOTE: to get here, OriginalNode must exist
		//In PHP5 could do this some other way
		
		
		$doc = $this->OriginalNode->OwnerDocument();
		
		//NOTE: for PHP5 Will use importNode() here
		
		$p = $this->OriginalNode->ParentNode();
		
		foreach ($this->ContentControl->OriginalNode->ChildNodes() as $kid)
		{
			$p->InsertBefore($kid,$this->OriginalNode);
		}
		
		$this->RemoveMe();
		
		return true;
	}
	
	/** Set the appropriate content control for this region
	 * @param PageControl_Content The content control to be inserted into this region
	 */
	function SetContent(&$control)
	{
		$this->ContentControl =& $control;	
	}
	
	
}

?>