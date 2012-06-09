<?php

/** A literal control
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Literal extends PageControl
{
	/** The text to replace the literal
	 * @var string
	 */
	var $Text = null; 
	
	

	function PageControl_Literal()
	{
		$this->Type = PC_LITERAL;
		
		parent::PageControl(__FILE__);
		
		
		$this->CanHaveControls = false;
		
		$this->ChildrenAreUnique = false;
		
		$this->NewNodeName = "";
		
		$this->AppendSpecialAttribute(new ControlAttribute("text","Text"));
		$this->AppendSpecialAttribute(new ControlAttribute("tag","NewNodeName"));
		
		
	}	
	
	function Control_Render(&$Owner)
	{
		
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		$doc = $this->NewNode->OwnerDocument();
		
		//allow HTML elements here
		if (is_a($this->Text,"PspDomElement"))
		{
			
			//IN PHP5 use Document Fragment here
			
			if ($this->OriginalNode != null)
			{
				//NOTE: this is a workaround
				$this->OriginalNode->InsertBeforeMe($this->Text->ChildNodes());
				
				$parent = $this->OriginalNode->ParentNode();
			
				$parent->RemoveChild($this->OriginalNode);	
			}
		}
		else
		{
			$this->NewNode->AppendChild($doc->CreateTextNode($this->Text));
			
			//replace this element if it exists in a template
			if ($this->OriginalNode != null)
			{
				$this->ReplaceElement(false);
			}
			
		}
		
		
		
		
		
		return true;
	}
	
}

?>