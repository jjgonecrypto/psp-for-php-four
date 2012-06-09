<?php


/** The MySQL 4.1 + implementation of the Database class.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class Database_MySQLi extends Database 
{
	
	//private
	var $mysqli; 
	
	
	function Database_MySQLi($server, $db, $user, $pword)
	{
		parent::initialise($server, $db, $user, $pword);
	}
	
	
	function Connect() {
		
		
		$mysqli = new mysqli($this->Server,$this->Username,$this->Password,$this->Database);
		
		if (mysqli_connect_errno()) 
		{
			trigger_error("MySQLi Error: ".mysqli_connect_error(),E_USER_ERROR);
		}
		
		$this->mysqli = $mysqli;
		
	}
	
	
	function Close() 
	{
		$this->mysqli->close();
	}
	
	
	/**
	 * Connect to MySQL datasource and return resource for query
	 * @param String query string
	 * @return Resource executed query resource
	 */
	function Query($query) {
		
		if ($this->mysqli == null)
		{
			$this->Connect();
		}
		
		//Store this result by default (allows nested queries without trouble)
		if ($result = $this->mysqli->query($query, MYSQLI_STORE_RESULT))
		{
			return $result;
		}
		else
		{
			trigger_error("MySQLi Error: ".$this->mysqli->error."\nSQL: <pre>".$query."</pre>",E_USER_ERROR);
		}
		
	}
	
	
	function CloseQuery($resource)
	{
		//TODO: check this
		$resource->close();
	}
	
	
	function FetchAssoc($resource) 
	{
		return $resource->fetch_assoc();
	}
	
	
	function RowCount($resource) 
	{
		return $resource->num_rows;
	}
	
	
	function GetInsertId() 
	{
		return $this->mysqli->insert_id;
	}
	
	
	function AffectedRows($resource) 
	{
		return $this->mysqli->affected_rows;
	}
	
	function DataSeek($resource, $index)
	{
		return $resource->data_seek($index);	
	}
} 

?>