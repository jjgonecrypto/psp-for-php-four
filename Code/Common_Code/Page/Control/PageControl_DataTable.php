<?php

/** A grid webpage control - a grid done in client side script (AJAX)
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_DataTable extends PageControl
{

	//NOTE: these should be static in php5
	
	/** The name of the GET variable that will signify to the page that 
	 * the instance is an Ajax load
	 * @var string
	 */
	function GetAjaxFlagKey()
	{
		return "_psp_ajax_flag";
	}
	
	/** The value of the GET variable that will signify to the page that 
	 * the instance is an Ajax load
	 * @var string
	 */
	function GetAjaxFlagValue()
	{
		return "9";
	} 
	
	/** The name of the GET variable that will signify to the page which  
	 * ajax control started the AJAX event
	 * @var string
	 */
	function GetAjaxSenderKey()
	{
		return "_psp_ajax_sender";
	} 
	
	
	/////////////////////////////////
	
	
	/**
	 * The list of buttons to use on the list.
	 *
	 * @var array
	 */
	var $Buttons = null;


	/**
	 * The list of columns to output.
	 *
	 * @var array
	 */
	var $Columns = null;


	/** The datasource that provides the content
	 *
	 * @var Datasource
	 */
	var $Datasource = null;


	/** The heading for the grid control.
	 * @var string
	 */ 
	var $Heading = null;


	/** Helper text for the grid.
	 * @var string
	 */ 
	var $HelperText = null;


	/** The type of column selection to use.
	 * @var enum RADIO | CHECKBOX | NONE
	 */
	var $Selector = null;


	/** The name of the datafield assigned to the selector. 
	 * @var string
	 */
	var $SelectorDatafield = null;


	/** Whether or not to limit the data to a number of rows and allowing paging links.
	 * @var bool
	 */
	var $EnablePaging = null;
	
	
	/** Size of the output pages.
	 * @var int
	 */
	var $PageSize = null;


	var $ButtonPostbacks = null; 
	

	function PageControl_DataTable()
	{
		$this->Type = PC_DATATABLE;
		
		parent::PageControl(__FILE__);
		
		
		$this->Buttons = array();
		
		$this->Columns = array();
		
		$this->CanHaveControls = false;
		
		$this->ChildrenAreUnique = true;
		
		//default vals
		$this->Selector = "RADIO";
		
		$this->SelectorDatafield = "id";
		
		$this->EnablePaging = false;
		
		$this->PageSize = 10;
		
		$this->DefaultCss = true;
		
		$this->ButtonPostbacks = array();
		
		
		//add to the list of special attributes
		$this->AppendSpecialAttribute(new ControlAttribute("heading","Heading"));
		$this->AppendSpecialAttribute(new ControlAttribute("pagesize","PageSize","INT"));
		$this->AppendSpecialAttribute(new ControlAttribute("selector","Selector"));
		$this->AppendSpecialAttribute(new ControlAttribute("selectordatafield","SelectorDataField"));
		$this->AppendSpecialAttribute(new ControlAttribute("enablepaging","EnablePaging","BOOL"));
		$this->AppendSpecialAttribute(new ControlAttribute("helpertext","HelperText"));
		
		
		
		
	}

	/** Set the datasource for this control.
	 *
	 */
	function SetDatasource($ds)
	{
		$this->Datasource = $ds;
	
		//$repeater_outer =& $this->FindControl("rptRows");
		//$repeater_outer->SetDatasource($ds);
	}

	function AddColumn(&$column)
	{
		$this->Columns[$column->Heading] =& $column;
	}

	function AddButton(&$button)
	{
		$this->Buttons[$button->Name] =& $button;
	}

	function &GetColumn($heading)
	{
		return $this->Columns[$heading];
	}

	function &GetButton($name)
	{
		return $this->Buttons[$name];
	} 

	function SetBottomText($text, $cssClass = null)
	{
		$litBottomText =& $this->FindControl("litBottomText");
		
		$litBottomText->Text = $text;
			
		$litBottomText->NewNodeName = "div";
		
		if ($cssClass != null)
		{
			$litBottomText->AddAttribute("class",$cssClass);
		}
	}


	function Control_Init()
	{
		parent::Control_Init();	
		
		//tell parent to register me for ajax
		$Base =& $this->GetBase();
		
		$Base->RegisterAjaxControl($this, array(&$this, "Control_Ajax"));
	
		
		//set the assigned template node
		$this->AssignedTemplateNode = $this->Template->Document->XPathQuerySingle("//div[@gridcontrol='true']");
		
	}
	
	
	function Control_Ajax()
	{
		//NOTE: Datasource must be set in Page_Init event
		if ($this->Datasource == null || count($this->Columns) < 1)
		{	
			trigger_error("The DataTable control requires it's Datasource and columns to be loaded in the Page_Init() event.", E_USER_ERROR);	
		}	
		
		//Here we load get the datasource and output it as XML 
		
		$doc = $this->Datasource->GetAsXMLDocument();
		
		$doSelector = strtoupper($this->Selector) != "NONE";
	
		//add columns to datasource
		$root = $doc->DocumentElement();
		
		$columns = $doc->CreateElement("columns");
		
		$root->InsertFirst($columns);
		
		foreach ($this->Columns as $col)
		{
			$colNode = $doc->CreateElement("column"); 
			$colNode->SetAttribute("heading",$col->Heading);
			$colNode->SetNodeContent($col->Datafield);
			$columns->AppendChild($colNode);
		}
		
		//add selector checkbox/radio selection if required
		if ($doSelector)
		{
			//add selector heading column
			
			$colNode = $doc->CreateElement("column");
			
			$colNode->SetNodeContent("selector");
			
			$colNode->SetAttribute("heading","");
			
			$columns->InsertFirst($colNode);
			
				
			//Add selectors to each row
				
			$rowNodes = $doc->XPathQuery("//row");
			
			$selector =& $this->FindControl("selectorGroup");
			
			foreach ($rowNodes as $row)
				{
					
					$selectorNode = $doc->CreateElement("selector");
					
					$selectorNode->SetAttribute("element","input");
					
					$selectorNode->SetAttribute("name",$selector->GetGroupName()."[]");
					
					$selectorNode->SetAttribute("type",strtolower($this->Selector));
					
					$inputValueNodes = $row->GetElementsByTagName($this->SelectorDatafield);
					$inputValueNode = $inputValueNodes[0];
					
					
					$selectorNode->SetAttribute("value", $inputValueNode->GetNodeContent());
					
					$row->InsertFirst($selectorNode);
			
			
				//add row code
				
			}
		}
		
		//output the data for this ajax instance	
		header('Content-type: text/xml');
		
		echo $doc->GetXML(false);
		
	}

	/** Called to redo the Load event.
	 *
		
	function Reload()
	{
		
		//add the datasource for the rows 
		$repeater_outer =& $this->FindControl("rptRows");
		
		$repeater_outer->Reload();
		
	}
	 */
	
	/** Call grid load event. 
	 * Add the buttons to the placeholder container. 
	 */
	function Control_Load()
	{
		
		//add the heading to the control
		$heading_control =& $this->FindControl("litHeading");
		$heading_control->Text = $this->Heading;
		
		//add helper text if any
		if ($this->HelperText != null)
		{
			$litHelperText =& $this->FindControl("litHelperText");
			$litHelperText->Text = $this->HelperText;	
		}
		
		/*
		//add the datasource for the columns header
		$repeater_colheaders =& $this->FindControl("rptColHeaders");
		$repeater_colheaders->SetDatasource(new Datasource($this->Columns));
		
		$repeater_outer =& $this->FindControl("rptRows");
		
		//set the row size
		if ($this->EnablePaging)
		{
			$repeater_outer->Limit = $this->PageSize;
		}
		
		//set the databinding function to this object's method
		$repeater_outer->SetDatabinder(array($this,"On_Databinding"));
		
	
		//fix the selectors
		if (strtoupper($this->Selector) == "NONE")
		{
			//remove selectors if disabled
			$selectorHeading =& $this->FindControl("selectorHeading");
			$selectorHeading->Visible = false;
			
		}
		else 
		{
			//set the input type to the one selected
			$selectorInput =& $this->FindControl("selectorGroup");
			$selectorInput->InputType =& $this->Selector;
		
			//toggle the selectall box
			if (strtoupper($this->Selector) != "CHECKBOX")
			{
				$chkSelectall =& $this->FindControl("chkSelectall");
				$chkSelectall->Visible = false;	
			}	
				
		}
		*/
		
		//add the buttons to the grid
		$plh =& $this->FindControl("plhButtons");
		$plhHelper =& $this->FindControl("plhButtonsHelper");
		
		if ($plh == null)
		{
			trigger_error("Cannot find grid buttons placeholder id in Grid template.", E_USER_ERROR);	
		}
		

		foreach ($this->Buttons as $namex=>$buttonx)
		{
			
			$button_control =& new PageControl_Button();
			
			$button_control->Name = $buttonx->Name;
			
			$button_control->DoesPostback = $buttonx->DoPostBack;
			
			if ($buttonx->RedirectUri != null)
			{
				$button_control->Href = $buttonx->RedirectUri;	
			}
			
			if ($buttonx->Title != null)
			{
				$button_control->Title = $buttonx->Title;
				
				//do helper text
				$tmp_lit = new PageControl_Literal();
				$tmp_lit->Text = $buttonx->Title;
				$tmp_lit->NewNodeName = "div";
//NOTE: temporarily removed this -> should be a javascript help popup			
//				$plhHelper->AddControl($tmp_lit);
			}
	
			$button_control->ClientFunction = "jsi.psp.getControlInstance('".$this->Type."','".$this->ID."').buttonClicked(".Utilities::BoolToString($buttonx->RequiresElements).",".Utilities::BoolToString($buttonx->AllowsMultipleElements).",'".$buttonx->ConfirmText."')";
			
			
			$plh->AddControl($button_control);
			
			
			
			//check to see if this button has a callback
			if (array_key_exists($button_control->Name, $this->ButtonPostbacks))
			{
				$this->RegisterPostbackEvent($button_control, $this->ButtonPostbacks[$button_control->Name]);
			}
		}
		
		//load the child controls
		parent::Control_Load();
		
	}
	
	/** Get a reference to the selector
	 * @return PageControl_InputGroup
	 */
	function &GetSelector()
	{
		return $this->FindControl("selectorGroup");
	}
	
	
	
	
	function Control_Render(&$Owner)
	{
		
		//call parent render
		$result = parent::Control_Render($Owner);

		if (!$result)
		{
			return false;
		}
		
		
		//NOTE: this control allows the use of [= ] inline blocks
		//NOTE: because this comes after the parent render, the JS and CSS templates 
		//		are now within this template also
		Parser::ParseInlineBlocks($this, $this->Template->Document->GetRoot());
		
		
		
		//replace this element if it exists in a template
		if ($this->OriginalNode != null)
		{
			$this->ReplaceElement(false);
		}
		
		return true;
	}

	function RegisterButtonPostback($button, $callback)
	{
		$this->ButtonPostbacks[$button->Name] = $callback;
	}
}

?>