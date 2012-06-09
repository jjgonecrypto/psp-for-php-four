<?php 

/** Represents a resource file on the website
* 
* @package psp.for.php.four
* @author Justin J. Moses <justin@justinjmoses.com.au>
* @copyright Copyright © 2007 Justin J. Moses
* @version php4
* @license http://web.justinjmoses.com.au/psp
*/

class Resource
{
	var $filename = null;
	
	var $Document = null;
	
	
	function Resource($filename)
	{
		$this->filename = $filename;
		
		$this->Document = new PspDomDocument();
		
		$this->Document->LoadFile($this->filename);	
	}	
	
	
	function GetItemString($resID)
	{
		
		$node = $this->Document->XPathQuerySingle("//item[@id='$resID']", null, false);
		
		return $node->GetNodeContent();
	}
	
	
	/** This function will set any properties in the control to 
	 * any values found in the resource file.
	 */
	function PopulateControl(&$control)
	{
		if ($control->ResourceID == null)
		{
			trigger_error("Resource.PopulateControl(): Cannot populate a control without a ResourceID.",E_USER_ERROR);	
		}
		
		//search through this resource file for the control
		$node = $this->Document->XPathQuerySingle("//".$control->Type."[@id='".$control->ResourceID."']", null, false);
		
		
		if ($node == null)
		{
			trigger_error("Cannot find a ".$control->Type." with ResourceID '".$control->ResourceID."'.",E_USER_ERROR);		
		}
			
		//for each child node, try to set that property in the control
		$children = $node->ChildNodes();
		
		
		foreach ($children as $child)
		{
			$name = $child->GetNodeName();
			
			
			if ($child->IsElement())
			{
				//NOTE: Be very careful here.... 
				//Don;t allow overwrites of the value
				
				if (is_object($control->$name))
				{
					trigger_error("A resource entry has been detected trying to override a property that contains an object. Type: ".$control->Type.", ResourceID: ".$control->ResourceID,E_USER_ERROR);	
				}
				
				
				if ($control->Type == PC_LITERAL && $name == "Text")
				{
					//for Literal controls Text only, load the text as an Element
					$control->$name = $child->CloneNode(true);
				}
				else
				{
					$control->$name = $child->GetNodeContent();	
				}	
			}

			
		}	
		
	}
	
}

?>