<?php

/** An ajax page control
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class PageControl_Ajax extends PageControl
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
	
	
	/** The ID of the page control that will begin the ajax transaction
	 * @var string
	 */
	var $InstancerID = null; 
	
	
	/** The page control that will begin the ajax transaction
	 * @var PageControl
	 */
	var $Instancer = null;
	
	
	/**
	 * @var Array of type AjaxIOElement.
	 */
	var $InputControls = null;
	
	/**
	 * @var Array of type AjaxIOElement.
	 */
	var $OutputControls = null;
	
	/**
	 * @var Array of type string (onstartup function calls)
	 */
	var $StartupFunctions = null; 
	
	/**
	 * @var Array of type string (oncomplete function calls)
	 */
	var $CompleteFunctions = null;
	
	/** The name of a javascript function to process each row of data output.
	 * @var String
	 */
	var $OutputFunction = null;
	
	/** The datasource used for responding to Ajax transactions
	 * @var Datasource
	 */
	var $Ajax_OutputDatasource = null; 
	
	/** The current row of output for the datasource
	 * @var DataRow
	 */
	var $Ajax_OutputCurrentRow = null;
	
	function PageControl_Ajax()
	{
		$this->Type = PC_AJAX;
		
		parent::PageControl(__FILE__);
		
		$this->CanHaveControls = false;
		$this->ChildrenAreUnique = false;
		
		$this->InputControls = array();
		$this->OutputControls = array();
		$this->StartupFunctions = array();
		$this->CompleteFunctions = array();
		
		$this->AppendSpecialAttribute(new ControlAttribute("instancer","InstancerID"));
		$this->AppendSpecialAttribute(new ControlAttribute("outputfunction","OutputFunction"));
		
		$this->NewNodeName = "";
	}	
	
	/** Find and load the instancer control that begins this transaction.
	 *
	 */ 
	function Control_Init()
	{
		parent::Control_Init();
		
		//get the base page or master that we belong to
		$Base =& $this->GetBase();
		
		//var_dump($Base);
		$found =& $Base->FindControl($this->InstancerID);
		
		if ($found == null)
		{
			trigger_error("Cannot find ajax instancer of id: ".$this->InstancerID." for ajax control of id: ".$this->ID,E_USER_ERROR);
		}
		
		$this->Instancer =& $found;
		
	}
	
	
	// {{{ Ajax Transaction Methods 
	
	function Ajax_Output()
	{
		//output the data for this ajax instance	
		header('Content-type: text/xml');
	
		$doc = $this->Ajax_OutputDatasource->GetAsXMLDocument();
		
		echo $doc->GetXML(false);
	}
	
	function Ajax_GetInput($label)
	{
		return $_GET[$label];	
	}
	
	/** Create a new row of output in the ajax response.
	 *
	 */
	function Ajax_NewOutputRow()
	{
		if ($this->Ajax_OutputDatasource == null)
		{
			$this->Ajax_OutputDatasource = new Datasource();	
		}
		
		$this->Ajax_CurrentDataRow =& new DataRow();
		
		$this->Ajax_OutputDatasource->AddRow(&$this->Ajax_CurrentDataRow);
	}
	
	function Ajax_AddRowField($name, $value)
	{
		$this->Ajax_CurrentDataRow->Add($name, htmlspecialchars($value));	
	}
	
	// }}}
	
	
	function Control_Load()
	{
		
		//NOTE: currently this control can't be added programmatically
		//could be changed in the future
		if ($this->OriginalNode == null)
		{
			trigger_error("The AJAX Control cannot be added programmatically.",E_USER_ERROR);	
		}
		
		//for IO, need to find this control
		$base =& $this->GetBase();
		
		//find all input nodes within OriginalNode 
		$this->LoadInputData($base, PCNODE_AJAXINPUT, $this->InputControls, "rptInput", true);
		
		//find all output nodes within OriginalNode 
		$this->LoadInputData($base, PCNODE_AJAXOUTPUT, $this->OutputControls, "rptOutput", true);
		
		//find all starter functions
		$this->LoadInputData($base, PCNODE_AJAXSTARTFNC, $this->StartupFunctions, "rptStartup");
		
		//find all complete functions 
		$this->LoadInputData($base, PCNODE_AJAXCOMPLETEFNC, $this->CompleteFunctions, "rptComplete");
		
		//set an output function if one is defined
		if ($this->OutputFunction != null)
		{
			$this->litOutputFunction =& $this->FindControl("litOutputFunction");
			
			$this->litOutputFunction->Text = "tmp_ajax.setOutputFunction(".$this->OutputFunction.");";
		}
		
		//find the executing control
		$this->Instancer->Href = "javascript: void(0);";
		
		$this->Instancer->AddAttribute("onclick","jsi.psp.getControlInstance('".$this->Type."','".$this->ID."').start();");
		
		parent::Control_Load();	
	}
	
	
	//private
	/** Specific function to load node data in from this Ajax control.
	 * @param String The name of the node type
	 * @param Array The property of this object to put the data into
	 * @param String The ID of the repeater to bind the found data to.
	 * @param Bool [Optional, default is FALSE] Whether or not this is one 
	 *				of the I/O elements, and thus requires the use of labels.
	 */
	function LoadInputData(&$base, $tag, &$array, $repeaterID, $isIOElement = false)
	{
		//find all input nodes within OriginalNode
		$nodes = $this->OriginalNode->GetElementsByTagName($tag);
		
		$data = array();
		
		foreach ($nodes as $n)
		{
			$content = $n->GetNodeContent();
			
			
			//do IO elements differently
			if ($isIOElement)
			{
				
					//find the control 
					$found_control =& $base->FindControl($content);
					
					//get the label 
					$label = $n->GetAttribute("label");
					
					//record this object
					$array[] = new AjaxIOElement($label, $found_control);
					
					//append datasource for client data 
					$data[] = array("label"=>$label,"objectID"=>$found_control->GetClientID()); 
				
			}
			else
			{
				//keep a copy of the names of the client functions
				$array[] = $content;
				
				//append datasource for client data 	
				$data[] = array("objectID"=>$content); 
			}
		}
		
		//find input repeater
		$repeater =& $this->FindControl($repeaterID);
		
		//create datasource
		//append datasource to found repeater
		$repeater->SetDatasource(new Datasource($data));
		
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
	
	
	/** Static method: discover if this instance is an Ajax event.
	 * @return bool Whether or not the ajax querystring is defined
	 */
	function IsAjaxState()
	{
		$flag = PageControl_Ajax::GetAjaxFlagKey();
		$flagval = PageControl_Ajax::GetAjaxFlagValue();
		
		return isset($_GET[$flag]) && $_GET[$flag] == $flagval;
	}
	
	
	/** Static method: get the id of the control that sent the state 
	 * @return string ID of ajax control that caused this state
	 */
	function GetAjaxSenderId()
	{
		return $_GET[PageControl_Ajax::GetAjaxSenderKey()];
	}


	/** Set the Ajax flag to the querystring, and the name of this control.
	 * NOTE: Could have to keep alive POST info here - but this is 
	 * a can of worms
	 */
	function SetAjaxState($currentUri)
	{
		$newUri = $currentUri;
		
		//TODO: append Ajax qs to uri 
		
		return $newUri;
		
		//THIS IS CALLED by the ajax control which inserts this into 
		//it's javascript
	}
	

}
?>