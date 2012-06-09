<?php

/** Either a radio/checkbox input or a select option.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_GroupItem extends PageControl
{
	/** The status of this item - selected or not.
	 * @var bool
	 */
	var $Selected = null;
	
	
	/** The identitfier of the group, used for controls loaded via template.
	 * @var string
	 */
	var $GroupID = null;
	
	
	/** The InputGroup control this belongs to
	 * @var PageControl_InputGroup
	 */
	var $Group = null;
	
	
	/** The value of this input
	* @var string
	*/
	var $Value = null; 
	
	
	/** The text to display for this input. Currenly only used for OPTION
	 * @var string
	 */
	var $Display = null; 
	
	
	function PageControl_GroupItem()
	{
		
		$this->Type = PC_GROUPITEM;
		
		parent::PageControl(__FILE__);
		
		
		$this->CanHaveControls = false;
		$this->ChildrenAreUnique = false;
		
		$this->Selected = false;
		
		$this->AppendSpecialAttribute(new ControlAttribute("group","GroupID"));
		$this->AppendSpecialAttribute(new ControlAttribute("selected","Selected"));
		$this->AppendSpecialAttribute(new ControlAttribute("value","Value"));
		$this->AppendSpecialAttribute(new ControlAttribute("display","Display"));
		
		
	}	
	
	/** Overwrite the base method to ensure that the setting of the node
	 * also loads the group control that this control belongs to.
	 */
	function SetOriginalNode($node) 
	{
		parent::SetOriginalNode($node);
	}
	
	
	/** Load the group data for this node
	 */
	function Control_Init()
	{
		parent::Control_Init();
		
		
		//check if inside a group
		if ($this->Parent->Type == PC_INPUTGROUP)
		{
			$this->GroupID = $this->Parent->ID;	
		}
		
		//do group if set
		if ($this->GroupID != null)
		{
			//trigger_error("GroupItem must have a set group.",E_USER_ERROR);
				
			//get the base page or master that we belong to
			$Base =& $this->GetBase();
			
			//var_dump($Base);
			$found =& $Base->FindControl($this->GroupID);
			
			if ($found == null)
			{
				trigger_error("Cannot find group of id: ".$this->GroupID." for groupitem of id ".$this->ID,E_USER_ERROR);
			}
			
			if ($found->Type != PC_INPUTGROUP)
			{
				trigger_error("ID for group: ".$this->GroupID." is set to a control that isn't an InputGroup.",E_USER_ERROR);
			}
			
			if (!$found->IsSelectBox())
			{
				//only assign an input node if not a select
				$this->NewNodeName = "input";	
				
			}
			
			$found->AddChild($this);
			
		}
		else
		{
			//a single non-grouped input
			$this->NewNodeName = "input";	
		}
		
	}
	
	
	/** Override the preload for groupitems without groups only.
	 *
	 */ 
	function Control_Preload()
	{
		
		if ($this->Group == null)
		{
			//get value from POSTBACK 
			$value = Postback::Get($this);
			

			if (is_string($value) && $value == $this->Value)
			{
				$this->Selected = true;	
			}
			
		}
		
		
		parent::Control_Preload();
	}
	
		
	function Control_Render(&$Owner)
	{
		//echo "GROUPITEM";
		//call parent render
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		
		//for either a non-group input or a grouped check/radio, render the value and checked status
		if ($this->Group == null || !$this->Group->IsSelectBox())	
		{
			if ($this->Value != null)
			{
				$this->NewNode->SetAttribute("value",$this->Value);
			}
			
			if ($this->Selected)
			{
				$this->NewNode->SetAttribute("checked","checked");
			}
			
			
			//if is not a select OPTION then we render as an INPUT
			if ($this->Group != null && !$this->Group->IsSelectBox())
			{
				//set attributes
				$this->NewNode->SetAttribute("name",$this->Group->GetGroupName()."[]");
				
				$this->NewNode->SetAttribute("type",$this->Group->GetGroupType());
				
				$doc = $this->NewNode->OwnerDocument();
				$x = $doc->CreateElement("label");
				$x->AppendChild($doc->CreateTextNode($this->Display));
				$x->SetAttribute("for",$this->GetClientID());
				$this->NewNode->AppendChild($x);
			}
			else
			{
				//render as single non-grouped input
				$this->NewNode->SetAttribute("name",$this->GetClientID());	
			}
		}
		else
		{	
			//do nothing, as the inputgroup control	will render us.
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