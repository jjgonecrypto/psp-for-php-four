<?php

/** A repeater control - loops over a datasource
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Repeater extends PageControl
{
	/** The datasource that provides the content
	 *
	 * @var Datasource
	 */
	var $Datasource = null;
	
	/** The Index to begin repeating over. Default of 0.
	 * @var int
	 */
	var $Start = null; 
	
	
	/** The number of rows to repeat. Default is null (meaning all).
	 * @var int
	 */
	var $Limit = null;
	
	
	/** The template node within the repeater.
	 * @var DomElement
	 */
	var $TemplateNode = null;
	
	
	/** The delegate to call on databinding 
	 * @var ClassFunction
	 */
	var $DatabinderDelegate = null;
	
	
	var $Rows = null;
	
	
	function PageControl_Repeater()
	{
		
		$this->Type = PC_REPEATER;
		
		parent::PageControl(__FILE__);
		
		$this->CanHaveControls = true;
		$this->ChildrenAreUnique = true;
		
		$this->Start = 0;
		
		$this->Rows = array();
		
		$this->AppendSpecialAttribute(new ControlAttribute("start","Start","INT"));
		$this->AppendSpecialAttribute(new ControlAttribute("limit","Limit","INT"));
		
	}	
	
	/** Set the datasource for this control.
	 *
	 */
	function SetDatasource($ds)
	{
		if (!is_a($ds,"Datasource"))
		{
			trigger_error("Only a valid datasource may be used in SetDatasource().",E_USER_ERROR);	
		}
		
		$this->Datasource = $ds;
	}
	
	/** Set the databinder delegate
	 *
	 */
	function SetDatabinder($delegate)
	{
		$this->DatabinderDelegate = $delegate;
	}
	
	/** Override the node setting, to set the template node also
	 * @var DomElement
	 */
	function SetOriginalNode($node)
	{
		parent::SetOriginalNode($node);
		
		$nodes = $node->GetElementsByTagName(PCNODE_TEMPLATE);
		
		if (count($nodes) < 1)
		{
			trigger_error("No template node was found. Each repeater control must contain a single template node.",E_USER_ERROR);
		}
		
		$this->TemplateNode = $nodes[0];
	}
	
	/** Called to redo the Load event.
	 *
	 */
	function Reload()
	{
		//reset the repeater rows
		$this->Rows = array();	
		
		$this->Control_Load();
	}
	
	function Control_Load()
	{
			
		//this control cannot be loaded programmatically
		if ($this->OriginalNode == null)
		{
			trigger_error("The Repeater control cannot be loaded programmatically. It must exist within a template.", E_USER_ERROR);	
		}	
		
		//dont render if no datasource
		if ($this->Datasource == null)
		{
			return false;
		}
		
		//load rows
		$parent = $this->OriginalNode->ParentNode();
		
		$enum = $this->Datasource->GetEnumerator();
		
		$rowcounter = 0;
		
		//loop over the datasource
		while (($datarow = $enum->GetNext()) != null)
		{
			
			//if the start hasn't been reached, then keep looping
			if ($enum->Counter < $this->Start)
			{
				continue;	
			}
			else
			{
				//if the limit has been reached, then stop rendering output
				if (is_numeric($this->Limit) && $rowcounter == $this->Limit)
				{
					break;	
				}
				$rowcounter++;	
			}
			
			//clone this node
			//$holder = $parent->InsertBefore($this->TemplateNode->CloneNode(true), $this->OriginalNode);
			
			$holder = $this->TemplateNode->CloneNode(true);
			
			
			//create repeater row control 
			$row_control =& new RepeaterRow($datarow, $rowcounter-1);
			
			//set up us as a parent
			$row_control->SetParent(&$this);
			
			//set the node of this row control
			$row_control->SetOriginalNode($holder);
			

			//echo "<p>ACTION HERE - setting ".$row_control->ID." to ".$this->ID."</p>";
			
			//initialise the control now it has been added to the parent
			$row_control->Control_Init();
			
			
			//call user databinding function (if any) with this control
			//NOTE: before preload, to allow for postback
			if ($this->DatabinderDelegate != null)
			{
				call_user_func_array($this->DatabinderDelegate, array(&$row_control));
			}
			
			
			//call methods
			$row_control->Control_Preload();
			
			//call methods
			$row_control->Control_Load();
			
			//append the row
			$this->Rows[] =& $row_control;
		}
		
		
		//load all the children
		parent::Control_Load();
		
		
	}
	
	function Control_Render(&$Owner)
	{
		
		//dont render if no datasource
		if ($this->Datasource == null)
		{
			$this->RemoveMe();
			
			return false;
		}
		
		//call parent render
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		$parent = $this->OriginalNode->ParentNode();
		
		//render the row objects
		for ($i = 0; $i < count($this->Rows); $i++)
		{	
			$row_control =& $this->Rows[$i];
			
			//$result = $this->Rows
			//render this control
			$row_control->Control_Render($this);
		
			//insert this row into the template before the control
			$this->OriginalNode->InsertBeforeMe($row_control->OriginalNode->ChildNodes());
			
			//remove the containing element that we cloned
			$parent->RemoveChild($row_control->OriginalNode);
		}
		
		
		//remove me 
		$this->RemoveMe();
		
		return true;
	}

	
	
}

?>