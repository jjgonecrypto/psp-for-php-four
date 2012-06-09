<?php

/** Single row in a datasource
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */ 
class DataRow
{
	
	/* The Data for this row.
	 * @var Mixed Either an Array or an Object
	 */
	var $Data = null;
	
	
	/** Either a new datarow 
	 * @param Array [Optional]
	 */
	function DataRow($data = array())
	{
		$this->Data = $data;
	}
	
	function GetEnumerator()
	{
		return new DataRowEnumerator($this);	
	}
	
	
	function NumColumns()
	{
		if (is_array($this->Data))
		{	
			return count($this->Data);
		}
		else if (is_object($this->Data))
		{
			return count(get_object_vars($this->Data));
		}
		else
		{
			//single  type data
			return 1;	
		}
	}
	
	/**
	 * @return Array of column names
	 */
	function GetColumns()
	{
		if (is_array($this->Data))
		{
			return array_keys($this->Data);	
		}
		else if (is_object($this->Data))
		{
			return get_object_vars($this->Data);	
		}
		else
		{
			//single type data
			return array($this->Data);		
		}
		
	}
	
	//NOTE: careful, this could update some object (this->Data) if not 
	//handled properly
	function Add($name, $value)
	{
		if (is_array($this->Data))
		{
			$this->Data[$name] = $value;	
		}	
		else if (is_object($this->Data))
		{
			$this->Data->$name = $value;	
		}
		else
		{
			trigger_error("A DataRow of base type, cannot have fields added.",E_USER_ERROR);	
		}
		
	}

	function Get($column)
	{
		if (is_array($this->Data))
		{
			if (isset($this->Data[$column]))
			{
				return $this->Data[$column];
			}
			else
			{
				return "";	
			}		
		}	
		else if (is_object($this->Data))
		{
			if (isset($this->Data->$column))
			{
				return $this->Data->$column;	
			}
			else
			{
				return "";	
			}
		}
		else
		{
			return $this->Data;	
		}
	}
	
	function &GetData()
	{
		return $this->Data;	
	}
}


?>