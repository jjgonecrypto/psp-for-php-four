<?php

//implements IEnumerator

/** An enumerator for a DB resource datasource.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class Datasource_DBResourceEnumerator
{
	var $Datasource = null;
	
	var $Counter = 0;
	
	function Datasource_DBResourceEnumerator($ds)
	{
		$this->Datasource = $ds;	
	}
	
	/** Get the next row of data.
	 * @return DataRow
	 */
	function GetNext()
	{
		global $Database;
	
		$row = $Database->fetchAssoc($this->Datasource->Data);
		
		$this->Counter++;
		
		if ($row != null)
		{
			return new DataRow($row);
		}
		else
		{
			return null;
		}	
	}
}

?>