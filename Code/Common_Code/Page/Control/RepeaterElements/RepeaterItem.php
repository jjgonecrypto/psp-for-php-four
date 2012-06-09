<?php

/** A generic item node in a repeater
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class RepeaterItem
{
	
	/** The node for this item
	 * @var DomElement
	 */
	var $Node = null;
	
	
	/** The name of the datafield
	 * @var string
	 */ 
	var $Datafield = null;
	
	
	/** Instead of a name, a node that will render to a name
	 * @var DomElement
	 */
	var $DatafieldNode = null;
	
	
	/** An identifier for this item. Used in any parent that wishes 
	 * to have this item inserted into an attribute.
	 * @var string
	 */
	var $ID = null; 


	/** States whether or not this item is to set an attribute in a parent
	 * @var bool
	 */ 
	var $ForParent = null;
	
	
	/** The parent's attribute to modify
	 * @var string
	 */
	var $ParentAttribute = null;
	
	
	function RepeaterItem(&$node)
	{
		$this->Node =& $node;
		
		if ($node->HasAttribute("datafield"))
		{
			$this->Datafield = $node->GetAttribute("datafield");
		}	
		else
		{
			$datafield_arr = $node->GetElementsByTagName("datafield");
			$this->DatafieldNode = $datafield_arr[0];		
		}
		
		if ($node->GetAttribute("id") != "")
		{		
			$this->ID = $node->GetAttribute("id");
		}
		
		//check to see if this operation is for a parent
		$this->ForParent = ($node->GetAttribute("forparent") == "true");
		
		if ($this->ForParent)
		{
			$this->ParentAttribute = $node->GetAttribute("attribute");
			if ($this->ParentAttribute == "")
			{
				trigger_error("A repeateritem has been found with it's forparent flag on, but parent attribute is present.",E_USER_ERROR);	
			}	
			else if ($this->ID == null)
			{
				trigger_error("A repeateritem has been found with it's forparent flag on, but without an ID.",E_USER_ERROR);	
			}
		}
	}
	
	function GetDatafield()
	{
		if ($this->Datafield != null)
		{
			return $this->Datafield;	
		}
		else if ($this->DatafieldNode != null)
		{
			//get the content from the node 
			$data = $this->DatafieldNode->GetNodeContent();	
			
			//strip out spaces, tabs and new lines from the node
			$data = trim($data, " \t\n\r");
			
			return $data;
		}	
		else
		{
			trigger_error("Cannot load datafield as both properties Datafield and DatafieldNode are null.",E_USER_ERROR);
		}
	
	}
	
	/** Get the data from the object and either replace this item node with 
	 * the data, or set the parent's attribute with the data.
	 * @param mixed The data to replace this, either string or array
	 */
	function SetData($datastring)
	{
		
		if (is_array($datastring))
		{
			$tmp = array();
			
			foreach ($datastring as $key=>$val)
			{
				$tmp[] = "$key: $val";	
			}
			$datastring = implode(",",$tmp);	
		}
		
		$parent = $this->Node->ParentNode();
		
		if ($this->ForParent)
		{
			
			//find the attribute
			$att = $parent->GetAttribute($this->ParentAttribute);
		
			//replace it with the datastring
			$parent->SetAttribute($this->ParentAttribute, str_replace("{".$this->ID."}",$datastring,$att));
			
			$parent->RemoveChild($this->Node);
		}
		else
		{
			//simply replace this ITEM node with the data
			$doc = $this->Node->OwnerDocument();
			
			$textnode = $doc->CreateTextNode($datastring);
			
			$parent->ReplaceChild($textnode, $this->Node);
			
		}
		
	}

}
?>