<?php

/** A row (an instance of the template) from a repeater
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class RepeaterRow extends PageControl
{
	
	/**
	 * @var DataRow The datarow associated with this control
	 */
	var $DataRow = null;
	
	/** The row number for this control
	 * @var int
	 */
	var $RowNumber = null;
	
	
	
	function RepeaterRow($datarow, $row_nbr)
	{
		
		
		$this->DataRow = $datarow;
		
		$this->RowNumber = $row_nbr;
		
		$this->Type = PCNODE_TEMPLATE;
		
		parent::PageControl(__FILE__);
		
		$this->CanHaveControls = true;
		
		$this->ChildrenAreUnique = true;

	}	
	
	
	/** Override the node setting, to load controls for this row
	* @var DomElement
	*/
	function SetOriginalNode($node)
	{
		parent::SetOriginalNode($node);
		
		//now load controls for this row element
		Parser::ParseForControls($this, $node);
		
		
	}
	
	function Control_Load()
	{
		//$this->PrintControlTree("me",0);
		
		//load all the children
		parent::Control_Load();
	}

	function GetClientID()
	{
		return $this->Parent->GetClientID()."_".$this->RowNumber;	
	}
	
	function Control_Render(&$Owner)
	{
		//get the repeater's parent
		$parent = $Owner->OriginalNode->ParentNode();
		
		//insert this node before the parent 
		$parent->InsertBefore($this->OriginalNode, $Owner->OriginalNode);
	
	
		//call parent render with ourselves as the owner
		$result = parent::Control_Render($this);
		
		if (!$result)
		{
			return false;	
		}
		
		
		//NOTE: this control allows the use of [= ] inline blocks
		Parser::ParseInlineBlocks($this, $this->OriginalNode);
		
		
		
		//get all items within the copy
		$holder_items = $this->OriginalNode->GetElementsByTagName(PCNODE_ITEM);
		
		//NOTE:: this relative query doesn't work properly in PHP4
		//$holder_items = $ctx->xpath_eval("//".PCNODE_ITEM."[@repeater='".$this->ID."']", $holder);
		
		
		//replace each item with it's value
		for ($i = 0; $i < count($holder_items); $i++)
		{
			$clone_item = $holder_items[$i];
			
			//ensure Item has repeater attribute
			if ($clone_item->GetAttribute("repeater") == "")
			{
				trigger_error("An item has been found within a repeater (id: ".$Owner->ID." ) without a valid repeater attribute.",E_USER_ERROR);	
			}
			
			//goto next item if this one isn't for this repeater
			if ($clone_item->GetAttribute("repeater") != $Owner->ID)
			{
				continue;	
			}
			
			//create a new repeater item
			$item = new RepeaterItem($clone_item);
			
			//get the datafield for this item
			$datafield = $item->GetDatafield();
			
			
			$item->SetData($this->DataRow->Get($datafield));
			
		}
			
			
		
	}
	/*
	function GetData($datafield)
	{
		//set the data for the item
		if (is_array($this->Data))
		{
			//allow for empty entries
			if (!isset($this->Data[$datafield]))
			{
				return "";	
			}
			else
			{
				return $this->Data[$datafield];
			}
		}
		else
		{
			return $this->Data->$datafield;
		}
	}
	*/
}

?>