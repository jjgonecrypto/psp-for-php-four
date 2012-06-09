<?php

/** A conditional IE comment, allowing child controls.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Conditional extends PageControl
{
	/** The text of the condition
	 * @var string
	 */
	var $Condition = null; 
	
	function PageControl_Conditional()
	{
		$this->Type = PC_CONDITIONAL;
		
		parent::PageControl(__FILE__);
		
		$this->CanHaveControls = true;
		
		$this->ChildrenAreUnique = false;
		
		$this->AppendSpecialAttribute(new ControlAttribute("condition","Condition"));
		
	}	
	

	
	function Control_Render(&$Owner)
	{
		
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		
		$doc = $this->OriginalNode->OwnerDocument();
		
		$comment_text = "[if ".$this->Condition."]>";
		
		$comment_text .= $this->OriginalNode->InnerXml();
		
		$comment_text .= "<![endif]";
		
		
		//need to render the opening conditional comment instead of the opening tag 
		$this->NewNode = $doc->CreateComment($comment_text);
		
		
		//replace this element if it exists in a template
		if ($this->OriginalNode != null)
		{
			$this->ReplaceElement(false);
		}
		
		//need to call the page to tell it where we rendered so the 
		//CSS will be rendered BEFORE it
		$Page =& WebsiteControl::GetPage();
		
		$Page->SetConditional($this->NewNode);
		
		
		
		return true;
	}
	
}

?>