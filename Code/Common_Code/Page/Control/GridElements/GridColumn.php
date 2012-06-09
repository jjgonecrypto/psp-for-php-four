<?php

/** A generic column for a grid
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class GridColumn
{
	
	/** The heading of the column.
	 * @var string
	 */
	var $Heading = null;
	
	
	/** The name of the datafield for this column.
	 * @var string
	 */
	var $Datafield = null;
	
	
	/** The css class for this column.
	 * @var string
	 */
	var $CssClass = null;
	
	//TODO
	var $CallbackOnProcess = null; 
	
	
	function GridColumn($Heading, $Datafield, $cssclass = null)
	{
		$this->Heading = $Heading;
		
		$this->Datafield = $Datafield;	
		
		$this->CssClass = $cssclass;
	}
	
	function SetCallback($callback)
	{
		$this->CallbackOnProcess = $callback;	
	}
}
?>