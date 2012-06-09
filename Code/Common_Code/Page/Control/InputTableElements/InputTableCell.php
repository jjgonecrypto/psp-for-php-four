<?php

/** A cell template within an InputTable control
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 * 
 */
 
class InputTableCell 
{
	var $Heading = null;
	
	
	var $LeftText = null;
	
	
	var $RightText = null;
	
	
	var $Attributes = null;
	
	
	function InputTableCell($heading, $lefttext, $righttext, $attributes)
	{
		$this->Heading = $heading;
		
		$this->LeftText = $lefttext;
		
		$this->RightText = $righttext; 
		
		$this->Attributes = $attributes;	
		
	}
	
	
	function &RenderOnto(&$element)
	{
		$element->SetAttribute("jsi_template","true");
		$element->SetAttribute("jsi_template_heading",$this->Heading);
		$element->SetAttribute("jsi_template_lefttext",$this->LeftText);
		$element->SetAttribute("jsi_template_righttext",$this->RightText);
		
		foreach ($this->Attributes as $attr)
		{
			$element->SetAttribute($attr->GetName(), $attr->GetValue());	
		}
		
		return $element;
	}
	
}
 
 
?>