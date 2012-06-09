<?php

/** A form group control -> SELECT or INPUT-radio or INPUT-checkbox 
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_InputGroup extends PageControl
{
	/** The list if children for this input group.
	 * @var Array
	 */
	var $Children = null;
	
	
	/** The type of this input
	 * @var string checkbox|radio|select
	 */
	var $InputType = null;
	
	
	/** For a SELECT box only, allow multiple elements.
	 * @var bool
	 */
	var $Multiple = null; 
	
	
	//private
	/** The postback data for this group. Required if the list of children aren't known.
	 * @var Array
	 */
	var $_PostbackData = null;


	function PageControl_InputGroup()
	{
		
		//reset the type
		$this->Type = PC_INPUTGROUP;
		
		
		parent::PageControl(__FILE__);
		
		
		$this->CanHaveControls = true;
		
		$this->ChildrenAreUnique = false;
		
		$this->Multiple = false;
		
		$this->AppendSpecialAttribute(new ControlAttribute("type","InputType"));
		$this->AppendSpecialAttribute(new ControlAttribute("multiple","Multiple"));
		
		$this->Children = array();

	}	
		
	function Control_Init()
	{
		parent::Control_Init();
		
		if ($this->IsSelectBox())
		{
			$this->NewNodeName = "select";	
		}	
	}
	
	/** Load up controls from postback
	*
	*/ 
	function Control_Preload()
	{
		
		//get value from POSTBACK 
		$value = Postback::Get($this);
		
		if ($value != null && $value != "")
		{
			$this->_PostbackData = $value;
		}	
		
		//the posted data is an array
		for ($i=0; $i<count($this->Children); $i++)
		{
			$child =& $this->Children[$i];

			//search in array if array value
			if (is_array($value) && array_search($child->Value, $value) !== false)
			{
				//if found from checkbox/radio, select this input
				$child->Selected = true;	
			}
			else if (is_string($value) && $value == $child->Value)
			{
				//if found from selectbox
				$child->Selected = true;	
			}
		}
		
		
		parent::Control_Preload();
	}
	
	/** Get a delimited string of all the selected input values
	 * @param string Delimiter. Default of comma.
	 * @return string Result
	 */
	function GetSelectedString($delim = ",")
	{
		
		$found = array();
		
		if (count($this->Children) > 0)
		{	
			foreach ($this->Children as $child)
			{
				if ($child->Selected)
				{
					$found[] = $child->Value;
				}	
			}
		}
		else
		{
			$found = $this->_PostbackData;
			
			if (!is_array($this->_PostbackData))
			{
				return $this->_PostbackData;	
			}	
		}
	
		return implode($delim, $found);
	}
	
	function GetPostbackArray()
	{
		return $this->_PostbackData;	
	}

	/** Deselect all children within this group
	 *
	 */ 	
	function DeselectAll()
	{
		for ($i=0; $i<count($this->Children); $i++)
		{
			$child =& $this->Children[$i];
			$child->Selected = false;	
		}
	}
	
	/** Select all children that have a value within the array. Deselect the others.
	 * @param Array of values
	 */ 
	function SelectFromValues($value_array)
	{
		for ($i=0; $i<count($this->Children); $i++)
		{
			$child =& $this->Children[$i];
			
			if (array_search($child->Value,$value_array) !== false)
			{
				$child->Selected = true;	
			}
			else
			{
				$child->Selected = false;	
			}
		}
	}
	
	
	function GetGroupName()
	{
		return $this->ID;	
	}
	
	function GetGroupType()
	{
		return $this->InputType;	
	}
	
	/** Called by the user to add a new input group
	 * @param PageControl_GroupItem
	 */
	function AddChild(&$itemcontrol)
	{
		if (!is_a($itemcontrol,"PageControl_GroupItem"))
		{
			trigger_error("Only a GroupItem control may be added to an InputGroup.",E_USER_ERROR);
		}
		
		//set the group 
		$itemcontrol->Group =& $this;
		
		//add the child
		$this->Children[] =& $itemcontrol;	
		
		
	}
	
	/** Select a subset of children by supplying their values.
	 * @param Mixed Array of values for a subset, or string for a single value.
	 */
	function Select($value)
	{
		for ($i = 0; $i < count($this->Children); $i++)
		{
			$child =& $this->Children[$i];
			
			if (is_string($value))
			{
				if ($value == $child->Value)
				{
					$child->Selected = true;
					return;	
				}
			}
			else if (is_array($value))
			{
				if (array_search($child->Value, $value) !== false)
				{
					$child->Selected = true;			
				}
			}
		}
	}
	
	function IsSelectBox()
	{
		return strtolower($this->InputType) == "select";
	}
	
	function Control_Render(&$Owner)
	{
		
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		if ($this->IsSelectBox())
		{
			//append name for postback
			if ($this->Multiple)
			{
				$this->NewNode->SetAttribute("name",$this->GetClientID()."[]");
				$this->NewNode->SetAttribute("multiple","multiple");
			}
			else
			{
				$this->NewNode->SetAttribute("name",$this->GetClientID());
			}
		
			//render all children as OPTIONs within ourself	
			$doc = $this->NewNode->OwnerDocument();
			//echo "HJERE!!!";
			
			foreach ($this->Children as $child)
			{
				//create OPTION node
				$option = $doc->CreateElement("option");
				
				//add any extra attributes to the OPTION 
				foreach ($child->Attributes as $a)
				{
					//don't get special attributes
					if (!$child->IsSpecialAttribute($a->GetName()))
					{
						$option->SetAttribute($a->GetName(),$a->GetValue());		
					}
				}
				
				$option->SetAttribute("value",$child->Value);
				
				$option->AppendChild($doc->CreateTextNode($child->Display));
				
				if ($child->Selected)
				{
					$option->SetAttribute("selected", "selected");	
				}
				
				$this->NewNode->AppendChild($option);					
			}
		}
		else
		{
			//do nothing - since this is just a reference control 
			//for children of type INPUT
		}
		
		//replace this element if it exists in a template
		if ($this->OriginalNode != null)
		{
			$this->ReplaceElement(false);
		}
		
		return true;
	}
	
}

?>