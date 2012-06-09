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
class PspDomElement extends PspDomNode
{
	
	
	function PspDomElement($element)
	{
		parent::PspDomNode($element);	
	}	
	
	/** Helper XPath relative query. 
	 *
	 */
	function XPath($xpath)
	{
		$doc = $this->OwnerDocument();
		return $doc->XPathQuery($xpath, $this);
	}
	
	/** Helper XPath relative query. Returns first result only.
	 * @param string Xpath query.
	 * @return PspDomNode
	 */
	function XPathSingle($xpath)
	{
		$doc = $this->OwnerDocument();
		return $doc->XPathQuerySingle($xpath, $this);
	}
	
	
	
	// {{{ DOM Functions
	
	function GetAttribute($name)
	{
		
		return $this->DomNode->get_attribute($name);
	}
	
	function SetAttribute($name, $value)
	{
		return $this->DomNode->set_attribute($name, $value);
	}
	
	function HasAttribute($name)
	{
		return $this->DomNode->has_attribute($name);
	}
	
	function RemoveAttribute($name)
	{
		return $this->DomNode->remove_attribute($name);
	}
	
	function GetElementsByTagName($name)
	{
		$tmp = array();
		
		$arr = $this->DomNode->get_elements_by_tagname($name);
		
		foreach ($arr as $a)
		{
			$tmp[] = $this->NodeFactory($a);	
		}	
		
		return $tmp;
	}
	// }}}
	
}
?>