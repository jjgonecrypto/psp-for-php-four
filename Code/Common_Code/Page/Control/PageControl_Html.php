<?php

/** Any html control, allowing visiblity 
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Html extends PageControl
{
	/** The text to replace the literal
	 * @var string
	 */
	var $Text = null; 
	
	
	var $NodeName = null;
	
	
	function PageControl_Html($node_name)
	{
		$this->Type = PC_HTML;
		$this->NodeName = $node_name;
		
		parent::PageControl(__FILE__);
		
		$this->CanHaveControls = true;
		
		$this->ChildrenAreUnique = false;
		
		
	}	
	

	
	function Control_Render(&$Owner)
	{
		
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		//remove unwanted attributes
		foreach ($this->SpecialAttributes as $sa)
		{
			$this->OriginalNode->RemoveAttribute($sa->Attribute);	
		}
		
		foreach ($this->Attributes as $a)
		{	
			//don't get special attributes
			if (!$this->IsSpecialAttribute($a->GetName()))
			{
				$val = $a->GetValue();
				
				if (strstr($val,"~/"))
				{
					$val = str_replace("~/",CMS_WEBSITE_LINK,$val);	
				}
				else
				{
					$val = str_replace("~",CMS_WEBSITE_LINK,$val);	
				}
				//replace any Tilda's with the base webaddress
				$this->OriginalNode->SetAttribute($a->GetName(),$val);		
			}
		}
		
		//set the client id if reqd
		$this->OriginalNode->SetAttribute("id",$this->GetClientID());
		
		return true;
	}
	
}

?>