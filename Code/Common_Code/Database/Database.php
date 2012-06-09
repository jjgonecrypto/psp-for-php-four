<?php

/** Abstract class to represent database calls
 *  NOTE: technically, the class is not abstract, but should be treated as such.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 * 
 */

class Database {
	
	var $Server;
	
	var $Database;
	
	var $Username;
	
	var $Password; 
	
	/**
	 * Initialise the connection settings of to this database.
	 *
	 * @param string $server
	 * @param string $db
	 * @param string $user
	 * @param string $pword
	 */
	function Initialise($server, $db, $user, $pword)
	{
		$this->Server = $server;
		$this->Database = $db;
		$this->Username = $user; 
		$this->Password = $pword;	
		
	}
	
	// {{{ Abstract Functions 
	
	//public abstract function Connect();
	//public abstract function Close();
	
	//public abstract function Query($queryStr);
	//public abstract function FetchAssoc($result);
	//public abstract function RowCount($result);
	//public abstract function CloseQuery($result);
	
	//public abstract function GetInsertId();
	//public abstract function AffectedRows($resource);
	
	
	//NEW FUNCTIONS
	//public abstract function dataSeek($result, $index);
	
	
	// }}}
	
}


?>