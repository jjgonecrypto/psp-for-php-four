<?php

/** A simple encapsulation of the DOMXML package in php4 to 
 * emulate the PHP5 DOM package, and help create a smooth transition 
 * between versions.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PspDomAttr
{
	var $DomAttribute = null;
	
	function PspDomAttr(&$node)
	{
		$this->DomAttribute = $node;	
	}	
	
	function GetName()
	{
		return $this->DomAttribute->name();
	}
	
	function GetValue()
	{
		return $this->DomAttribute->value();	
	}

	function SetValue($val)
	{
		//workaround to 
		//problem with $this->DomAttribute->set_value()
		$x = $this->DomAttribute->first_child();
		
		$x->set_content($val);
	}
	
	//syn of GetValue
	function GetNodeValue()
	{
		return $this->GetValue();
	}
	
	//syn of SetValue
	function SetNodeValue($val)
	{
		$this->SetValue($val);
	}
}
?>