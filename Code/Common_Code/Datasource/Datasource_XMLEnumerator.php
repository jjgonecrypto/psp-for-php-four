<?php

//implements IEnumerator

/** Enumerator for XML Datasource
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class Datasource_XMLEnumerator
{
	var $Datasource = null;
	
	var $Counter = 0;
	
	function Datasource_XMLEnumerator($ds)
	{
		$this->Datasource = $ds;	
	}
	
	/** Get the next row of data.
	 * @return DataRow
	 */
	function GetNext()
	{
		
		//TODO::
		//get the XML row and make a datarow from it (by first making an 
		//associative array from the data)
		
		/**
		if ($this->Counter >= count($this->Datasource->Data))
		{
			return null;	
		} 
		
		$akeys = array_keys($this->Datasource->Data);
		
		$result = $this->Datasource->Data[$akeys[$this->Counter]];
		
		$this->Counter++;
		
		if ($result != null)
		{
			//allow a datasource to be comprised of datarows
			if (is_a($result, "DataRow"))
			{
				return $result;	
			}
			else
			{
				return new DataRow($result);
			}
		}
		else
		{
			return null;
		}	
		*/
	}
}

?>