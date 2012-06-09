<?php

/** A generic button for a grid
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class GridButton
{
	/** The name of the button
	 * @var string
	 */
	var $Name = null;
	
	/** The URI to redirect to on click
	 * @var string
	 */
	var $RedirectUri = null;
	
	
	/** Javascript confirm text on click
	 * @var string
	 */
	var $ConfirmText = null;
	
	/** Indicates that this button should postback to the page.
	 * @var bool
	 */
	var $DoPostBack = null;
	
	
	/** States whether or not this button requires grid rows to be visible.
	 * @var bool
	 */
	var $RequiresElements = null;
	
	
	/** States whether or not this buttonallows.
	 * @var bool
	 */
	var $AllowsMultipleElements = null;
	
	
	var $Title = null;
	
	
	function GridButton($Name)
	{
		$this->Name = $Name;
		
		$this->DoPostBack = true;
		
		$this->RequiresElements = false;
		
		$this->AllowsMultipleElements = false;
	}
	
}
?>