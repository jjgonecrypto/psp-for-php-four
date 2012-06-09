<?php


/** The MySQL 4.0 implementation of the Database class.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class Database_MySQL extends Database 
{
	
	//private
	var $connection; 
	
	
	function Database_MySQL($server, $db, $user, $pword)
	{
		parent::initialise($server, $db, $user, $pword);
	}
	
	
	function Connect() {
		
		if (!($db_cid = mysql_connect($this->Server, $this->Username, $this->Password)))
		{
			trigger_error("MySQL Error: ".mysql_error(), E_USER_ERROR);
		}
		
		$this->connection = $db_cid;
	}
	
	
	function Close() 
	{
		mysql_close($this->connection);
	}
	
	
	/**
	 * Connect to MySQL datasource and return resource for query
	 * @param String query string
	 * @return Resource executed query resource
	 */
	function Query($query) {
		
		if (!is_resource($this->connection))
		{
			$this->Connect();
		}
		
		if (!mysql_select_db($this->Database, $this->connection))
		{
			trigger_error("MySQL Error: ".mysql_error(), E_USER_ERROR);
		}
		
		if ($res = mysql_query($query,$this->connection))
		{
			return $res;
		}
		else
		{
			trigger_error("MySQL Error: ".mysql_error()."<br />SQL: <pre>".$query."</pre>",E_USER_ERROR);
		}
		
	}
	
	
	function CloseQuery($resource)
	{
		return mysql_free_result($resource);
	}
	
	
	function FetchAssoc($resource) 
	{
		return mysql_fetch_assoc($resource);
	}
	
	
	function RowCount($resource) 
	{
		return mysql_num_rows($resource);
	}
	
	
	function GetInsertId() 
	{
		return mysql_insert_id($this->connection);
	}
	
	
	function AffectedRows($resource) 
	{
		return mysql_affected_rows($resource);
	}
	
	function DataSeek($resource, $index)
	{
		return mysql_data_seek($resource,$index);	
	}
} 

?>