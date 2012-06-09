<?php

/** A Datasource based on a DB resource 
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 * 
 * @todo Currently tied too much to MySQL, should be fixed. 
 */
class Datasource_DBResource extends Datasource
{
	function Datasource_DBResource($data)
	{
		parent::Datasource($data);
	}
	
	function GetEnumerator()
	{
		return new Datasource_DBResourceEnumerator($this);
	}
	
	
	/** Don't inherit AddRow from the parent
	 *
	 */
	function AddRow(&$datarow)
	{
		trigger_error("Cannot add data to a DB Resource Datasource.",E_USER_ERROR);
	}
	
	function RowCount()
	{
		global $Database;
		
		return $Database->RowCount($this->Data);	
	}
	
}

?>