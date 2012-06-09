<?php

/** A simple key/value Url query string variable.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class UrlVariable
{
	var $Name = null;
	
	var $Value = null;
	
	function UrlVariable($name, $value)
	{
		$this->Name = $name;
	
		$this->Value = $value;	
	}
	
}
?>