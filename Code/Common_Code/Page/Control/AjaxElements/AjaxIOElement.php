<?php

/** An AJAX Input/Output element
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class AjaxIOElement
{
	var $Label = null;
	
	var $Control = null;	
	
	function AjaxIOElement($lbl, &$ctrl)
	{
		$this->Label = $lbl;
		$this->Control =& $ctrl;	
	}
}
?>