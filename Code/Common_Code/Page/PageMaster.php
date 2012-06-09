<?php

/** A website master page.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageMaster extends BasePage
{
	
	/** The page/master that owns this master
	 * @var BasePage
	 */
	var $Owner = null;
	
	
	function PageMaster($filename)
	{
		//constructor
		parent::BasePage($filename);	
	}
	
	function SetOwner(&$owner)
	{
		$this->Owner =& $owner;
	}
	
	function Page_Load()
	{
		//base load	
		parent::Page_Load();	
	}	
	
	/** Return the page XHTML
	 * @return string
	 */
	function Page_Render()
	{
		$document = parent::Page_Render();
		
		return $document;
	}
	
	/** Search through this and all previous master pages for a region.
	 * @param string The ID of the region to find
	 */
	function &FindRegion($RegionID)
	{
		//check this master page for the region
		$region =& $this->FindControl($RegionID);
		
		if ($region != null)
		{
			return $region;	
		}
		else if ($this->Master == null)
		{
			//cannot find the region and no master page left so error
			trigger_error("Cannot find the region specified by ID: $RegionID.",E_USER_ERROR);
		}
		else
		{
			//otherwise check this master's master page
			return $this->Master->FindRegion($RegionID);		
		}

	}
}

?>