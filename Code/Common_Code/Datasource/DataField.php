<?php

/** A Datafield within a row
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class DataField
{
	var $Name = null;
	
	var $Value = null;
	
	function DataField($name, $val)
	{
		$this->Name = $name;
		$this->Value = $val;	
	}	
	
}
?>