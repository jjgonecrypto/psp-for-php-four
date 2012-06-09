<?php

/** A client controlled data input table that interfaces with the server via XML. 
 *
 * Essentially a client-side table that allows a variable 
 * amount of user input, and updates a hidden field with an XML representation 
 * of the data. 
 *
 *  Note: this control requires the installation of the jsi javascript.interface
 * @see http://web.justinjmoses.com.au/jsi
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 * 
 */

class PageControl_InputTable extends PageControl
{
	
	//private
	/** The document received via postback. 
	 * @var PspDomDocument
	 */
	var $PostedXMLDocument = null;
	
	
	/** The heading of this input table
	 * @var string
	 */
	var $Heading = null;
	

	//private
	var $Cells = null;
	
	
	function PageControl_InputTable()
	{
		$this->Type = PC_INPUTTABLE;
		
		parent::PageControl(__FILE__);
		
		$this->CanHaveControls = true;
		
		$this->ChildrenAreUnique = true;
		
		$this->AppendSpecialAttribute(new ControlAttribute("heading","Heading"));
		
		
	}	
	
	function Control_Init()
	{
		parent::Control_Init();
		
		$this->AssignedTemplateNode = $this->Template->Document->XPathQuerySingle("/div");
		
	}
	
	/** Load up controls from postback
	*
	*/ 
	function Control_Preload()
	{
		$inData =& $this->FindControl("inData");
		
		$base = $this->GetBase();
		
		if ($base->IsPostBack)
		{
			//get xml data
			$xml = Postback::DirtyMe(Postback::Get($inData));
			
			$this->PostedXMLDocument = new PspDomDocument();
			
			$this->PostedXMLDocument->LoadXML($xml);
		}
	
		parent::Control_Preload();
		
	}
	
	function GetPostedXMLDoc()
	{
		return $this->PostedXMLDocument;	
	}
	
	
	
	function Reset()
	{
		$inData =& $this->FindControl("inData");
	
		$inData->Value = "";
		
		$this->PostedXMLDocument = null;
			
	}
	
	function Control_Load()
	{
		
		//this control cannot be loaded programmatically
		if ($this->OriginalNode == null)
		{
			trigger_error("The InputTable control cannot be loaded programmatically. It must exist within a template.", E_USER_ERROR);	
		}	
		
		
		$cells = $this->OriginalNode->GetElementsByTagName("cellinput");
		
		foreach ($cells as $cellnode)
		{
			$heading = $cellnode->GetAttribute("heading");
			$cellnode->RemoveAttribute("heading");
			
			$lefttext = $cellnode->GetAttribute("lefttext");
			$cellnode->RemoveAttribute("lefttext");
			
			$righttext = $cellnode->GetAttribute("righttext");
			$cellnode->RemoveAttribute("righttext");
			
			//append to local cells
			$this->Cells[] = new InputTableCell($heading,$lefttext,$righttext,$cellnode->GetAttributes());
		}
		
		parent::Control_Load();
			
	}
	
	/** Call to ensure preloaded data can be set to the input table.
	 * @param Datasource
	 */
	function SetPreloaderDatasource($Datasource)
	{
		
		$this->rptPreloader =& $this->FindControl("rptPreloader");
		
		$this->rptPreloader->SetDatasource($Datasource);
		
		$this->rptPreloader->SetDatabinder(array(&$this,"On_Databinding"));
		
	}
	
	
	function On_Databinding(&$row)
	{
		$litIns =& $row->FindControl("litInserter");
		
		$enum = $row->DataRow->GetEnumerator();
		
		$val_array = array();
			
		while (($datafield = $enum->GetNext()) != null)
		{
			if (is_numeric($datafield->Value))
			{
				$val_array[] = $datafield->Value;	
			}			
			else
			{
				$val_array[] = "'".$datafield->Value."'";
			}
		}
		
		$litIns->Text = implode(",",$val_array);	
	}
	
	
	/** Render this control.
	 * 
	 */ 
	function Control_Render(&$Owner)
	{
		
		$result = parent::Control_Render($Owner);
		
		if (!$result)
		{
			return false;	
		}
		
		
		//NOTE: this control allows the use of [= ] inline blocks
		//NOTE: because this comes after the parent render, the JS and CSS templates 
		//		are now within this template also
		Parser::ParseInlineBlocks($this, $this->Template->Document->GetRoot());
		
		
		//Output the cell templates
		$cellTemplates =& $this->FindControl("cellTemplates");
		
		$doc = $this->OriginalNode->OwnerDocument();
		
		//get the supplied templates
		foreach ($this->Cells as $cell)
		{
			$cellElement = $doc->CreateElement("span");		
			
			$cell->RenderOnto(&$cellElement);
			
			$cellTemplates->OriginalNode->AppendExternal($cellElement);
		}
		
		//remove me if exists in template 
		if ($this->OriginalNode != null)
		{
			//$this->RemoveMe();
			$this->ReplaceElement(false);
		}
		
		return true;
	}
	
}



?>