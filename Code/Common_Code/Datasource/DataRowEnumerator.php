<?php

//implements IEnumerator

/** A field enumerator for a datarow
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
 
//class DataRowEnumerator implements IEnumerator
class DataRowEnumerator
{
	var $DataRow = null;
	
	var $Counter = 0;
	
	function DataRowEnumerator($dr)
	{
		$this->DataRow = $dr;	
	}
	
	/** Get the next field
	 * @return DataField
	 */
	function GetNext()
	{
		
		if ($this->Counter >= $this->DataRow->NumColumns())
		{
			return null;	
		} 
		
		$cols = $this->DataRow->GetColumns();
		
		$column = $cols[$this->Counter];
		
		$result = $this->DataRow->Get($column);
		
		
		$this->Counter++;
		
		
		//allow a datasource to be comprised of datarows
		if (is_a($result, "DataField"))
		{
			return $result;	
		}
		else
		{
			return new DataField($column, $result);
		}
		
	}
}

?>