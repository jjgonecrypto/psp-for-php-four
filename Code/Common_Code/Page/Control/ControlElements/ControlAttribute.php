<?php

/** Any attribute within a control.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class ControlAttribute
{
	
	/** The name of the attribute that the control supports
	 * @var string
	 */
	var $Attribute = null;
	
	/** The name of the control's property for this attribute 
	 *
	 */
	var $Property = null;
		

	//NOTE: this should become an enum in php5		
	/** The type of the control's attribute
	 * @var enum STRING | BOOL | INT
	 */
	var $Type = null;
	 	
		
	function ControlAttribute($att, $prop, $type = "STRING")
	{
		$this->Attribute = $att;
		$this->Property = $prop;	
		$this->Type = $type;
	}
	
	
	/** Verify and return the value for this control attribute. 
	 * @return mixed (bool / int / string)
	 */
	function VerifyValue($control, $value)
	{
		//type decide	
		if ($this->Type == "BOOL")
		{
			return strtolower($value) == "true";	
		}
		else if ($this->Type == "INT")
		{
			if (!is_numeric($value))
			{
				trigger_error("Control attribute found is not numeric. ",E_USER_ERROR);	
			}
		}
		
		return 	$value;
	}
	

}
?>