<?php

//implements IEnumerator

/** Plain Datasource enumerator
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class DatasourceEnumerator
{
	var $Datasource = null;
	
	var $Counter = 0;
	
	function DatasourceEnumerator($ds)
	{
		$this->Datasource = $ds;	
	}
	
	/** Get the next row of data.
	 * @return DataRow
	 */
	function GetNext()
	{
		
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
	}
}

?>