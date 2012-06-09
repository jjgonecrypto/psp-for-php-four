<?php

/** An anchor webpage control - for using server paths inside links. 
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Anchor extends PageControl
{
	var $Href = null;
	
	function PageControl_Anchor()
	{
		$this->Type = PC_ANCHOR;
		
		parent::PageControl(__FILE__);
		
		$this->CanHaveControls = true;
		$this->ChildrenAreUnique = false;
		
		$this->AppendSpecialAttribute(new ControlAttribute("href","Href"));
		
		$this->NewNodeName = "a";
	}	
	
	function Control_Render(&$Owner)
	{
		//call parent render
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		$href_str = $this->Href;
	
		//NOTE: could do this better with Url object
		if (strstr($this->Href,"~/") !== false)
		{
			$href_str = str_replace("~/",CMS_WEBSITE_LINK,$href_str);	
		}
		
		//call again to ensure single tildas also replaced
		$href_str = str_replace("~",CMS_WEBSITE_LINK,$href_str);
		
		$this->NewNode->SetAttribute("href",$href_str);
		
		//replace this element if it exists in a template
		if ($this->OriginalNode != null)
		{
			$this->ReplaceElement();
		}
		
		return true;
	}
	
}

?>