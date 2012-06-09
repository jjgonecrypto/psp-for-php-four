<?php

/** A content webpage control - for defining blocks of code to be inserting into a master page 
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Content extends PageControl
{
	
	/** The ID of the Region control within a master page this content is for
	 * @var string
	 */
	var $RegionID = null;
	
	function PageControl_Content()
	{
		$this->Type = PC_CONTENT;
		
		parent::PageControl(__FILE__);
		
		
		$this->CanHaveControls = true;
		
		$this->ChildrenAreUnique = false;
		
		$this->AppendSpecialAttribute(new ControlAttribute("region","RegionID"));
		
		
		
	}	
	
	//override base obj
	function Control_Init()
	{
		
		parent::Control_Init();
		
		//get a reference to the page
			
		//set the parent to be page, as content controls 
		$this->Parent =& WebsiteControl::GetPage();
		
	}
	
	function Control_Render(&$Owner)
	{
		
		//call parent render
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		//get a reference to the page
		$Page =& WebsiteControl::GetPage();
		
		if ($Page->Master == null)
		{
			trigger_error("Master page doesn't exist for control of ID: ".$this->ID, E_USER_ERROR);	
		}
		
		$RegionControl =& $Page->Master->FindRegion($this->RegionID);;
		
		$RegionControl->SetContent($this);
		
		
		return true;
	}
	
	
	
	
}

?>