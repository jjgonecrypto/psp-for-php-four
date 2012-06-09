<?php

/** Used to hold the place of a PSP inline code block
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class InlineCodeBlock
{
	
	var $ControlID = null;
	
	var $PropertyOrFunction = null; 
	

	var $Text = null;
	
	var $IHasControls = null;
	
	
	function InlineCodeBlock($IHasControls,$controlID, $propFnc)
	{
		$this->IHasControls = $IHasControls;
		
		
		$this->ControlID = $controlID;
		
		$this->PropertyOrFunction = $propFnc;
		
	}
	
	function LoadText()
	{
		//allow the use of the "this" object
		if ($this->ControlID == "this")
		{
			$control = $this->IHasControls;
		}
		else
		{
			$control = $this->IHasControls->FindControl($this->ControlID, false);	
		
			if ($control == null)
			{
				trigger_error("Inline code block: ".$this->ControlID.".".$this->PropertyOrFunction." doesn't have a valid control.", E_USER_ERROR);	
			}
		}
		
		
		$prop = $this->PropertyOrFunction;
		
		//NOTE: php5 use property_exists() here
		$tmp = get_object_vars($control);
		
		if (isset($tmp[$prop]))
		{
			$this->Text = $control->$prop;
		}
		else if (method_exists($control,$prop))	
		{
			$text = call_user_func(array($control,$prop));
			
			if (!is_string($text) && !is_numeric($text))
			{
				trigger_error("Inline code block: ".$this->ControlID.".".$this->PropertyOrFunction." function doesn't return string.", E_USER_ERROR );	
			}
			
			$this->Text = $text;
		}
		else
		{
			trigger_error("Inline code block: ".$this->ControlID.".".$this->PropertyOrFunction." invalid property or functin name.", E_USER_ERROR );	
		}	
	}
}

?>