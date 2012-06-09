<?php

/** Datasource that provides an enumerator for an XML doc
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */ 
class Datasource_XML
{
	var $Data = null;
	
	/** Either a new datasource, or one from existing data
	 * @param Mixed [Optional, default is empty array]
	 */
	function Datasource_XML($data = null)
	{
		if ($data == null)
		{
			$data = new PspDomDocument();	
		}
		
		$this->Data = $data;
	}
	
	
	//public abstract function GetEnumerator();	
	function GetEnumerator()
	{
		return new Datasource_XMLEnumerator($this);
		
	}
	
	//NOTE: untested!
	function RowCount()
	{
		$rows = $this->Data->XPathQuery("/*/*");
		
		return count($rows);
			
	}
	
	
}


?>